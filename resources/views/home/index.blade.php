@extends('layouts.app')

@section('title', 'Supplier Resmi Kebutuhan MBG Terpercaya')
@section('meta_description', 'Supplier resmi pengadaan kebutuhan Makan Bergizi Gratis — produk terstandar, harga transparan, pengiriman tepat waktu untuk institusi, sekolah, dan yayasan.')

@section('content')

{{-- ===== HERO ===== --}}
<section class="relative bg-gradient-to-br from-sky-800 via-sky-700 to-sky-600 text-white overflow-hidden">

    {{-- Dot grid overlay --}}
    <div class="absolute inset-0 bg-dot-grid opacity-[0.12] pointer-events-none"></div>

    {{-- Decorative shapes --}}
    <div class="absolute -top-32 -right-32 w-[480px] h-[480px] rounded-full bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
    <div class="absolute bottom-0 -left-20 w-80 h-80 rounded-full bg-white/5 pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            {{-- Left: Headline + CTA + Stats --}}
            <div>
                {{-- Official badge --}}
                <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/25 text-white text-xs font-semibold px-3.5 py-1.5 rounded-full mb-6">
                    <svg class="w-3.5 h-3.5 text-yellow-300 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Mitra Resmi Program Makan Bergizi Gratis
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-[3.25rem] xl:text-6xl font-extrabold leading-[1.1] tracking-tight mb-5">
                    Supplier Resmi<br>
                    <span class="text-cyan-300">Pengadaan MBG</span><br>
                    Terpercaya
                </h1>

                <p class="text-sky-100 text-lg leading-relaxed mb-8 max-w-lg">
                    Menyediakan produk makanan bergizi berkualitas tinggi untuk institusi, sekolah, yayasan, dan pesantren peserta program Makan Bergizi Gratis. Pesan online, bayar transfer, konfirmasi dalam 1×24 jam.
                </p>

                <div class="flex flex-wrap gap-3 mb-10">
                    <a href="{{ route('catalog.index') }}"
                       class="inline-flex items-center gap-2 bg-white text-sky-700 font-bold px-5 py-3 rounded-xl hover:bg-sky-50 transition-all duration-200 shadow-lg text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Lihat Katalog Produk
                    </a>
                    @guest('customer')
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-300 text-yellow-900 font-bold px-5 py-3 rounded-xl transition-all duration-200 shadow-lg text-sm">
                        Daftar Gratis Sekarang
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    @endguest
                </div>

                {{-- Stats --}}
                <div class="flex items-center gap-5 sm:gap-8 flex-wrap">
                    <div>
                        <p class="text-2xl sm:text-3xl font-extrabold tabular-nums">{{ \App\Models\Product::active()->count() }}+</p>
                        <p class="text-xs text-sky-200 mt-0.5">Produk Tersedia</p>
                    </div>
                    <div class="w-px h-10 bg-white/20 hidden sm:block"></div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-extrabold">100%</p>
                        <p class="text-xs text-sky-200 mt-0.5">Produk Terstandar</p>
                    </div>
                    <div class="w-px h-10 bg-white/20 hidden sm:block"></div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-extrabold">1×24</p>
                        <p class="text-xs text-sky-200 mt-0.5">Jam Konfirmasi</p>
                    </div>
                </div>
            </div>

            {{-- Right: Trust info card --}}
            <div class="hidden lg:block">
                <div class="relative">
                    <div class="absolute inset-0 bg-white/5 rounded-3xl rotate-2 scale-105"></div>
                    <div class="relative bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-7 space-y-4">

                        {{-- Kualitas Terjamin --}}
                        <div class="flex items-start gap-4 p-4 bg-white/10 rounded-2xl">
                            <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-white text-sm">Kualitas Terjamin</p>
                                <p class="text-sky-200 text-xs mt-0.5 leading-relaxed">Setiap produk telah melalui seleksi ketat dan memenuhi standar gizi program MBG dari Badan Gizi Nasional.</p>
                            </div>
                        </div>

                        {{-- Antar ke Lokasi --}}
                        <div class="flex items-start gap-4 p-4 bg-white/10 rounded-2xl">
                            <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-white text-sm">Antar Sampai Tempat</p>
                                <p class="text-sky-200 text-xs mt-0.5 leading-relaxed">Pengiriman langsung ke lokasi SPPG Anda. Tidak perlu repot mengambil — kami yang datang.</p>
                            </div>
                        </div>

                        {{-- Category chips --}}
                        @if($categories->count() > 0)
                        <div class="pt-1">
                            <p class="text-xs font-semibold text-sky-200 uppercase tracking-wider mb-3">Kategori Produk</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($categories->take(6) as $cat)
                                <a href="{{ route('catalog.index', ['kategori' => $cat]) }}"
                                   class="text-xs bg-white/15 hover:bg-white/25 border border-white/20 text-white px-3 py-1.5 rounded-full transition-colors font-medium">
                                    {{ $cat }}
                                </a>
                                @endforeach
                                @if($categories->count() > 6)
                                <a href="{{ route('catalog.index') }}"
                                   class="text-xs bg-white/10 border border-white/15 text-sky-200 hover:bg-white/20 transition-colors px-3 py-1.5 rounded-full font-medium">
                                    +{{ $categories->count() - 6 }} lainnya
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Quick CTA --}}
                        <a href="{{ route('catalog.index') }}"
                           class="flex items-center justify-between bg-white/15 hover:bg-white/25 border border-white/20 rounded-xl px-4 py-3 transition-colors group">
                            <span class="text-sm font-semibold text-white">Mulai Pesan Sekarang</span>
                            <svg class="w-4 h-4 text-sky-200 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===== LAYANAN UNTUK (TARGET AUDIENCE) ===== --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <p class="text-xs font-semibold text-sky-600 uppercase tracking-widest mb-2">Layanan Kami</p>
            <h2 class="text-3xl font-extrabold text-gray-900">Siapa yang Kami Layani?</h2>
            <p class="text-gray-500 mt-2 max-w-xl mx-auto">Platform pengadaan kebutuhan MBG yang dirancang khusus untuk berbagai jenis institusi penerima program.</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">

            <div class="group p-6 rounded-2xl bg-sky-50 hover:bg-sky-100 border border-sky-100 hover:border-sky-200 transition-all duration-200 text-center">
                <div class="w-14 h-14 bg-sky-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-1.5 text-sm sm:text-base">Sekolah & Madrasah</h3>
                <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">SD, SMP, SMA, MI, MTs, MA peserta program MBG</p>
            </div>

            <div class="group p-6 rounded-2xl bg-sky-50 hover:bg-sky-100 border border-sky-100 hover:border-sky-200 transition-all duration-200 text-center">
                <div class="w-14 h-14 bg-sky-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-1.5 text-sm sm:text-base">Yayasan Sosial</h3>
                <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">Yayasan pendidikan dan sosial mitra program gizi</p>
            </div>

            <div class="group p-6 rounded-2xl bg-sky-50 hover:bg-sky-100 border border-sky-100 hover:border-sky-200 transition-all duration-200 text-center">
                <div class="w-14 h-14 bg-sky-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-1.5 text-sm sm:text-base">Instansi Pemerintah</h3>
                <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">Dinas, UPTD, dan lembaga pemerintah pengampu MBG</p>
            </div>

            <div class="group p-6 rounded-2xl bg-sky-50 hover:bg-sky-100 border border-sky-100 hover:border-sky-200 transition-all duration-200 text-center">
                <div class="w-14 h-14 bg-sky-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-1.5 text-sm sm:text-base">Pondok Pesantren</h3>
                <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">Pesantren dan lembaga pendidikan Islam peserta MBG</p>
            </div>

        </div>
    </div>
</section>

{{-- ===== PRODUK UNGGULAN ===== --}}
@if($featuredProducts->count() > 0)
<section class="py-14 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-end justify-between mb-7">
            <div>
                <p class="text-xs font-semibold text-sky-600 uppercase tracking-widest mb-1">Pilihan Terbaik</p>
                <h2 class="text-2xl font-extrabold text-gray-900">Produk Unggulan</h2>
                <p class="text-gray-500 mt-0.5 text-sm">Produk paling diminati untuk kebutuhan program MBG</p>
            </div>
            <a href="{{ route('catalog.index') }}"
               class="hidden sm:inline-flex items-center gap-1 text-sky-600 hover:text-sky-700 font-semibold text-sm transition-colors">
                Lihat Selengkapnya
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
            @foreach($featuredProducts->take(4) as $product)
            <x-product-card-sm :product="$product"/>
            @endforeach
        </div>

        <div class="text-center mt-6 sm:hidden">
            <a href="{{ route('catalog.index') }}"
               class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors text-sm">
                Lihat Selengkapnya
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

    </div>
</section>
@endif

{{-- ===== CARA PESAN ===== --}}
<section class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-xs font-semibold text-sky-600 uppercase tracking-widest mb-2">Mudah & Transparan</p>
            <h2 class="text-3xl font-extrabold text-gray-900">Cara Pemesanan</h2>
            <p class="text-gray-500 mt-2">Lima langkah sederhana dari pemilihan produk hingga konfirmasi pesanan</p>
        </div>

        <div class="relative">
            {{-- Connecting line (desktop only) --}}
            <div class="hidden lg:block absolute top-8 left-[calc(10%+2rem)] right-[calc(10%+2rem)] h-0.5 bg-sky-100 z-0"></div>

            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-8 lg:gap-4 relative">

                @php
                $steps = [
                    ['num' => '01', 'title' => 'Daftar Akun', 'desc' => 'Buat akun gratis dengan email institusi Anda', 'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
                    ['num' => '02', 'title' => 'Pilih Produk', 'desc' => 'Browse katalog dan tambahkan produk ke keranjang', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['num' => '03', 'title' => 'Checkout', 'desc' => 'Isi alamat pengiriman dan konfirmasi pesanan', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                    ['num' => '04', 'title' => 'Transfer Bank', 'desc' => 'Transfer sesuai total dan upload bukti pembayaran', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                    ['num' => '05', 'title' => 'Konfirmasi', 'desc' => 'Admin verifikasi dan pesanan Anda segera diproses', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
                @endphp

                @foreach($steps as $step)
                <div class="flex flex-col items-center text-center relative z-10">
                    <div class="relative mb-4">
                        <div class="w-16 h-16 bg-sky-600 rounded-2xl flex items-center justify-center shadow-md shadow-sky-100">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $step['icon'] }}"/>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-sky-100 text-sky-700 rounded-full flex items-center justify-center text-xs font-extrabold border-2 border-white">
                            {{ $step['num'] }}
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1 text-sm sm:text-base">{{ $step['title'] }}</h3>
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</section>

{{-- ===== KEUNGGULAN ===== --}}
<section class="py-16 bg-sky-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-xs font-semibold text-sky-600 uppercase tracking-widest mb-2">Keunggulan Kami</p>
            <h2 class="text-3xl font-extrabold text-gray-900">Mengapa Pilih Kami?</h2>
            <p class="text-gray-500 mt-2">Komitmen kami terhadap kualitas, transparansi, dan efisiensi pengadaan</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">

            <div class="bg-white rounded-2xl p-8 shadow-sm border border-sky-100">
                <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 text-lg mb-2">Produk Terstandar</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Seluruh produk telah melalui seleksi kualitas ketat dan memenuhi standar gizi yang dipersyaratkan program Makan Bergizi Gratis.</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm border border-sky-100">
                <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 text-lg mb-2">Harga Transparan</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Harga grosir langsung dari distributor, ditampilkan secara transparan tanpa biaya tersembunyi. Efisiensi anggaran program Anda terjaga.</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm border border-sky-100">
                <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 text-lg mb-2">Proses 1×24 Jam</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Sistem pemesanan online yang mudah, verifikasi pembayaran cepat, dan konfirmasi pesanan dalam satu kali dua puluh empat jam kerja.</p>
            </div>

        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-20 bg-gradient-to-br from-sky-800 to-sky-600 relative overflow-hidden">
    <div class="absolute inset-0 bg-dot-grid opacity-10 pointer-events-none"></div>
    <div class="absolute -bottom-16 -right-16 w-64 h-64 bg-white/5 rounded-full pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-1 bg-gradient-to-r from-transparent via-white/20 to-transparent pointer-events-none"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-xs font-semibold text-sky-200 uppercase tracking-widest mb-3">Mulai Sekarang</p>
        <h2 class="text-3xl lg:text-4xl xl:text-5xl font-extrabold text-white leading-tight mb-4">
            Siap Memenuhi Kebutuhan<br class="hidden sm:block">
            <span class="text-cyan-300">Program MBG Anda?</span>
        </h2>
        <p class="text-sky-100 text-lg mb-8 max-w-xl mx-auto leading-relaxed">
            Daftar akun gratis hari ini dan dapatkan akses penuh ke katalog produk MBG berkualitas dengan harga kompetitif.
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('catalog.index') }}"
               class="inline-flex items-center gap-2 bg-white text-sky-700 font-bold px-7 py-3.5 rounded-xl hover:bg-sky-50 transition-colors shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Lihat Katalog Produk
            </a>
            @guest('customer')
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-300 text-yellow-900 font-bold px-7 py-3.5 rounded-xl transition-colors shadow-lg">
                Daftar Akun Gratis
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            @endguest
        </div>
    </div>
</section>

@endsection
