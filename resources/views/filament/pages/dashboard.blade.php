<x-filament-panels::page>

    {{-- Tab Gudang --}}
    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:4px;">

        {{-- Tab Semua --}}
        <button wire:click="setGudang('semua')"
            style="display:flex; align-items:center; gap:6px; padding:7px 16px; border-radius:20px; font-size:13px; font-weight:600; cursor:pointer; border:1.5px solid {{ $activeGudang === 'semua' ? 'var(--fi-primary-600,#4f46e5)' : '#e2e8f0' }}; background:{{ $activeGudang === 'semua' ? 'var(--fi-primary-600,#4f46e5)' : 'white' }}; color:{{ $activeGudang === 'semua' ? 'white' : '#64748b' }}; transition:.15s;">
            <x-heroicon-o-globe-alt style="width:14px;height:14px;" />
            Semua
        </button>

        @foreach($this->getGudangs() as $gudang)
            <button wire:click="setGudang('{{ $gudang->id }}')"
                style="display:flex; align-items:center; gap:6px; padding:7px 16px; border-radius:20px; font-size:13px; font-weight:600; cursor:pointer; border:1.5px solid {{ $activeGudang == $gudang->id ? 'var(--fi-primary-600,#4f46e5)' : '#e2e8f0' }}; background:{{ $activeGudang == $gudang->id ? 'var(--fi-primary-600,#4f46e5)' : 'white' }}; color:{{ $activeGudang == $gudang->id ? 'white' : '#64748b' }}; transition:.15s;">
                @if($gudang->tipe === 'toko')
                    <x-heroicon-o-building-storefront style="width:14px;height:14px;" />
                @else
                    <x-heroicon-o-archive-box style="width:14px;height:14px;" />
                @endif
                {{ $gudang->nama }}
                <span style="font-size:10px; opacity:.75;">{{ ucfirst($gudang->tipe) }}</span>
            </button>
        @endforeach

    </div>

    {{-- Widgets --}}
    <x-filament-widgets::widgets
        :widgets="$this->getVisibleWidgets()"
        :columns="$this->getColumns()"
    />

</x-filament-panels::page>
