<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Livewire\Attributes\On;

class TransaksiTerbaruWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'TransaksiTerbaru';

    public ?int $gudangId = null;

    #[On('gudang-filter-changed')]
    public function updateGudang(?int $gudangId): void
    {
        $this->gudangId = $gudangId;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaksi::query()
                    ->with(['kasir', 'gudang'])
                    ->where('status', 'selesai')
                    ->when($this->gudangId, fn($q) => $q->where('gudang_id', $this->gudangId))
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('kode_transaksi')
                    ->label('Kode')
                    ->weight('bold')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('kasir.name')
                    ->label('Kasir')
                    ->searchable(),

                TextColumn::make('gudang.nama')
                    ->label('Lokasi')
                    ->badge()
                    ->color('info')
                    ->placeholder('-'),

                TextColumn::make('total_harga')
                    ->label('Total')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->weight('bold')
                    ->color('success'),

                TextColumn::make('total_bayar')
                    ->label('Dibayar')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                TextColumn::make('kembalian')
                    ->label('Kembalian')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'selesai' => 'success',
                        'batal'   => 'danger',
                        default   => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
