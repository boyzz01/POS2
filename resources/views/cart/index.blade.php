@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

    @if(empty($items))
    {{-- Empty state --}}
    <div class="text-center py-24 bg-white rounded-2xl shadow-sm border border-gray-100">
        <svg class="w-24 h-24 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <p class="text-xl font-semibold text-gray-400 mb-2">Keranjang kosong</p>
        <p class="text-gray-400 text-sm mb-6">Tambahkan produk dari katalog kami</p>
        <a href="{{ route('catalog.index') }}"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2.5 rounded-lg transition-colors">
            Lihat Katalog
        </a>
    </div>

    @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Cart items --}}
        <div class="lg:col-span-2 space-y-4">
            @foreach($items as $productId => $item)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex gap-4">
                    {{-- Image --}}
                    <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                        @if($item['foto'])
                        <img src="{{ asset('storage/' . $item['foto']) }}"
                             alt="{{ $item['name'] }}"
                             class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full bg-green-50 flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate">{{ $item['name'] }}</h3>
                        <p class="text-sm text-gray-500">{{ $item['size'] }}</p>
                        <p class="text-green-700 font-semibold mt-1">
                            Rp {{ number_format($item['price'], 0, ',', '.') }} / karton
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col items-end gap-3">
                        {{-- Remove --}}
                        <form method="POST" action="{{ route('cart.remove', $productId) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>

                        {{-- Quantity --}}
                        <form method="POST" action="{{ route('cart.update', $productId) }}"
                              x-data="{ qty: {{ $item['quantity'] }} }"
                              @change.debounce.500ms="$el.submit()">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                <button type="button" @click="qty = Math.max(1, qty - 1); $el.closest('form').submit()"
                                        class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-50">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <input type="number" name="quantity" x-model="qty"
                                       min="1" max="999"
                                       class="w-12 text-center text-sm font-semibold h-8 border-x border-gray-200 focus:outline-none">
                                <button type="button" @click="qty++; $el.closest('form').submit()"
                                        class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-50">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        </form>

                        <p class="text-sm font-bold text-gray-900">
                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Clear cart --}}
            <div class="text-right">
                <form method="POST" action="{{ route('cart.clear') }}"
                      onsubmit="return confirm('Kosongkan semua keranjang?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-500 hover:text-red-700">
                        Kosongkan Keranjang
                    </button>
                </form>
            </div>
        </div>

        {{-- Order summary --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <h2 class="font-bold text-gray-900 text-lg mb-5">Ringkasan Pesanan</h2>

                <div class="space-y-3 text-sm mb-5">
                    @foreach($items as $item)
                    <div class="flex justify-between text-gray-600">
                        <span class="truncate pr-2">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                        <span class="shrink-0 font-medium">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-100 pt-4 mb-6">
                    <div class="flex justify-between font-bold text-gray-900 text-lg">
                        <span>Subtotal</span>
                        <span class="text-green-700">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">*Ongkir ditentukan saat checkout</p>
                </div>

                @auth('customer')
                <a href="{{ route('checkout.index') }}"
                   class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-md">
                    Lanjut ke Checkout
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @else
                <a href="{{ route('login') }}?redirect={{ urlencode(route('checkout.index')) }}"
                   class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-md">
                    Login untuk Checkout
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <p class="text-xs text-gray-400 text-center mt-2">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-green-600 hover:underline">Daftar gratis</a>
                </p>
                @endauth
            </div>
        </div>

    </div>
    @endif

</div>
@endsection
