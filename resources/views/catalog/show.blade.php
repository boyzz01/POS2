@extends('layouts.app')

@section('title', $product->merk . ' ' . $product->ukuran)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-sky-600 transition-colors">Beranda</a>
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('catalog.index') }}" class="hover:text-sky-600 transition-colors">Katalog</a>
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-900 font-medium truncate">{{ $product->merk }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

        {{-- Product Image --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($product->foto)
            <img src="{{ asset('storage/' . $product->foto) }}"
                 alt="{{ $product->merk }}"
                 class="w-full aspect-square object-cover">
            @else
            <div class="aspect-square bg-sky-50 flex items-center justify-center">
                <svg class="w-32 h-32 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            @endif
        </div>

        {{-- Product Details --}}
        <div class="flex flex-col">
            @if($product->kategori)
            <span class="text-xs font-semibold text-sky-600 uppercase tracking-wide mb-2">{{ $product->kategori }}</span>
            @endif

            <h1 class="text-3xl font-bold text-gray-900 mb-1">{{ $product->merk }}</h1>
            <p class="text-gray-500 text-lg mb-6">{{ $product->ukuran }}</p>

            {{-- Price box --}}
            <div class="bg-sky-50 border border-sky-100 rounded-xl p-5 mb-6">
                <p class="text-3xl font-extrabold text-sky-700 tabular-nums">
                    Rp {{ number_format($product->harga_karton, 0, ',', '.') }}
                    <span class="text-base font-normal text-gray-500">/ karton</span>
                </p>
                <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-600">
                    <span>{{ $product->pcs_per_karton }} pcs / karton</span>
                    <span>≈ Rp {{ number_format($product->harga_satuan, 0, ',', '.') }} / pcs</span>
                </div>
            </div>

            {{-- Stock --}}
            <div class="flex items-center gap-3 mb-6">
                @if($product->stok_karton > 0)
                <span class="inline-flex items-center gap-1.5 bg-sky-100 text-sky-700 text-sm font-medium px-3 py-1.5 rounded-full">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Tersedia: {{ $product->stok_karton }} karton
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 bg-red-100 text-red-600 text-sm font-medium px-3 py-1.5 rounded-full">
                    Stok Habis
                </span>
                @endif

                @if($product->supplier)
                <span class="text-sm text-gray-500">Supplier: {{ $product->supplier }}</span>
                @endif
            </div>

            {{-- Description --}}
            @if($product->keterangan)
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Keterangan</h3>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $product->keterangan }}</p>
            </div>
            @endif

            {{-- Add to cart form --}}
            @if($product->stok_karton > 0)
            <form method="POST" action="{{ route('cart.add', $product) }}"
                  x-data="{ qty: 1 }" class="mt-auto">
                @csrf
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah (karton)</label>
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                        <button type="button" @click="qty = Math.max(1, qty - 1)"
                                class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                        </button>
                        <input type="number" name="quantity" x-model="qty"
                               min="1" max="{{ $product->stok_karton }}"
                               class="w-16 text-center border-x border-gray-200 h-10 text-sm font-semibold focus:outline-none tabular-nums">
                        <button type="button" @click="qty = Math.min({{ $product->stok_karton }}, qty + 1)"
                                class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-500">
                        = <span class="font-semibold text-gray-900 tabular-nums"
                                x-text="'Rp ' + ({{ $product->harga_karton }} * qty).toLocaleString('id-ID')"></span>
                    </p>
                </div>

                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white font-bold py-3.5 rounded-xl transition-colors shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Tambah ke Keranjang
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Related products --}}
    @if($related->count() > 0)
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Produk Sejenis</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
            @foreach($related as $item)
            <x-product-card :product="$item"/>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
