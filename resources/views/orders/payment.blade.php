@extends('layouts.app')

@section('title', 'Pembayaran — ' . $order->invoice_number)

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Status indicator --}}
    <div class="text-center mb-8">
        @if($order->status->value === 'paid')
        <div class="w-16 h-16 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-8 h-8 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Pembayaran Dikonfirmasi!</h1>
        <p class="text-gray-500 mt-1">Pesanan Anda sedang diproses</p>
        @elseif($order->status->value === 'payment_uploaded')
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Menunggu Verifikasi</h1>
        <p class="text-gray-500 mt-1">Bukti transfer sedang diverifikasi oleh admin</p>
        @else
        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Lakukan Pembayaran</h1>
        <p class="text-gray-500 mt-1">Segera transfer sesuai nominal di bawah ini</p>
        @endif
    </div>

    {{-- Rejection notice --}}
    @if($order->status->value === 'rejected' && $order->rejection_reason)
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="font-semibold text-red-800 mb-1">Pembayaran Ditolak</p>
                <p class="text-sm text-red-700">{{ $order->rejection_reason }}</p>
                <p class="text-sm text-red-600 mt-2">Silakan upload ulang bukti transfer yang benar di bawah ini.</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Invoice --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="bg-sky-600 px-6 py-4 flex items-center justify-between">
            <div>
                <p class="text-sky-100 text-xs font-medium">INVOICE</p>
                <p class="text-white font-bold text-xl">{{ $order->invoice_number }}</p>
            </div>
            <span class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $order->status->badgeClass() }}">
                {{ $order->status->label() }}
            </span>
        </div>

        <div class="p-6">
            {{-- Items --}}
            <div class="space-y-2 mb-4">
                @foreach($order->items as $item)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ $item->product_name }} {{ $item->product_size }} × {{ $item->quantity }}</span>
                    <span class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
            <div class="border-t border-gray-100 pt-3 space-y-2 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold text-gray-900 text-base pt-2 border-t border-gray-100">
                    <span>TOTAL TRANSFER</span>
                    <span class="text-sky-700 text-lg">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Bank transfer info --}}
    @if($order->canUploadProof())
    <div class="bg-sky-50 border border-sky-200 rounded-2xl p-6 mb-6">
        <h3 class="font-bold text-sky-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Transfer ke Rekening Berikut
        </h3>

        <div class="space-y-3">
            <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3 border border-sky-100">
                <div>
                    <p class="text-xs text-gray-400">Bank</p>
                    <p class="font-bold text-gray-900 text-lg">{{ $order->bank_name }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3 border border-sky-100"
                 x-data="{ copied: false }">
                <div>
                    <p class="text-xs text-gray-400">Nomor Rekening</p>
                    <p class="font-bold text-gray-900 text-xl tracking-widest">{{ $order->bank_account }}</p>
                </div>
                <button type="button"
                        @click="navigator.clipboard.writeText('{{ $order->bank_account }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="text-xs font-medium text-sky-600 hover:text-sky-800 bg-sky-50 hover:bg-sky-100 px-3 py-1.5 rounded-lg transition-colors">
                    <span x-show="!copied">Salin</span>
                    <span x-show="copied" class="text-emerald-600">✓ Disalin!</span>
                </button>
            </div>

            <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3 border border-sky-100">
                <div>
                    <p class="text-xs text-gray-400">Atas Nama</p>
                    <p class="font-bold text-gray-900">{{ $order->bank_holder }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between bg-amber-50 rounded-xl px-4 py-3 border border-amber-200"
                 x-data="{ copied: false }">
                <div>
                    <p class="text-xs text-amber-600">Jumlah Transfer (tepat)</p>
                    <p class="font-extrabold text-amber-900 text-2xl">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </p>
                </div>
                <button type="button"
                        @click="navigator.clipboard.writeText('{{ $order->total }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="text-xs font-medium text-amber-700 hover:text-amber-900 bg-amber-100 hover:bg-amber-200 px-3 py-1.5 rounded-lg transition-colors">
                    <span x-show="!copied">Salin</span>
                    <span x-show="copied" class="text-emerald-600">✓ Disalin!</span>
                </button>
            </div>
        </div>

        <p class="text-xs text-gray-500 mt-4 bg-white rounded-lg p-3 border border-gray-100">
            ⚠️ Transfer tepat sesuai nominal di atas (termasuk angka genap). Perbedaan nominal akan menghambat verifikasi pembayaran.
        </p>
    </div>
    @endif

    {{-- Upload proof --}}
    @if($order->canUploadProof())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-1">Upload Bukti Transfer</h3>
        <p class="text-sm text-gray-500 mb-5">Format: JPG, PNG, atau PDF. Maks 5 MB.</p>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
            @foreach($errors->all() as $error)
            <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST"
              action="{{ route('orders.payment.upload', $order) }}"
              enctype="multipart/form-data"
              x-data="{ file: null, dragging: false }">
            @csrf

            <div
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="dragging = false; file = $event.dataTransfer.files[0]; $refs.fileInput.files = $event.dataTransfer.files"
                :class="dragging ? 'border-sky-400 bg-sky-50' : 'border-gray-200 hover:border-sky-300'"
                class="border-2 border-dashed rounded-xl p-8 text-center cursor-pointer transition-colors"
                @click="$refs.fileInput.click()">

                <input type="file" name="proof" accept=".jpg,.jpeg,.png,.pdf"
                       class="hidden" x-ref="fileInput"
                       @change="file = $event.target.files[0]">

                <template x-if="!file">
                    <div>
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-700">Klik atau seret file ke sini</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, PDF hingga 5 MB</p>
                    </div>
                </template>

                <template x-if="file">
                    <div class="flex items-center justify-center gap-3">
                        <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-gray-900" x-text="file.name"></p>
                            <p class="text-xs text-gray-500" x-text="(file.size / 1024).toFixed(1) + ' KB'"></p>
                        </div>
                        <button type="button" @click.stop="file = null; $refs.fileInput.value = ''"
                                class="ml-2 text-red-400 hover:text-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>

            <button type="submit" :disabled="!file"
                    :class="file ? 'bg-sky-600 hover:bg-sky-700 cursor-pointer' : 'bg-gray-300 cursor-not-allowed'"
                    class="w-full mt-4 text-white font-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Kirim Bukti Transfer
            </button>
        </form>
    </div>
    @endif

    {{-- Already uploaded proof --}}
    @if($order->latestPaymentProof && !$order->canUploadProof())
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 mb-6">
        <p class="font-semibold text-blue-900 mb-1 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
            </svg>
            Bukti Transfer Terkirim
        </p>
        <p class="text-sm text-blue-700 mb-3">
            {{ $order->latestPaymentProof->original_filename }}
            ({{ $order->latestPaymentProof->fileSizeForHumans() }})
        </p>
        <a href="{{ route('orders.payment.proof', [$order, $order->latestPaymentProof]) }}"
           target="_blank"
           class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-600 hover:text-blue-800 bg-white border border-blue-200 px-3 py-1.5 rounded-lg transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Lihat Bukti
        </a>
    </div>
    @endif

    {{-- Actions --}}
    <div class="flex gap-3">
        <a href="{{ route('orders.show', $order) }}"
           class="flex-1 text-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
            Detail Pesanan
        </a>
        <a href="{{ route('orders.index') }}"
           class="flex-1 text-center px-4 py-2.5 bg-white border border-gray-200 hover:border-sky-300 hover:text-sky-700 text-gray-700 text-sm font-medium rounded-xl transition-colors">
            Semua Pesanan
        </a>
    </div>

</div>
@endsection
