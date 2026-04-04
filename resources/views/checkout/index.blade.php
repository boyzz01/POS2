@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        @foreach($errors->all() as $error)
        <p class="text-sm text-red-700">• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf

        {{-- Checkout form --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Data pemesan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-900 text-lg mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 bg-green-100 text-green-700 rounded-full flex items-center justify-center text-sm font-bold">1</span>
                    Data Pemesan
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Pemesan / Institusi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="customer_name"
                               value="{{ old('customer_name', $customer->name) }}"
                               required
                               placeholder="Nama lengkap atau nama institusi"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('customer_name') border-red-400 @enderror">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nomor WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="customer_phone"
                               value="{{ old('customer_phone', $customer->phone) }}"
                               required
                               placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('customer_phone') border-red-400 @enderror">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Ongkir (opsional)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                            <input type="number" name="shipping_cost"
                                   value="{{ old('shipping_cost', 0) }}"
                                   min="0"
                                   placeholder="0"
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Kosongkan atau isi 0 jika tidak ada ongkir</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Pengiriman <span class="text-red-500">*</span>
                        </label>
                        <textarea name="customer_address" rows="3" required
                                  placeholder="Alamat lengkap termasuk kota dan kode pos"
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none @error('customer_address') border-red-400 @enderror">{{ old('customer_address') }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Catatan Pesanan (opsional)
                        </label>
                        <textarea name="notes" rows="2"
                                  placeholder="Instruksi khusus, waktu pengiriman yang diinginkan, dll."
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Info pembayaran --}}
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
                <h3 class="font-semibold text-amber-900 flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Info Pembayaran
                </h3>
                <p class="text-sm text-amber-800 leading-relaxed">
                    Setelah klik <strong>Buat Pesanan</strong>, Anda akan diarahkan ke halaman pembayaran berisi nomor rekening dan total yang harus ditransfer. Upload bukti transfer untuk memproses pesanan Anda.
                </p>
            </div>
        </div>

        {{-- Order summary --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <h2 class="font-bold text-gray-900 text-lg mb-5 flex items-center gap-2">
                    <span class="w-7 h-7 bg-green-100 text-green-700 rounded-full flex items-center justify-center text-sm font-bold">2</span>
                    Ringkasan
                </h2>

                <div class="space-y-3 text-sm mb-5 max-h-64 overflow-y-auto pr-1">
                    @foreach($items as $item)
                    <div class="flex justify-between items-start gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 truncate">{{ $item['name'] }}</p>
                            <p class="text-gray-400 text-xs">{{ $item['size'] }} × {{ $item['quantity'] }} karton</p>
                        </div>
                        <span class="shrink-0 font-medium text-gray-700">
                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                        </span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-100 pt-4 space-y-2 text-sm mb-6"
                     x-data="{ shipping: 0 }"
                     x-init="document.querySelector('[name=shipping_cost]').addEventListener('input', e => shipping = parseInt(e.target.value) || 0)">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Ongkir</span>
                        <span x-text="'Rp ' + shipping.toLocaleString('id-ID')">Rp 0</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-900 text-base pt-2 border-t border-gray-100">
                        <span>Total</span>
                        <span class="text-green-700"
                              x-text="'Rp ' + ({{ $subtotal }} + shipping).toLocaleString('id-ID')">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-md">
                    Buat Pesanan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
