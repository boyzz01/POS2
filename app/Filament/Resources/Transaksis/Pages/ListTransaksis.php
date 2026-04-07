<?php

namespace App\Filament\Resources\Transaksis\Pages;

use App\Filament\Resources\Transaksis\TransaksiResource;
use App\Models\Gudang;
use App\Models\Transaksi;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTransaksis extends ListRecords
{
    protected static string $resource = TransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        $user = auth()->user();

        // Non-super-admin users only see their own gudang — no tabs needed
        if (! $user?->isSuperAdmin()) {
            return [];
        }

        $tabs = [
            'semua' => Tab::make('Semua')
                ->icon('heroicon-o-globe-alt')
                ->badge(Transaksi::where('status', 'selesai')->count()),
        ];

        $gudangs = Gudang::where('aktif', true)
            ->orderByRaw("FIELD(tipe,'toko','gudang')")
            ->orderBy('nama')
            ->get();

        foreach ($gudangs as $gudang) {
            $count = Transaksi::where('status', 'selesai')
                ->where('gudang_id', $gudang->id)
                ->count();

            $tabs[(string) $gudang->id] = Tab::make($gudang->nama)
                ->icon($gudang->tipe === 'toko' ? 'heroicon-o-building-storefront' : 'heroicon-o-archive-box')
                ->badge($count)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('gudang_id', $gudang->id));
        }

        return $tabs;
    }
}
