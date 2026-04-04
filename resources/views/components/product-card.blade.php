@props(['product'])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group flex flex-col">
    {{-- Image --}}
    <a href="{{ route('catalog.show', $product) }}" class="block overflow-hidden rounded-t-xl">
        <div class="aspect-square bg-gray-100 flex items-center justify-center">
            @if($product->foto)
            <img src="{{ asset('storage/' . $product->foto) }}"
                 alt="{{ $product->merk }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                 loading="lazy">
            @else
            <div class="w-full h-full bg-sky-50 flex items-center justify-center">
                <svg class="w-16 h-16 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            @endif
        </div>
    </a>

    {{-- Info --}}
    <div class="p-4 flex flex-col flex-1">
        @if($product->kategori)
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
                @if($product->stok_karton > 0)
                <span class="text-xs bg-sky-50 text-sky-700 px-2 py-1 rounded-full font-medium">
                    Stok: {{ $product->stok_karton }} karton
                </span>
                @else
                <span class="text-xs bg-red-50 text-red-600 px-2 py-1 rounded-full font-medium">Stok Habis</span>
                @endif
            </div>

            <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-3">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                        @if($product->stok_karton <= 0) disabled @endif
                        class="w-full py-2 text-sm font-medium rounded-lg transition-colors
                               {{ $product->stok_karton > 0
                                    ? 'bg-sky-600 hover:bg-sky-700 text-white'
                                    : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                    {{ $product->stok_karton > 0 ? '+ Keranjang' : 'Habis' }}
                </button>
            </form>
        </div>
    </div>
</div>
