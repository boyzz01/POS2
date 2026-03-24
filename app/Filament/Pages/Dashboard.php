<?php

namespace App\Filament\Pages;

use App\Models\Gudang;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    public string $activeGudang = 'semua';

    public function setGudang(string $key): void
    {
        $this->activeGudang = $key;
        $gudangId = $key === 'semua' ? null : (int) $key;
        $this->dispatch('gudang-filter-changed', gudangId: $gudangId);
    }

    public function getGudangs()
    {
        return Gudang::where('aktif', true)
            ->orderByRaw("FIELD(tipe,'toko','gudang')")
            ->orderBy('nama')
            ->get();
    }

    public function getView(): string
    {
        return 'filament.pages.dashboard';
    }
}
