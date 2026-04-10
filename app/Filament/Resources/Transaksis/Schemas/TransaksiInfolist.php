<?php

namespace App\Filament\Resources\Transaksis\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransaksiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Transaksi')
                    ->schema([
                        TextEntry::make('kode_transaksi')->label('Kode Transaksi')->weight('bold')->copyable(),
                        TextEntry::make('gudang.nama')->label('Lokasi')->badge()->color('info')->placeholder('-'),
                        TextEntry::make('kasir.name')->label('Kasir'),
                        TextEntry::make('nama_pembeli')->label('Pembeli')->placeholder('-'),
                        TextEntry::make('metode_pembayaran')->label('Metode Pembayaran')
                            ->badge()
                            ->formatStateUsing(fn ($state) => ucfirst($state))
                            ->color(fn ($state) => $state === 'cash' ? 'success' : 'info'),
                        TextEntry::make('status')->label('Status')
                            ->badge()
                            ->formatStateUsing(fn ($state) => ucfirst($state))
                            ->color(fn ($state) => match ($state) {
                                'selesai' => 'success',
                                'batal'   => 'danger',
                                default   => 'gray',
                            }),
                        TextEntry::make('created_at')->label('Waktu Transaksi')->dateTime('d M Y, H:i'),
                        TextEntry::make('catatan')->label('Catatan')->placeholder('-')->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Rincian Pembayaran')
                    ->schema([
                        TextEntry::make('total_harga')->label('Total Harga')
                            ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                            ->weight('bold')->color('success'),
                        TextEntry::make('total_bayar')->label('Dibayar')
                            ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                        TextEntry::make('kembalian')->label('Kembalian')
                            ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                    ])
                    ->columns(3),

                Section::make('Item Produk')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                TextEntry::make('nama_produk')->label('Produk')->weight('bold'),
                                TextEntry::make('ukuran')->label('Ukuran'),
                                TextEntry::make('jumlah')->label('Qty')->suffix(' karton'),
                                TextEntry::make('harga_satuan')->label('Harga/Karton')
                                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                                TextEntry::make('subtotal')->label('Subtotal')
                                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                                    ->weight('bold')->color('success'),
                            ])
                            ->columns(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
