<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPolicyTest extends TestCase
{
    use RefreshDatabase;

    private function makeCustomer(array $attrs = []): Customer
    {
        return Customer::factory()->create($attrs);
    }

    private function makeOrder(Customer $customer, array $attrs = []): Order
    {
        return Order::create(array_merge([
            'customer_id'      => $customer->id,
            'invoice_number'   => 'MBG-TEST-' . uniqid(),
            'customer_name'    => $customer->name,
            'customer_phone'   => '081234567890',
            'customer_address' => 'Jl. Test No. 1',
            'subtotal'         => 100000,
            'shipping_cost'    => 0,
            'total'            => 100000,
            'status'           => OrderStatus::AwaitingPayment,
            'bank_name'        => 'BCA',
            'bank_account'     => '1234567890',
            'bank_holder'      => 'Test',
        ], $attrs));
    }

    /** Customer dapat melihat order miliknya */
    public function test_customer_can_view_own_order(): void
    {
        $customer = $this->makeCustomer();
        $order    = $this->makeOrder($customer);

        $this->actingAs($customer, 'customer')
             ->get(route('orders.show', $order))
             ->assertOk();
    }

    /** Customer TIDAK BISA melihat order milik customer lain — harus 403 */
    public function test_customer_cannot_view_other_customers_order(): void
    {
        $owner = $this->makeCustomer();
        $other = $this->makeCustomer();
        $order = $this->makeOrder($owner);

        $this->actingAs($other, 'customer')
             ->get(route('orders.show', $order))
             ->assertForbidden();
    }

    /** Guest diredirect ke login saat akses order detail */
    public function test_guest_is_redirected_to_login(): void
    {
        $customer = $this->makeCustomer();
        $order    = $this->makeOrder($customer);

        $this->get(route('orders.show', $order))
             ->assertRedirect(route('login'));
    }

    /** Upload proof diizinkan saat status awaiting_payment */
    public function test_customer_can_upload_proof_when_awaiting_payment(): void
    {
        $customer = $this->makeCustomer();
        $order    = $this->makeOrder($customer, ['status' => OrderStatus::AwaitingPayment]);

        $this->assertTrue($order->canUploadProof());
        $this->assertTrue($customer->can('uploadProof', $order));
    }

    /** Upload proof TIDAK diizinkan saat status payment_uploaded */
    public function test_customer_cannot_upload_when_already_uploaded(): void
    {
        $customer = $this->makeCustomer();
        $order    = $this->makeOrder($customer, ['status' => OrderStatus::PaymentUploaded]);

        $this->assertFalse($order->canUploadProof());
        $this->assertFalse($customer->can('uploadProof', $order));
    }

    /** Upload proof diizinkan lagi saat status rejected */
    public function test_customer_can_upload_when_rejected(): void
    {
        $customer = $this->makeCustomer();
        $order    = $this->makeOrder($customer, [
            'status'           => OrderStatus::Rejected,
            'rejection_reason' => 'Nominal tidak sesuai',
        ]);

        $this->assertTrue($order->canUploadProof());
        $this->assertTrue($customer->can('uploadProof', $order));
    }

    /** Customer lain tidak bisa upload proof order orang lain */
    public function test_other_customer_cannot_upload_proof(): void
    {
        $owner = $this->makeCustomer();
        $other = $this->makeCustomer();
        $order = $this->makeOrder($owner, ['status' => OrderStatus::AwaitingPayment]);

        $this->assertFalse($other->can('uploadProof', $order));
    }
}
