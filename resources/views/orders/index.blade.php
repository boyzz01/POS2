@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Pesanan Saya</h1>

    @if($orders->isEmpty())
    <div class="text-center py-24 bg-white rounded-2xl shadow-sm border border-gray-100">
        <svg class="w-20 h-20 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p class="text-xl font-semibold text-gray-400 mb-2">Belum ada pesanan</p>
        <p class="text-gray-400 text-sm mb-6">Mulai pesan produk kebutuhan MBG Anda</p>
        <a href="{{ route('catalog.index') }}"
           class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white font-medium px-6 py-2.5 rounded-lg transition-colors">
            Lihat Katalog
        </a>
    </div>

    @else
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="font-bold text-gray-900">{{ $order->invoice_number }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $order->status->badgeClass() }}">
                                {{ $order->status->label() }}
                            </span>
                            @if($order->is_po)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-100 text-sky-700">
                                Pre-Order
                            </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                    <p class="font-bold text-sky-700 text-lg shrink-0">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </p>
                </div>

                <div class="mt-3 flex flex-wrap gap-1.5">
                    @foreach($order->items->take(3) as $item)
                    <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full">
                        {{ $item->product_name }} × {{ $item->quantity }}
                    </span>
                    @endforeach
                    @if($order->items->count() > 3)
                    <span class="text-xs bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full">
                        +{{ $order->items->count() - 3 }} lainnya
                    </span>
                    @endif
                </div>

                @if($order->status->value === 'rejected' && $order->rejection_reason)
                <div class="mt-3 bg-red-50 rounded-lg px-3 py-2 text-xs text-red-700">
                    <span class="font-semibold">Alasan penolakan:</span> {{ $order->rejection_reason }}
                </div>
                @endif
            </div>

            <div class="border-t border-gray-50 px-5 py-3 flex gap-3 bg-gray-50">
                <a href="{{ route('orders.show', $order) }}"
                   class="text-sm font-medium text-gray-600 hover:text-sky-600 transition-colors">
                    Detail
                </a>
                @if($order->canUploadProof())
                <a href="{{ route('orders.payment', $order) }}"
                   class="text-sm font-semibold text-sky-600 hover:text-sky-700 transition-colors
                          {{ $order->status->value === 'rejected' ? 'text-red-600 hover:text-red-700' : '' }}">
                    {{ $order->status->value === 'rejected' ? 'Upload Ulang Bukti' : 'Bayar Sekarang' }}
                </a>
                @elseif($order->status->value === 'payment_uploaded')
                <a href="{{ route('orders.payment', $order) }}"
                   class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                    Lihat Status
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
    @endif

</div>
@endsection
