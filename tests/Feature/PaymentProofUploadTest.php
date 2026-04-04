<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentProofUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    private function makeCustomer(): Customer
    {
        return Customer::factory()->create();
    }

    private function makeOrder(Customer $customer, OrderStatus $status = OrderStatus::AwaitingPayment): Order
    {
        return Order::create([
            'customer_id'      => $customer->id,
            'invoice_number'   => 'MBG-TEST-' . uniqid(),
            'customer_name'    => $customer->name,
            'customer_phone'   => '081234567890',
            'customer_address' => 'Jl. Test No. 1',
            'subtotal'         => 200000,
            'shipping_cost'    => 0,
            'total'            => 200000,
            'status'           => $status,
            'bank_name'        => 'BCA',
            'bank_account'     => '1234567890',
            'bank_holder'      => 'Test',
        ]);
    }

    /** Upload JPG valid berhasil, status berubah ke payment_uploaded */
    public function test_valid_jpg_upload_succeeds(): void
    {
        $customer = $this->makeCustomer();
        $order    = $this->makeOrder($customer);
        $file     = UploadedFile::fake()->image('bukti.jpg', 400, 400)->size(500);

        $this->actingAs($customer, 'customer')
             ->post(route('orders.payment.upload', $order), ['proof' => $file])
             ->assertRedirect(route('orders.payment', $order));

        $order->refresh();
        $this->assertEquals(OrderStatus::PaymentUploaded, $order->status);
        $this->assertCount(1, $order->paymentProofs);
    }

    /** Upload PDF valid berhasil */
    public function test_valid_pdf_upload_succeeds(): void
    {
        $customer = $this->makeCustomer();
        $order    = $this->makeOrder($customer);
        $file     = UploadedFile::fake()->create('bukti.pdf', 1024, 'application/pdf');

        $this->actingAs($customer, 'customer')
             ->post(route('orders.payment.upload', $order), ['proof' => $file])
             ->assertRedirect(route('orders.payment', $order));

        $order->refresh();
        $this->assertEquals(OrderStatus::PaymentUploaded, $order->status);
    }

    /** Upload tanpa file gagal validasi */
    public function test_upload_without_file_fails(): void
    {
        $customer = $this->makeCustomer();
        $order    = $this->makeOrder($customer);

        $this->actingAs($customer, 'customer')
             ->post(route('orders.payment.upload', $order), [])
             ->assertSessionHasErrors('proof');
    }

    /** Tipe file tidak diizinkan ditolak */
    public function test_disallowed_file_type_rejected(): void
    {
        $customer = $this->makeCustomer();
        $order    = $this->makeOrder($customer);
        $file     = UploadedFile::fake()->create('malware.exe', 100, 'application/octet-stream');

        $this->actingAs($customer, 'customer')
             ->post(route('orders.payment.upload', $order), ['proof' => $file])
             ->assertSessionHasErrors('proof');
    }

    /** File terlalu besar ditolak */
    public function test_oversized_file_rejected(): void
    {
        $customer = $this->makeCustomer();
        $order    = $this->makeOrder($customer);
        $file     = UploadedFile::fake()->image('big.jpg')->size(6000); // 6 MB > 5 MB limit

        $this->actingAs($customer, 'customer')
             ->post(route('orders.payment.upload', $order), ['proof' => $file])
             ->assertSessionHasErrors('proof');
    }

    /** Guest tidak bisa upload */
    public function test_guest_cannot_upload(): void
    {
        $customer = $this->makeCustomer();
        $order    = $this->makeOrder($customer);
        $file     = UploadedFile::fake()->image('bukti.jpg');

        $this->post(route('orders.payment.upload', $order), ['proof' => $file])
             ->assertRedirect(route('login'));
    }

    /** Tidak bisa upload saat status payment_uploaded */
    public function test_cannot_upload_when_already_uploaded(): void
    {
        $customer = $this->makeCustomer();
        $order    = $this->makeOrder($customer, OrderStatus::PaymentUploaded);
        $file     = UploadedFile::fake()->image('bukti.jpg', 200, 200)->size(100);

        $this->actingAs($customer, 'customer')
             ->post(route('orders.payment.upload', $order), ['proof' => $file])
             ->assertForbidden();
    }

    /** File tersimpan di private storage, BUKAN public disk */
    public function test_proof_stored_in_private_storage(): void
    {
        $customer = $this->makeCustomer();
        $order    = $this->makeOrder($customer);
        $file     = UploadedFile::fake()->image('bukti.jpg')->size(200);

        $this->actingAs($customer, 'customer')
             ->post(route('orders.payment.upload', $order), ['proof' => $file]);

        $proof = $order->refresh()->latestPaymentProof;
        $this->assertNotNull($proof);
        Storage::disk('local')->assertExists($proof->file_path);
        Storage::disk('public')->assertMissing($proof->file_path);
    }
}
