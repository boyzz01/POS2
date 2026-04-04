<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly OrderService $orderService,
    ) {}

    public function index(): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect(route('cart.index'))->with('error', 'Keranjang belanja kosong.');
        }

        $items    = $this->cart->all();
        $subtotal = $this->cart->total();
        $customer = auth('customer')->user();

        return view('checkout.index', compact('items', 'subtotal', 'customer'));
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect(route('cart.index'))->with('error', 'Keranjang belanja kosong.');
        }

        $order = $this->orderService->placeOrder(auth('customer')->user(), $request->validated());

        return redirect(route('orders.payment', $order))
            ->with('success', "Pesanan {$order->invoice_number} berhasil dibuat! Segera lakukan pembayaran.");
    }
}
