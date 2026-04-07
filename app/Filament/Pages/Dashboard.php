<?php

namespace App\Filament\Pages;

use App\Filament\Resources\KeuanganResource;
use App\Filament\Resources\ProductResource;
use App\Models\Gudang;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    public string $activeGudang = 'semua';

    public function mount()
    {
        $user = auth()->user();

        if ($user?->isKasir()) {
            return redirect()->to(PosKasir::getUrl());
        }

        if ($user?->isAdmin() || $user?->isKepalaGudang()) {
            return redirect()->to(ProductResource::getUrl());
        }

        if ($user?->isKeuangan()) {
            return redirect()->to(KeuanganResource::getUrl());
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

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
