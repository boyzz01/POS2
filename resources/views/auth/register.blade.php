@extends('layouts.guest')

@section('title', 'Daftar Akun SPPG')

@section('content')
    <h1 class="text-2xl font-bold text-gray-900 text-center mb-1">Daftar Akun SPPG</h1>
    <p class="text-sm text-gray-500 text-center mb-7">Isi data SPPG Anda untuk mulai memesan kebutuhan MBG</p>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-5" role="alert" aria-live="polite">
            @foreach ($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        {{-- Nama SPPG --}}
        <div>
            <label for="nama_sppg" class="block text-sm font-medium text-gray-700 mb-1.5">
                Nama SPPG <span class="text-red-500">*</span>
            </label>
            <input type="text" id="nama_sppg" name="nama_sppg" value="{{ old('nama_sppg') }}" autocomplete="organization"
                required autofocus placeholder="Contoh: SPPG Harapan Bangsa"
                class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-shadow
                      {{ $errors->has('nama_sppg') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            @error('nama_sppg')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Alamat SPPG --}}
        <div>
            <label for="alamat_sppg" class="block text-sm font-medium text-gray-700 mb-1.5">
                Alamat SPPG <span class="text-red-500">*</span>
            </label>
            <textarea id="alamat_sppg" name="alamat_sppg" rows="3" required
                placeholder="Jl. Contoh No. 1, Kelurahan, Kecamatan, Kota/Kabupaten, Provinsi"
                class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-shadow resize-none
                         {{ $errors->has('alamat_sppg') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">{{ old('alamat_sppg') }}</textarea>
            @error('alamat_sppg')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- No WA Ka. Purchasing --}}
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">
                No. WA Ka. Purchasing <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-500 select-none">+62</span>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                    inputmode="tel" required placeholder="8xxxxxxxxxx"
                    class="w-full pl-14 pr-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-shadow
                          {{ $errors->has('phone') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            </div>
            <p class="text-xs text-gray-400 mt-1">Nomor WhatsApp yang aktif untuk konfirmasi pesanan</p>
            @error('phone')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Foto Dashboard SPPG --}}
        <div x-data="{
            showContoh: false,
            preview: null,
            fileName: null,
            handleFile(e) {
                const file = e.target.files[0];
                if (!file) return;
                this.fileName = file.name;
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = ev => this.preview = ev.target.result;
                    reader.readAsDataURL(file);
                } else {
                    this.preview = 'pdf';
                }
            }
        }">
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-sm font-medium text-gray-700">
                    Foto Dashboard SPPG <span class="text-red-500">*</span>
                </label>
                {{-- Tombol lihat contoh --}}
                <button type="button" @click="showContoh = !showContoh"
                    class="inline-flex items-center gap-1 text-xs font-medium text-sky-600 hover:text-sky-700 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span x-text="showContoh ? 'Sembunyikan contoh' : 'Lihat contoh foto'"></span>
                </button>
            </div>

            {{-- Panel contoh foto --}}
            <div x-show="showContoh" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-1"
                class="mb-3 rounded-xl overflow-hidden border border-sky-200 bg-sky-50">
                <div class="flex items-center gap-2 px-3 py-2 bg-sky-100 border-b border-sky-200">
                    <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-xs font-semibold text-sky-700">Contoh tampilan Dashboard SPPG</p>
                </div>
                <div class="p-3">
                    <img src="{{ asset('images/contoh-dashboard-sppg.jpeg') }}" alt="Contoh tampilan Dashboard SPPG"
                        class="w-full rounded-lg shadow-sm object-contain max-h-64 bg-white">
                    <p class="text-xs text-sky-600 mt-2 leading-relaxed">
                        Upload screenshot halaman <strong>SPPG Dashboard</strong> dari aplikasi SPPG Anda seperti contoh di
                        atas. Foto ini digunakan untuk verifikasi akun Anda.
                    </p>
                </div>
            </div>

            {{-- File upload area --}}
            <label for="foto_dashboard"
                class="relative flex flex-col items-center justify-center w-full border-2 border-dashed rounded-xl cursor-pointer transition-colors
                      {{ $errors->has('foto_dashboard') ? 'border-red-400 bg-red-50' : 'border-gray-300 bg-gray-50 hover:border-sky-400 hover:bg-sky-50' }}"
                :class="fileName ? 'py-4 px-4' : 'py-8 px-4'">

                {{-- Preview gambar --}}
                <template x-if="preview && preview !== 'pdf'">
                    <div class="w-full mb-3">
                        <img :src="preview" class="max-h-48 mx-auto rounded-lg object-contain shadow-sm">
                    </div>
                </template>

                {{-- Preview PDF --}}
                <template x-if="preview === 'pdf'">
                    <div class="flex items-center gap-3 mb-3 bg-white border border-gray-200 rounded-lg px-4 py-3 w-full">
                        <svg class="w-8 h-8 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm text-gray-700 font-medium truncate" x-text="fileName"></span>
                    </div>
                </template>

                {{-- Default state --}}
                <template x-if="!preview">
                    <div class="flex flex-col items-center text-center">
                        <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm font-medium text-gray-600">Klik untuk upload foto</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, atau PDF — maks. 5 MB</p>
                    </div>
                </template>

                <template x-if="fileName">
                    <p class="text-xs text-sky-600 mt-1 font-medium">Klik untuk ganti file</p>
                </template>

                <input type="file" id="foto_dashboard" name="foto_dashboard" accept=".jpg,.jpeg,.png,.pdf" required
                    @change="handleFile($event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
            </label>

            <p class="text-xs text-gray-400 mt-1.5">Screenshot halaman Dashboard SPPG Anda sebagai bukti verifikasi</p>
            @error('foto_dashboard')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Divider --}}
        <div class="border-t border-gray-100 pt-2">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Informasi Akun</p>
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                Email <span class="text-red-500">*</span>
            </label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email"
                inputmode="email" required placeholder="email@sppg.com"
                class="w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-shadow
                      {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            @error('email')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                Password <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" id="password" name="password" autocomplete="new-password"
                    required placeholder="Minimal 8 karakter"
                    class="w-full px-4 py-2.5 pr-11 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-shadow">
                <button type="button" @click="show = !show"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                    :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Konfirmasi Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">
                Konfirmasi Password <span class="text-red-500">*</span>
            </label>
            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-shadow">
        </div>

        <button type="submit"
            class="w-full bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm shadow-sm mt-2">
            Daftar Sekarang
        </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-sky-600 hover:text-sky-700 font-semibold">Masuk</a>
    </p>
@endsection
