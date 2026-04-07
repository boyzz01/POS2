@props(['product'])

<a href="{{ route('catalog.show', $product) }}"
   class="group bg-white rounded-xl border border-gray-100 hover:shadow-md hover:border-sky-100 transition-all duration-200 flex flex-col overflow-hidden">

    {{-- Image --}}
    <div class="aspect-square bg-sky-50 overflow-hidden">
        @if($product->foto)
        <img src="{{ asset('storage/' . $product->foto) }}"
             alt="{{ $product->merk }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
             loading="lazy">
        @else
        <div class="w-full h-full flex items-center justify-center">
            <svg class="w-10 h-10 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="p-3">
        @if($product->kategori)
        <span class="text-xs font-medium text-sky-600 uppercase tracking-wide">{{ $product->kategori?->nama }}</span>
        @endif
        <p class="text-sm font-semibold text-gray-900 group-hover:text-sky-700 transition-colors line-clamp-2 leading-snug mt-0.5">
            {{ $product->merk }}
        </p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $product->ukuran }}</p>
        <p class="text-sm font-bold text-sky-700 mt-2 tabular-nums">
            Rp {{ number_format($product->harga_karton, 0, ',', '.') }}
            <span class="text-xs font-normal text-gray-400">/ karton</span>
        </p>
    </div>

</a>
