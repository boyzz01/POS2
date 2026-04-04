<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Supplier MBG') — Makan Bergizi Gratis</title>
    <meta name="description" content="@yield('meta_description', 'Supplier terpercaya kebutuhan Makan Bergizi Gratis (MBG) untuk institusi, sekolah, dan yayasan.')">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    @stack('head')
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

{{-- ===== NAVBAR ===== --}}
<nav class="bg-white shadow-sm sticky top-0 z-50" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-sky-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="font-bold text-sky-700 text-lg leading-tight">Supplier<br><span class="text-xs font-medium text-gray-500 leading-none">MBG</span></span>
            </a>

            {{-- Desktop nav --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}"
                   class="text-sm font-medium {{ request()->routeIs('home') ? 'text-sky-600' : 'text-gray-600 hover:text-sky-600' }} transition-colors">
                    Beranda
                </a>
                <a href="{{ route('catalog.index') }}"
                   class="text-sm font-medium {{ request()->routeIs('catalog.*') ? 'text-sky-600' : 'text-gray-600 hover:text-sky-600' }} transition-colors">
                    Katalog Produk
                </a>
                @auth('customer')
                <a href="{{ route('orders.index') }}"
                   class="text-sm font-medium {{ request()->routeIs('orders.*') ? 'text-sky-600' : 'text-gray-600 hover:text-sky-600' }} transition-colors">
                    Pesanan Saya
                </a>
                @endauth
            </div>

            {{-- Right actions --}}
            <div class="flex items-center gap-3">
                {{-- Cart --}}
                <a href="{{ route('cart.index') }}"
                   class="relative p-2 text-gray-600 hover:text-sky-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @php $cartCount = app(\App\Services\CartService::class)->count(); @endphp
                    @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-sky-600 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center">
                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                    </span>
                    @endif
                </a>

                @auth('customer')
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-sky-600 transition-colors">
                        <div class="w-8 h-8 bg-sky-100 rounded-full flex items-center justify-center text-sky-700 font-semibold text-xs">
                            {{ strtoupper(substr(auth('customer')->user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden sm:block max-w-[120px] truncate">{{ auth('customer')->user()->name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                         x-transition
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                        <a href="{{ route('orders.index') }}"
                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-sky-50 hover:text-sky-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Pesanan Saya
                        </a>
                        <hr class="my-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}"
                   class="hidden sm:inline-flex text-sm font-medium text-gray-600 hover:text-sky-600 transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Daftar
                </a>
                @endauth

                {{-- Mobile hamburger --}}
                <button @click="open = !open" class="md:hidden p-2 text-gray-600">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open" x-transition class="md:hidden pb-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-sky-600">Beranda</a>
            <a href="{{ route('catalog.index') }}" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-sky-600">Katalog Produk</a>
            @auth('customer')
            <a href="{{ route('orders.index') }}" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-sky-600">Pesanan Saya</a>
            @else
            <a href="{{ route('login') }}" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-sky-600">Masuk</a>
            <a href="{{ route('register') }}" class="block px-3 py-2 text-sm font-medium text-sky-600">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

{{-- Flash messages --}}
@if(session('success') || session('error'))
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4" x-data="{ show: true }" x-show="show">
    @if(session('success'))
    <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="text-sm">{{ session('success') }}</span>
        <button @click="show = false" class="ml-auto text-green-600 hover:text-green-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mt-2">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span class="text-sm">{{ session('error') }}</span>
        <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    @endif
</div>
@endif

{{-- Page content --}}
<main>
    @yield('content')
</main>

{{-- ===== FOOTER ===== --}}
<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-sky-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-white text-lg">Supplier MBG</span>
                </div>
                <p class="text-sm leading-relaxed text-gray-400">
                    Supplier terpercaya kebutuhan Makan Bergizi Gratis (MBG) untuk institusi, sekolah, dan yayasan di seluruh Indonesia.
                </p>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-4">Tautan Cepat</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="{{ route('catalog.index') }}" class="hover:text-white transition-colors">Katalog Produk</a></li>
                    @auth('customer')
                    <li><a href="{{ route('orders.index') }}" class="hover:text-white transition-colors">Pesanan Saya</a></li>
                    @else
                    <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Daftar Akun</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-4">Informasi Pembayaran</h4>
                <div class="text-sm space-y-1 text-gray-400">
                    <p>Bank: <span class="text-white font-medium">{{ config('payment.bank_name') }}</span></p>
                    <p>No. Rekening: <span class="text-white font-medium">{{ config('payment.bank_account') }}</span></p>
                    <p>Atas Nama: <span class="text-white font-medium">{{ config('payment.bank_holder') }}</span></p>
                </div>
                <p class="text-xs text-gray-500 mt-4">Pembayaran melalui transfer bank manual. Bukti transfer wajib diupload setelah melakukan pembayaran.</p>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-10 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3">
            <p class="text-xs text-gray-500">© {{ date('Y') }} Supplier MBG. Hak cipta dilindungi.</p>
            <p class="text-xs text-gray-500">Dibangun untuk mendukung program Makan Bergizi Gratis</p>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
