<?php

namespace App\Filament\Widgets;

use App\Models\Keuangan;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class KeuanganBulanIniWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Transaksi Keuangan Bulan Ini';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Keuangan::query()
                    ->with('user')
                    ->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year)
                    ->latest('tanggal')
                    ->limit(8)
            )
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn (string $state) => match ($state) {
                        'pemasukan'   => 'success',
                        'pengeluaran' => 'danger',
                        default       => 'gray',
                    }),

                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('judul')
                    ->label('Keterangan')
                    ->limit(35),

                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->formatStateUsing(function ($state, $record) {
                        $prefix = $record->jenis === 'pemasukan' ? '+ ' : '- ';
                        return $prefix . 'Rp ' . number_format($state, 0, ',', '.');
                    })
                    ->color(fn ($record) => $record->jenis === 'pemasukan' ? 'success' : 'danger')
                    ->weight('bold'),

                TextColumn::make('user.name')
                    ->label('Dicatat oleh'),
            ])
            ->defaultSort('tanggal', 'desc');
    }
}
