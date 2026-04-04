<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(private readonly CartService $cart) {}

    /**
     * Place a new order from the current cart.
     *
     * @param  array{
     *   customer_name: string,
     *   customer_phone: string,
     *   customer_address: string,
     *   notes: ?string,
     *   shipping_cost: int,
     * } $data
     */
    public function placeOrder(Customer $customer, array $data): Order
    {
        $cartItems    = $this->cart->all();
        $subtotal     = $this->cart->total();
        $shippingCost = (int) ($data['shipping_cost'] ?? 0);
        $total        = $subtotal + $shippingCost;

        $order = Order::create([
            'customer_id'      => $customer->id,
            'invoice_number'   => $this->generateInvoiceNumber(),
            'customer_name'    => $data['customer_name'],
            'customer_phone'   => $data['customer_phone'],
            'customer_address' => $data['customer_address'],
            'notes'            => $data['notes'] ?? null,
            'subtotal'         => $subtotal,
            'shipping_cost'    => $shippingCost,
            'total'            => $total,
            'status'           => OrderStatus::AwaitingPayment,
            'bank_name'        => config('payment.bank_name'),
            'bank_account'     => config('payment.bank_account'),
            'bank_holder'      => config('payment.bank_holder'),
        ]);

        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id'     => $item['product_id'],
                'product_name'   => $item['name'],
                'product_size'   => $item['size'],
                'price_per_unit' => $item['price'],
                'quantity'       => $item['quantity'],
                'subtotal'       => $item['price'] * $item['quantity'],
            ]);
        }

        $this->cart->clear();

        try {
            $customer->notify(new OrderCreatedNotification($order));
        } catch (\Throwable) {
            // Silent fail — email not configured
        }

        return $order;
    }

    public function approvePayment(Order $order): void
    {
        $order->update([
            'status'  => OrderStatus::Paid,
            'paid_at' => now(),
        ]);
    }

    public function rejectPayment(Order $order, string $reason): void
    {
        $order->update([
            'status'           => OrderStatus::Rejected,
            'rejection_reason' => $reason,
        ]);
    }

    private function generateInvoiceNumber(): string
    {
        do {
            $invoice = 'MBG-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (Order::where('invoice_number', $invoice)->exists());

        return $invoice;
    }
}
