@extends('layouts.app')

@section('title', 'Detail Pesanan — ' . $order->invoice_number)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('orders.index') }}"
           class="p-2 text-gray-500 hover:text-sky-600 bg-white rounded-lg shadow-sm border border-gray-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $order->invoice_number }}</h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $order->status->badgeClass() }}">
                    {{ $order->status->label() }}
                </span>
                @if($order->is_po)
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-100 text-sky-700">Pre-Order</span>
                @endif
                <span class="text-sm text-gray-500">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</span>
            </div>
        </div>
    </div>

    {{-- Rejection notice --}}
    @if($order->status->value === 'rejected' && $order->rejection_reason)
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <p class="font-semibold text-red-800 mb-1">Pembayaran Ditolak</p>
        <p class="text-sm text-red-700">{{ $order->rejection_reason }}</p>
        <a href="{{ route('orders.payment', $order) }}"
           class="inline-block mt-3 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition-colors">
            Upload Ulang Bukti Transfer
        </a>
    </div>
    @endif

    <div class="space-y-6">

        {{-- PO Banner --}}
        @if($order->is_po)
        <div class="bg-sky-50 border border-sky-200 rounded-xl px-5 py-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-sky-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 11H4L5 9z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-sky-800">Pesanan Pre-Order (PO)</p>
                <p class="text-xs text-sky-700 mt-0.5">Pesanan ini adalah PO — barang akan disiapkan setelah pembayaran dikonfirmasi. Estimasi ketersediaan akan dikonfirmasi oleh admin.</p>
            </div>
        </div>
        @endif

        {{-- Order items --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50">
                <h2 class="font-semibold text-gray-900">Item Pesanan</h2>
            </div>
            <div class="p-6 space-y-4">
                @foreach($order->items as $item)
                <div class="flex items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                        <p class="text-sm text-gray-500">{{ $item->product_size }} × {{ $item->quantity }} karton</p>
                        <p class="text-xs text-gray-400">Rp {{ number_format($item->price_per_unit, 0, ',', '.') }} / karton</p>
                    </div>
                    <p class="font-semibold text-gray-900 shrink-0">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </p>
                </div>
                @endforeach

                <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-900 text-base">
                        <span>Total</span>
                        <span class="text-sky-700">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Shipping info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Informasi Pengiriman</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">Pemesan</p>
                    <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">WhatsApp</p>
                    <p class="font-medium text-gray-900">{{ $order->customer_phone }}</p>
                </div>
                @if($order->notes)
                <div class="sm:col-span-2">
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">Catatan</p>
                    <p class="text-gray-700">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Payment proof history --}}
        @if($order->paymentProofs->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Riwayat Bukti Transfer</h2>
            <div class="space-y-3">
                @foreach($order->paymentProofs->sortByDesc('created_at') as $proof)
                <div class="flex items-center justify-between gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                            @if($proof->isPdf())
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                            @else
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $proof->original_filename }}</p>
                            <p class="text-xs text-gray-400">{{ $proof->fileSizeForHumans() }} · {{ $proof->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('orders.payment.proof', [$order, $proof]) }}"
                       target="_blank"
                       class="shrink-0 text-xs font-medium text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                        Lihat
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Bottom actions --}}
    <div class="mt-6 flex gap-3">
        @if($order->canUploadProof())
        <a href="{{ route('orders.payment', $order) }}"
           class="flex-1 text-center px-4 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl transition-colors">
            {{ $order->status->value === 'rejected' ? 'Upload Ulang Bukti' : 'Bayar Sekarang' }}
        </a>
        @elseif(in_array($order->status->value, ['awaiting_payment', 'payment_uploaded']))
        <a href="{{ route('orders.payment', $order) }}"
           class="flex-1 text-center px-4 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl transition-colors">
            Halaman Pembayaran
        </a>
        @endif
    </div>

</div>
@endsection
