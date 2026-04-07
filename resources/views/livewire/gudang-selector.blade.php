<div x-data="{ open: false }" class="relative" @click.outside="open = false" wire:loading.class="opacity-60 pointer-events-none">
    <button
        @click="@if(!$locked) open = !open @endif"
        type="button"
        class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium
               bg-amber-50 border border-amber-200 text-amber-800
               hover:bg-amber-100 transition-colors duration-150
               {{ $locked ? 'cursor-default opacity-80' : 'cursor-pointer' }}"
    >
        @if($locked)
            <x-heroicon-o-lock-closed class="w-4 h-4 text-amber-600" />
        @else
            <x-heroicon-o-building-storefront class="w-4 h-4 text-amber-600" />
        @endif

        <span class="max-w-[120px] truncate">
            {{ $active ? $active->nama : 'Semua Gudang' }}
        </span>

        @if(!$locked)
            <svg wire:loading wire:target="setGudang" class="w-3 h-3 text-amber-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            <x-heroicon-o-chevron-down wire:loading.remove wire:target="setGudang" class="w-3 h-3 text-amber-500" x-bind:class="open ? 'rotate-180' : ''" style="transition: transform 0.15s" />
        @endif
    </button>

    @if(!$locked)
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute right-0 mt-1 w-52 rounded-lg shadow-lg bg-white border border-gray-100 z-50 overflow-hidden"
            style="display: none;"
        >
            <div class="py-1">
                <button
                    wire:click="setGudang(null)"
                    @click="open = false"
                    type="button"
                    class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 transition-colors
                           {{ $active === null ? 'bg-amber-50 font-semibold text-amber-800' : '' }}"
                >
                    <x-heroicon-o-building-office-2 class="w-4 h-4 {{ $active === null ? 'text-amber-500' : 'text-gray-400' }}" />
                    Semua Gudang
                    @if($active === null)
                        <x-heroicon-o-check class="w-3 h-3 text-amber-500 ml-auto" />
                    @endif
                </button>

                @if($gudangs->isNotEmpty())
                    <div class="border-t border-gray-100 my-1"></div>
                    @foreach($gudangs as $gudang)
                        <button
                            wire:click="setGudang({{ $gudang->id }})"
                            @click="open = false"
                            type="button"
                            class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 transition-colors
                                   {{ $active?->id === $gudang->id ? 'bg-amber-50 font-semibold text-amber-800' : '' }}"
                        >
                            <x-heroicon-o-building-storefront class="w-4 h-4 {{ $active?->id === $gudang->id ? 'text-amber-500' : 'text-gray-400' }}" />
                            <span class="truncate">{{ $gudang->nama }}</span>
                            @if($gudang->tipe)
                                <span class="ml-auto text-xs text-gray-400 shrink-0">{{ $gudang->tipe }}</span>
                            @endif
                            @if($active?->id === $gudang->id)
                                <x-heroicon-o-check class="w-3 h-3 text-amber-500 shrink-0" />
                            @endif
                        </button>
                    @endforeach
                @endif
            </div>
        </div>
    @endif
</div>
