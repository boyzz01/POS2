<?php

namespace App\Filament\Resources\Transaksis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransaksisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_transaksi')
                    ->label('Kode Transaksi')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('gudang.nama')
                    ->label('Lokasi')
                    ->badge()
                    ->color('info')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('kasir.name')
                    ->label('Kasir')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_pembeli')
                    ->label('Pembeli')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('metode_pembayaran')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn ($state) => $state === 'cash' ? 'success' : 'info'),

                TextColumn::make('total_harga')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('kembalian')
                    ->label('Kembalian')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn ($state) => match ($state) {
                        'selesai' => 'success',
                        'batal'   => 'danger',
                        default   => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'selesai' => 'Selesai',
                        'batal'   => 'Batal',
                    ]),

                SelectFilter::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash'     => 'Cash',
                        'transfer' => 'Transfer',
                    ]),

                SelectFilter::make('gudang_id')
                    ->label('Lokasi')
                    ->relationship('gudang', 'nama')
                    ->native(false),

                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('dari')->label('Dari Tanggal')->native(false)->displayFormat('d M Y'),
                        DatePicker::make('sampai')->label('Sampai Tanggal')->native(false)->displayFormat('d M Y'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['dari'], fn ($q) => $q->whereDate('created_at', '>=', $data['dari']))
                        ->when($data['sampai'], fn ($q) => $q->whereDate('created_at', '<=', $data['sampai'])))
                    ->columns(2),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada transaksi')
            ->emptyStateIcon('heroicon-o-shopping-cart');
    }
}
