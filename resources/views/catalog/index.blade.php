@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="mb-8">
        <p class="text-xs font-semibold text-sky-600 uppercase tracking-widest mb-1">Semua Produk</p>
        <h1 class="text-3xl font-extrabold text-gray-900">Katalog Produk</h1>
        <p class="text-gray-500 mt-1">Temukan produk kebutuhan MBG berkualitas untuk program Anda</p>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 bg-gray-100 p-1 rounded-xl mb-6 w-fit">
        <a href="{{ route('catalog.index', array_merge(request()->except(['tab', 'page']), ['tab' => 'ready'])) }}"
           class="px-5 py-2 text-sm font-semibold rounded-lg transition-colors
                  {{ $tab === 'ready' ? 'bg-white text-sky-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            Ready Stock
            <span class="ml-1.5 text-xs font-bold {{ $tab === 'ready' ? 'text-sky-600' : 'text-gray-400' }}">{{ $countReady }}</span>
        </a>
        <a href="{{ route('catalog.index', array_merge(request()->except(['tab', 'page']), ['tab' => 'po'])) }}"
           class="px-5 py-2 text-sm font-semibold rounded-lg transition-colors
                  {{ $tab === 'po' ? 'bg-white text-sky-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            Pre-Order (PO)
            <span class="ml-1.5 text-xs font-bold {{ $tab === 'po' ? 'text-sky-600' : 'text-gray-400' }}">{{ $countPo }}</span>
        </a>
    </div>

    {{-- Filter & Search --}}
    <form method="GET" action="{{ route('catalog.index') }}"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-8">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-xs font-medium text-gray-500 mb-1.5">Cari Produk</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           placeholder="Nama produk, ukuran, kategori..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-shadow">
                </div>
            </div>

            @if($categories->count() > 0)
            <div class="sm:w-52">
                <label for="kategori" class="block text-xs font-medium text-gray-500 mb-1.5">Kategori</label>
                <select id="kategori" name="kategori"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent bg-white transition-shadow">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('kategori') === $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="sm:self-end flex gap-2">
                <button type="submit"
                        class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Cari
                </button>
                @if(request('search') || request('kategori'))
                <a href="{{ route('catalog.index', ['tab' => $tab]) }}"
                   class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    Reset
                </a>
                @endif
            </div>
        </div>
    </form>

    {{-- Active filters --}}
    @if(request('search') || request('kategori'))
    <div class="flex flex-wrap items-center gap-2 mb-5">
        <span class="text-xs text-gray-500">Filter aktif:</span>
        @if(request('search'))
        <span class="inline-flex items-center gap-1.5 bg-sky-100 text-sky-700 text-xs font-medium px-3 py-1 rounded-full">
            Cari: "{{ request('search') }}"
            <a href="{{ route('catalog.index', array_filter(['kategori' => request('kategori')])) }}"
               class="hover:text-sky-900">&times;</a>
        </span>
        @endif
        @if(request('kategori'))
        <span class="inline-flex items-center gap-1.5 bg-sky-100 text-sky-700 text-xs font-medium px-3 py-1 rounded-full">
            Kategori: {{ request('kategori') }}
            <a href="{{ route('catalog.index', array_filter(['search' => request('search')])) }}"
               class="hover:text-sky-900">&times;</a>
        </span>
        @endif
    </div>
    @endif

    {{-- Results info --}}
    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-gray-500">
            Menampilkan <span class="font-semibold text-gray-900">{{ $products->total() }}</span> produk
        </p>
    </div>

    {{-- Product grid --}}
    @if($products->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
        @foreach($products as $product)
        <x-product-card :product="$product"/>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-10">
        {{ $products->links() }}
    </div>

    @else
    <div class="text-center py-24">
        <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-xl font-semibold text-gray-400">Produk tidak ditemukan</p>
        <p class="text-gray-400 mt-2 text-sm">Coba kata kunci lain atau reset filter</p>
        <a href="{{ route('catalog.index') }}"
           class="inline-block mt-5 px-5 py-2.5 bg-sky-600 text-white text-sm font-medium rounded-lg hover:bg-sky-700 transition-colors">
            Lihat Semua Produk
        </a>
    </div>
    @endif

</div>
@endsection
