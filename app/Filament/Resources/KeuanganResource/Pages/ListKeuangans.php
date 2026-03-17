<?php

namespace App\Filament\Resources\KeuanganResource\Pages;

use App\Filament\Resources\KeuanganResource;
use App\Models\Keuangan;
use Filament\Actions\CreateAction;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListKeuangans extends ListRecords
{
    protected static string $resource = KeuanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Transaksi'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua')
                ->badge(Keuangan::count()),

            'pemasukan' => Tab::make('Pemasukan')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('jenis', 'pemasukan'))
                ->badge(Keuangan::where('jenis', 'pemasukan')->count())
                ->badgeColor('success'),

            'pengeluaran' => Tab::make('Pengeluaran')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('jenis', 'pengeluaran'))
                ->badge(Keuangan::where('jenis', 'pengeluaran')->count())
                ->badgeColor('danger'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            KeuanganResource\Widgets\KeuanganStatsWidget::class,
        ];
    }
}
