<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static ?string $navigationLabel = 'Barang Masuk';

    protected static ?string $modelLabel = 'Barang';

    protected static ?string $pluralModelLabel = 'Data Barang';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Foto Produk')
                    ->schema([
                        FileUpload::make('foto')
                            ->label('Foto Produk')
                            ->image()
                            ->imageEditor()
                            ->directory('products')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ]),

                Section::make('Informasi Barang')
                    ->schema([
                        TextInput::make('merk')
                            ->label('Merk')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Frisian Flag'),

                        TextInput::make('ukuran')
                            ->label('Ukuran')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Contoh: 110ml'),

                        TextInput::make('pcs_per_karton')
                            ->label('Pcs / Karton')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->suffix('pcs')
                            ->placeholder('Contoh: 36'),

                        TextInput::make('supplier')
                            ->label('Supplier')
                            ->maxLength(255)
                            ->placeholder('Nama supplier'),
                    ])
                    ->columns(2),

                Section::make('Jumlah Masuk')
                    ->schema([
                        TextInput::make('jumlah_masuk')
                            ->label('Jumlah Barang Masuk')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->suffix('karton')
                            ->placeholder('Contoh: 1000')
                            ->live(onBlur: true)
                            ->helperText(function ($get) {
                                $pcs = (int) $get('pcs_per_karton');
                                $karton = (int) $get('jumlah_masuk');
                                if ($pcs > 0 && $karton > 0) {
                                    return 'Total: ' . number_format($pcs * $karton) . ' pcs';
                                }
                                return 'Isi Pcs/Karton terlebih dahulu untuk melihat total pcs';
                            }),

                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(3)
                            ->placeholder('Keterangan tambahan (opsional)'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=P&color=7F9CF5&background=EBF4FF'),

                TextColumn::make('merk')
                    ->label('Merk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('ukuran')
                    ->label('Ukuran')
                    ->searchable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('pcs_per_karton')
                    ->label('Pcs/Karton')
                    ->numeric()
                    ->suffix(' pcs')
                    ->alignCenter(),

                TextColumn::make('jumlah_masuk')
                    ->label('Jumlah Masuk')
                    ->numeric()
                    ->suffix(' karton')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('total_pcs')
                    ->label('Total Pcs')
                    ->getStateUsing(fn (Product $record) => $record->jumlah_masuk * $record->pcs_per_karton)
                    ->numeric()
                    ->suffix(' pcs')
                    ->alignCenter()
                    ->color('success')
                    ->weight('bold'),

                TextColumn::make('supplier')
                    ->label('Supplier')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Input')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('merk')
                    ->label('Filter Merk')
                    ->options(fn () => Product::query()->distinct()->pluck('merk', 'merk')->toArray())
                    ->searchable(),

                SelectFilter::make('ukuran')
                    ->label('Filter Ukuran')
                    ->options(fn () => Product::query()->distinct()->pluck('ukuran', 'ukuran')->toArray())
                    ->searchable(),

                SelectFilter::make('supplier')
                    ->label('Filter Supplier')
                    ->options(fn () => Product::query()->whereNotNull('supplier')->distinct()->pluck('supplier', 'supplier')->toArray())
                    ->searchable(),
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
            ->emptyStateHeading('Belum ada data barang')
            ->emptyStateDescription('Klik tombol "Tambah Barang" untuk menginput barang masuk pertama.')
            ->emptyStateIcon(Heroicon::OutlinedArchiveBox);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view'   => Pages\ViewProduct::route('/{record}'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
