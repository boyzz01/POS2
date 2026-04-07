<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangMasukResource\Pages;
use App\Models\BarangMasuk;
use App\Models\Gudang;
use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BarangMasukResource extends Resource
{
    protected static ?string $model = BarangMasuk::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedArchiveBoxArrowDown;

    protected static ?string $navigationLabel = 'Barang Masuk';

    protected static ?string $modelLabel = 'Barang Masuk';

    protected static ?string $pluralModelLabel = 'Riwayat Barang Masuk';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return !$user?->isKasir() && !$user?->isKeuangan();
    }

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Barang Masuk')
                    ->schema([
                        Select::make('product_id')
                            ->label('Produk')
                            ->required()
                            ->options(fn () => Product::orderBy('merk')->get()->mapWithKeys(
                                fn ($p) => [$p->id => $p->merk . ' – ' . $p->ukuran]
                            ))
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('Pilih produk')
                            ->live()
                            ->afterStateUpdated(fn ($state, $set) => $set('_product_info', $state)),

                        Select::make('gudang_id')
                            ->label('Gudang')
                            ->required()
                            ->options(fn () => Gudang::where('aktif', true)->pluck('nama', 'id'))
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('Pilih gudang'),

                        TextInput::make('jumlah')
                            ->label('Jumlah Masuk')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->suffix('karton')
                            ->placeholder('Contoh: 50'),

                        TextInput::make('supplier')
                            ->label('Supplier')
                            ->maxLength(255)
                            ->placeholder('Nama supplier'),

                        DatePicker::make('tanggal')
                            ->label('Tanggal Masuk')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d M Y'),

                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(3)
                            ->placeholder('Keterangan tambahan (opsional)'),

                        FileUpload::make('foto')
                            ->label('Foto Bukti Barang Masuk')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('barang-masuk')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Bukti')
                    ->disk('public')
                    ->circular()
                    ->toggleable(),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('product.merk')
                    ->label('Produk')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('product.ukuran')
                    ->label('Ukuran')
                    ->badge()
                    ->color('info'),

                TextColumn::make('gudang.nama')
                    ->label('Gudang')
                    ->badge()
                    ->color('success'),

                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->suffix(' karton')
                    ->alignCenter()
                    ->weight('bold'),

                TextColumn::make('supplier')
                    ->label('Supplier')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Diinput oleh')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Waktu Input')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('tanggal', 'desc')
            ->filters([
                SelectFilter::make('product_id')
                    ->label('Produk')
                    ->options(fn () => Product::orderBy('merk')->pluck('merk', 'id'))
                    ->searchable(),

                SelectFilter::make('gudang_id')
                    ->label('Gudang')
                    ->options(fn () => Gudang::pluck('nama', 'id')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada data barang masuk')
            ->emptyStateDescription('Klik tombol "Tambah Barang Masuk" untuk mencatat stok masuk.')
            ->emptyStateIcon(Heroicon::OutlinedArchiveBoxArrowDown);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBarangMasuks::route('/'),
            'create' => Pages\CreateBarangMasuk::route('/create'),
            'view'   => Pages\ViewBarangMasuk::route('/{record}'),
            'edit'   => Pages\EditBarangMasuk::route('/{record}/edit'),
        ];
    }
}
