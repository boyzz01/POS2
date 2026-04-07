@extends('layouts.app')

@section('title', 'Pre-Order — ' . $product->merk)

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('catalog.index', ['tab' => 'po']) }}"
                class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Katalog PO
            </a>
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold bg-sky-100 text-sky-700 px-3 py-1 rounded-full">Pre-Order</span>
                <h1 class="text-2xl font-extrabold text-gray-900">Form Pesanan PO</h1>
            </div>
            <p class="text-gray-500 mt-1 text-sm">Produk ini stok kosong. Anda dapat memesan lebih dahulu dengan menyertakan
                bukti transfer</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-700">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('po.store', $product) }}" enctype="multipart/form-data"
            class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{
                qty: {{ old('quantity', 1) }},
                price: {{ $product->harga_karton }},
                proof: null,
                get subtotal() { return this.qty * this.price; },
                fmt(n) { return 'Rp ' + n.toLocaleString('id-ID'); },
            }">
            @csrf

            <div class="lg:col-span-2 space-y-6">

                {{-- Info Produk --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-900 text-lg mb-4 flex items-center gap-2">
                        <span
                            class="w-7 h-7 bg-sky-100 text-sky-700 rounded-full flex items-center justify-center text-sm font-bold">1</span>
                        Produk yang Dipesan
                    </h2>

                    <div class="flex gap-4">
                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                            @if ($product->foto)
                                <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->merk }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-sky-50 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-sky-200" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            @if ($product->kategori)
                                <span
                                    class="inline-block text-xs font-semibold bg-sky-100 text-sky-700 px-2 py-0.5 rounded-full mb-1">{{ $product->kategori?->nama }}</span>
                            @endif
                            <p class="font-bold text-gray-900">{{ $product->merk }}</p>
                            <p class="text-sm text-gray-500">{{ $product->ukuran }} · {{ $product->pcs_per_karton }}
                                pcs/karton</p>
                            <p class="text-sky-700 font-bold mt-1">Rp
                                {{ number_format($product->harga_karton, 0, ',', '.') }} <span
                                    class="text-xs font-normal text-gray-500">/ karton</span></p>
                        </div>
                    </div>

                    {{-- Qty input --}}
                    <div class="mt-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Pesanan <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center rounded-xl overflow-hidden border border-sky-200 bg-sky-50 w-36">
                                <button type="button" @click="qty = Math.max(1, qty - 1)"
                                    class="w-10 h-11 flex items-center justify-center text-sky-600 hover:bg-sky-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M20 12H4" />
                                    </svg>
                                </button>
                                <input type="number" name="quantity" x-model.number="qty" min="1" max="9999"
                                    class="flex-1 text-center text-base font-bold text-sky-700 bg-transparent border-none focus:outline-none tabular-nums w-0">
                                <button type="button" @click="qty++"
                                    class="w-10 h-11 flex items-center justify-center text-sky-600 hover:bg-sky-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Subtotal produk</p>
                                <p class="text-lg font-extrabold text-sky-700" x-text="fmt(subtotal)">
                                    Rp {{ number_format($product->harga_karton, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Data pemesan --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-900 text-lg mb-5 flex items-center gap-2">
                        <span
                            class="w-7 h-7 bg-sky-100 text-sky-700 rounded-full flex items-center justify-center text-sm font-bold">2</span>
                        Data Pemesan
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Pemesan / Institusi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', $customer->name) }}"
                                required placeholder="Nama lengkap atau nama institusi"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 @error('customer_name') border-red-400 @enderror">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor WhatsApp <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="customer_phone"
                                value="{{ old('customer_phone', $customer->phone) }}" required placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 @error('customer_phone') border-red-400 @enderror">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                            <textarea name="notes" rows="2" placeholder="Instruksi khusus, estimasi waktu yang diinginkan, dll."
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-none">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Upload bukti transfer --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-900 text-lg mb-1 flex items-center gap-2">
                        <span
                            class="w-7 h-7 bg-sky-100 text-sky-700 rounded-full flex items-center justify-center text-sm font-bold">3</span>
                        Upload Bukti Transfer
                    </h2>
                    <p class="text-sm text-gray-500 mb-5 ml-9">DP atau lunas. Format: JPG, PNG, PDF. Maks 5 MB.</p>

                    <div x-data="{ file: null, dragging: false }" @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="dragging = false; file = $event.dataTransfer.files[0]; $refs.proof.files = $event.dataTransfer.files">

                        <div :class="dragging ? 'border-sky-400 bg-sky-50' : (file ? 'border-green-400 bg-green-50' :
                            'border-gray-200 hover:border-sky-300')"
                            class="border-2 border-dashed rounded-xl p-8 text-center cursor-pointer transition-colors"
                            @click="$refs.proof.click()">

                            <input type="file" name="proof" accept=".jpg,.jpeg,.png,.pdf" class="hidden"
                                x-ref="proof" @change="file = $event.target.files[0]">

                            <template x-if="!file">
                                <div>
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-700">Klik atau seret file ke sini</p>
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, PDF hingga 5 MB</p>
                                </div>
                            </template>

                            <template x-if="file">
                                <div class="flex items-center justify-center gap-3">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <div class="text-left">
                                        <p class="text-sm font-semibold text-gray-900" x-text="file.name"></p>
                                        <p class="text-xs text-gray-500" x-text="(file.size / 1024).toFixed(1) + ' KB'">
                                        </p>
                                    </div>
                                    <button type="button" @click.stop="file = null; $refs.proof.value = ''"
                                        class="ml-2 text-red-400 hover:text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Sidebar ringkasan --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h2 class="font-bold text-gray-900 text-lg mb-5">Ringkasan PO</h2>

                    <div class="space-y-3 text-sm mb-5">
                        <div class="flex justify-between items-start gap-2">
                            <div>
                                <p class="font-medium text-gray-800">{{ $product->merk }}</p>
                                <p class="text-gray-400 text-xs">{{ $product->ukuran }} × <span x-text="qty">1</span>
                                    karton</p>
                            </div>
                            <span class="shrink-0 font-medium text-gray-700" x-text="fmt(subtotal)">
                                Rp {{ number_format($product->harga_karton, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 space-y-2 text-sm mb-6">
                        <div class="flex justify-between font-bold text-gray-900 text-base">
                            <span>Total</span>
                            <span class="text-sky-700" x-text="fmt(subtotal)">Rp
                                {{ number_format($product->harga_karton, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div
                        class="bg-sky-50 border border-sky-100 rounded-xl p-4 mb-5 text-xs text-sky-800 leading-relaxed">
                        <p class="font-semibold mb-1">Info Pembayaran Bank</p>
                        <p><span class="font-medium">{{ \App\Models\Setting::get('bank_name', config('payment.bank_name')) }}</span></p>
                        <p>{{ \App\Models\Setting::get('bank_account', config('payment.bank_account')) }}</p>
                        <p>a.n. {{ \App\Models\Setting::get('bank_holder', config('payment.bank_holder')) }}</p>
                    </div>

                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-sky-600 hover:bg-sky-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 11H4L5 9z" />
                        </svg>
                        Buat Pesanan PO
                    </button>
                    <p class="text-xs text-gray-400 text-center mt-3">Pesanan dikonfirmasi setelah admin memverifikasi
                        bukti transfer.</p>
                </div>
            </div>

        </form>
    </div>
@endsection
