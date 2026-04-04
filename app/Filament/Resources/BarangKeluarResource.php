<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangKeluarResource\Pages;
use App\Models\BarangKeluar;
use App\Models\Gudang;
use App\Models\Product;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BarangKeluarResource extends Resource
{
    protected static ?string $model = BarangKeluar::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedArrowUpTray;

    protected static ?string $navigationLabel = 'Barang Keluar';

    protected static ?string $modelLabel = 'Barang Keluar';

    protected static ?string $pluralModelLabel = 'Daftar Barang Keluar';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Pengiriman')
                ->schema([
                    Select::make('gudang_id')
                        ->label('Gudang / Asal')
                        ->required()
                        ->native(false)
                        ->options(fn () => Gudang::where('aktif', true)
                            ->get()
                            ->mapWithKeys(fn ($g) => [$g->id => ($g->tipe === 'toko' ? '🏪 ' : '🏭 ') . $g->nama])
                        ),

                    DatePicker::make('tanggal')
                        ->label('Tanggal Keluar')
                        ->required()
                        ->default(now())
                        ->native(false)
                        ->displayFormat('d M Y'),

                    TextInput::make('tujuan')
                        ->label('Tujuan')
                        ->placeholder('Contoh: Toko Medan, Customer A...')
                        ->maxLength(255),

                    TextInput::make('nama_penerima')
                        ->label('Nama Penerima')
                        ->placeholder('Nama pembeli / penerima')
                        ->maxLength(255),

                    Radio::make('metode_pembayaran')
                        ->label('Metode Pembayaran')
                        ->options(['cash' => 'Cash', 'transfer' => 'Transfer'])
                        ->default('transfer')
                        ->inline()
                        ->columnSpanFull(),

                    Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->rows(2)
                        ->placeholder('Keterangan tambahan')
                        ->columnSpanFull(),
                ])
                ->columns(4),

            Section::make('Daftar Barang')
                ->schema([
                    Repeater::make('items')
                        ->label('')
                        ->relationship('items')
                        ->schema([
                            Select::make('product_id')
                                ->label('Produk')
                                ->required()
                                ->native(false)
                                ->searchable()
                                ->options(fn () => Product::where('harga_karton', '>', 0)
                                    ->get()
                                    ->mapWithKeys(fn ($p) => [
                                        $p->id => "{$p->merk} - {$p->ukuran} (Stok: {$p->stok_karton} karton)"
                                    ])
                                )
                                ->afterStateUpdated(function ($state, $set) {
                                    if ($state) {
                                        $product = Product::find($state);
                                        $set('nama_produk', $product?->merk . ' ' . $product?->ukuran);
                                        $set('harga_satuan', $product?->harga_karton ?? 0);
                                        $set('subtotal', 0);
                                    }
                                })
                                ->live()
                                ->columnSpan(2),

                            TextInput::make('jumlah')
                                ->label('Jumlah (Karton)')
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->suffix('karton')
                                ->live(debounce: 300)
                                ->afterStateUpdated(function ($state, $get, $set) {
                                    $set('subtotal', (int) $state * (int) $get('harga_satuan'));
                                }),

                            TextInput::make('harga_satuan')
                                ->label('Harga/Karton')
                                ->numeric()
                                ->prefix('Rp')
                                ->readOnly(),

                            TextInput::make('subtotal')
                                ->label('Subtotal')
                                ->numeric()
                                ->prefix('Rp')
                                ->readOnly(),

                            TextInput::make('nama_produk')
                                ->label('Nama Produk')
                                ->required()
                                ->readOnly()
                                ->columnSpan(2),
                        ])
                        ->columns(6)
                        ->addActionLabel('+ Tambah Produk')
                        ->reorderable(false)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('gudang.nama')
                    ->label('Asal Gudang')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('tujuan')
                    ->label('Tujuan')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('metode_pembayaran')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn ($state) => $state === 'cash' ? 'success' : 'info'),

                TextColumn::make('items_count')
                    ->label('Jml Produk')
                    ->counts('items')
                    ->suffix(' produk')
                    ->badge()
                    ->color('info'),

                TextColumn::make('user.name')
                    ->label('Dicatat oleh')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('tanggal', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada data barang keluar')
            ->emptyStateIcon(Heroicon::OutlinedArrowUpTray);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBarangKeluars::route('/'),
            'create' => Pages\CreateBarangKeluar::route('/create'),
            'view'   => Pages\ViewBarangKeluar::route('/{record}'),
            'edit'   => Pages\EditBarangKeluar::route('/{record}/edit'),
        ];
    }
}
