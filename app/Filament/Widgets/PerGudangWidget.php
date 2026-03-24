<?php

namespace App\Filament\Widgets;

use App\Models\Gudang;
use App\Models\Keuangan;
use App\Models\Transaksi;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Livewire\Attributes\On;

class PerGudangWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    public ?int $gudangId = null;

    #[On('gudang-filter-changed')]
    public function updateGudang(?int $gudangId): void
    {
        $this->gudangId = $gudangId;
    }

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Ringkasan Per Gudang / Toko';

    public function table(Table $table): Table
    {
        return $table
            ->query(Gudang::query()->where('aktif', true)->orderByRaw("FIELD(tipe,'toko','gudang')")->orderBy('nama'))
            ->columns([
                TextColumn::make('nama')
                    ->label('Lokasi')
                    ->formatStateUsing(fn ($state, $record) => ($record->tipe === 'toko' ? '🏪 ' : '🏭 ') . $state)
                    ->weight('bold'),

                TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn ($state) => $state === 'toko' ? 'info' : 'warning'),

                TextColumn::make('pemasukan_bulan_ini')
                    ->label('Pemasukan Bln Ini')
                    ->state(fn ($record) => 'Rp ' . number_format(
                        Keuangan::where('gudang_id', $record->id)
                            ->where('jenis', 'pemasukan')
                            ->whereMonth('tanggal', now()->month)
                            ->whereYear('tanggal', now()->year)
                            ->sum('jumlah'),
                        0, ',', '.'
                    ))
                    ->color('success'),

                TextColumn::make('pengeluaran_bulan_ini')
                    ->label('Pengeluaran Bln Ini')
                    ->state(fn ($record) => 'Rp ' . number_format(
                        Keuangan::where('gudang_id', $record->id)
                            ->where('jenis', 'pengeluaran')
                            ->whereMonth('tanggal', now()->month)
                            ->whereYear('tanggal', now()->year)
                            ->sum('jumlah'),
                        0, ',', '.'
                    ))
                    ->color('danger'),

                TextColumn::make('saldo')
                    ->label('Saldo')
                    ->state(function ($record) {
                        $masuk  = Keuangan::where('gudang_id', $record->id)->where('jenis', 'pemasukan')->sum('jumlah');
                        $keluar = Keuangan::where('gudang_id', $record->id)->where('jenis', 'pengeluaran')->sum('jumlah');
                        $saldo  = $masuk - $keluar;
                        return ($saldo >= 0 ? '+ ' : '- ') . 'Rp ' . number_format(abs($saldo), 0, ',', '.');
                    })
                    ->color(fn ($record) => $this->getSaldoColor($record))
                    ->weight('bold'),

                TextColumn::make('omzet_pos_bulan_ini')
                    ->label('Omzet POS Bln Ini')
                    ->state(fn ($record) => 'Rp ' . number_format(
                        Transaksi::where('gudang_id', $record->id)
                            ->where('status', 'selesai')
                            ->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->sum('total_harga'),
                        0, ',', '.'
                    ))
                    ->color('primary'),

                TextColumn::make('total_transaksi')
                    ->label('Total Transaksi')
                    ->state(fn ($record) => Transaksi::where('gudang_id', $record->id)
                        ->where('status', 'selesai')->count() . ' trx')
                    ->color('gray'),
            ])
            ->paginated(false);
    }

    private function getSaldoColor($record): string
    {
        $masuk  = Keuangan::where('gudang_id', $record->id)->where('jenis', 'pemasukan')->sum('jumlah');
        $keluar = Keuangan::where('gudang_id', $record->id)->where('jenis', 'pengeluaran')->sum('jumlah');
        return ($masuk - $keluar) >= 0 ? 'success' : 'danger';
    }
}
