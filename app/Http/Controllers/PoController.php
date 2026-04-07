<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\OrderService;
use App\Services\PaymentProofService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PoController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly PaymentProofService $proofService,
    ) {}

    public function show(Product $product): View|RedirectResponse
    {
        abort_unless($product->is_active, 404);

        if ($product->stok_karton > 0) {
            return redirect()->route('catalog.show', $product);
        }

        $customer = auth('customer')->user();

        return view('po.show', compact('product', 'customer'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $data = $request->validate([
            'quantity'       => ['required', 'integer', 'min:1', 'max:9999'],
            'customer_name'  => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'quantity.required'       => 'Jumlah pesanan wajib diisi.',
            'quantity.min'            => 'Minimal pesan 1 karton.',
            'customer_name.required'  => 'Nama pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'proof.required'          => 'Bukti transfer wajib diupload.',
            'proof.mimes'             => 'Format bukti harus JPG, PNG, atau PDF.',
            'proof.max'               => 'Ukuran file maksimal 5 MB.',
        ]);

        $customer = auth('customer')->user();
        $order    = $this->orderService->placePOOrder($customer, $product, $data);
        $this->proofService->upload($order, $request->file('proof'));

        return redirect()->route('orders.payment', $order)
            ->with('success', "Pesanan PO {$order->invoice_number} berhasil dibuat!");
    }
}
