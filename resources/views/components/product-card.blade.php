@props(['product'])

@php
    $cartQty = app(\App\Services\CartService::class)->getQuantity($product->id);
    $inStock = $product->stok_karton > 0;
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group flex flex-col"
    x-data="{
        qty: {{ $cartQty }},
        max: {{ $product->stok_karton }},
        syncing: false,
        capped: false,
        timer: null,
        token: document.querySelector('meta[name=csrf-token]').content,
    
        inc() {
            if (this.qty >= this.max) { this.capped = true; return; }
            this.qty++;
            this.capped = false;
            this.schedule();
        },
        dec() {
            if (this.qty > 0) { this.qty--;
                this.capped = false;
                this.schedule(); }
        },
    
        schedule() {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => this.sync(), 600);
        },
    
        async sync() {
            this.syncing = true;
            try {
                const res = await fetch('{{ route('cart.set', $product) }}', {
                    method: 'PATCH',
                    headers: { 'X-CSRF-TOKEN': this.token, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ quantity: this.qty }),
                });
                const data = await res.json();
                if (res.ok) {
                    if (data.quantity !== this.qty) {
                        this.capped = true;
                        this.qty = data.quantity;
                    }
                    document.querySelectorAll('[data-cart-count]').forEach(el => {
                        el.textContent = data.cartCount > 99 ? '99+' : data.cartCount;
                        el.style.display = data.cartCount > 0 ? '' : 'none';
                    });
                }
            } finally {
                this.syncing = false;
            }
        }
    }">

    {{-- Image --}}
    <a href="{{ route('catalog.show', $product) }}" class="block overflow-hidden rounded-t-xl">
        <div class="aspect-square bg-gray-100 flex items-center justify-center">
            @if ($product->foto)
                <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->merk }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    loading="lazy">
            @else
                <div class="w-full h-full bg-sky-50 flex items-center justify-center">
                    <svg class="w-16 h-16 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            @endif
        </div>
    </a>

    {{-- Info --}}
    <div class="p-4 flex flex-col flex-1">
        @if ($product->kategori)
            <span class="text-xs font-medium text-sky-600 uppercase tracking-wide mb-1">{{ $product->kategori }}</span>
        @endif

        <a href="{{ route('catalog.show', $product) }}"
            class="font-semibold text-gray-900 hover:text-sky-700 transition-colors line-clamp-2 leading-tight mb-1">
            {{ $product->merk }}
        </a>
        <p class="text-xs text-gray-500 mb-3">{{ $product->ukuran }}</p>

        <div class="mt-auto">
            <p class="text-lg font-bold text-sky-700">
                Rp {{ number_format($product->harga_karton, 0, ',', '.') }}
                <span class="text-xs font-normal text-gray-500">/ karton</span>
            </p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $product->pcs_per_karton }} pcs / karton</p>

            <div class="flex items-center justify-between mt-3">
                @if ($inStock)
                    <span class="text-xs bg-sky-50 text-sky-700 px-2 py-1 rounded-full font-medium">
                        Stok: {{ $product->stok_karton }} karton
                    </span>
                @else
                    <span class="text-xs bg-red-50 text-red-600 px-2 py-1 rounded-full font-medium">Stok Habis</span>
                @endif
            </div>

            {{-- Cart action --}}
            @if (!$inStock)
                <a href="{{ auth('customer')->check() ? route('po.show', $product) : route('login') }}"
                   class="flex items-center justify-center gap-1.5 w-full mt-3 py-2 text-sm font-medium rounded-lg bg-sky-600 hover:bg-sky-700 text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 11H4L5 9z"/>
                    </svg>
                    Pesan PO
                </a>
            @else
                {{-- Button: tambah (qty=0) atau stepper (qty>0) --}}
                <div class="mt-3">

                    {{-- Tombol + Keranjang --}}
                    <button x-show="qty === 0" @click="inc()"
                        class="w-full py-2 text-sm font-medium rounded-lg bg-sky-600 hover:bg-sky-700 text-white transition-colors">
                        + Keranjang
                    </button>

                    {{-- Stepper --}}
                    <div x-show="qty > 0"
                        class="flex items-center rounded-lg overflow-hidden border border-sky-200 bg-sky-50">

                        <button type="button" @click="dec()"
                            class="w-9 h-9 flex items-center justify-center text-sky-600 hover:bg-sky-100 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4" />
                            </svg>
                        </button>

                        <span class="flex-1 text-center text-sm font-bold text-sky-700 tabular-nums relative">
                            <span x-text="qty"></span>
                            {{-- Dot indikator syncing --}}
                            <span x-show="syncing"
                                class="absolute -top-1 -right-1 w-2 h-2 bg-sky-400 rounded-full animate-pulse"></span>
                        </span>

                        <button type="button" @click="inc()" :disabled="qty >= max"
                            class="w-9 h-9 flex items-center justify-center text-sky-600 hover:bg-sky-100 transition-colors disabled:opacity-40">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>

                    <p x-show="capped" x-transition class="text-xs text-sky-600 mt-1.5 text-center font-medium">
                        Maks. {{ $product->stok_karton }} karton
                    </p>

                </div>
            @endif
        </div>
    </div>
</div>
