@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<h1 class="text-2xl font-bold text-gray-900 text-center mb-1">Selamat Datang</h1>
<p class="text-sm text-gray-500 text-center mb-7">Masuk ke akun Supplier MBG Anda</p>

@if($errors->any())
<div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-5" role="alert">
    @foreach($errors->all() as $error)
    <p class="text-sm text-red-700">{{ $error }}</p>
    @endforeach
</div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"
               autocomplete="email" inputmode="email" required autofocus
               class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-shadow
                      {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
    </div>

    <div x-data="{ show: false }">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
        <div class="relative">
            <input :type="show ? 'text' : 'password'" id="password" name="password"
                   autocomplete="current-password" required
                   class="w-full px-4 py-2.5 pr-11 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-shadow">
            <button type="button" @click="show = !show"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                    :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'">
                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="flex items-center">
        <input type="checkbox" id="remember" name="remember"
               class="w-4 h-4 text-sky-600 border-gray-300 rounded focus:ring-sky-500">
        <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
    </div>

    <button type="submit"
            class="w-full bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm shadow-sm">
        Masuk
    </button>
</form>

<p class="text-center text-sm text-gray-600 mt-6">
    Belum punya akun?
    <a href="{{ route('register') }}" class="text-sky-600 hover:text-sky-700 font-semibold">Daftar sekarang</a>
</p>
@endsection
