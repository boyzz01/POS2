<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $modelLabel = 'Produk';

    protected static ?string $pluralModelLabel = 'Katalog Produk';

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
                            ->disk('public')
                            ->visibility('public')
                            ->directory('products')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ]),

                Section::make('Informasi Produk')
                    ->schema([
                        TextInput::make('merk')
                            ->label('Merk / Nama Produk')
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

                        TextInput::make('harga_karton')
                            ->label('Harga / Karton')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->placeholder('Contoh: 180000'),

                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Keterangan tambahan (opsional)'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
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

                TextColumn::make('harga_karton')
                    ->label('Harga/Karton')
                    ->money('IDR')
                    ->sortable()
                    ->color('warning')
                    ->weight('bold'),

                TextColumn::make('stok_karton')
                    ->label('Stok')
                    ->getStateUsing(fn (Product $record) => $record->stok_karton)
                    ->suffix(' karton')
                    ->alignCenter()
                    ->color('success')
                    ->weight('bold'),

                TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('merk')
            ->filters([
                SelectFilter::make('ukuran')
                    ->label('Ukuran')
                    ->options(fn () => Product::query()->distinct()->pluck('ukuran', 'ukuran')->toArray())
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
            ->emptyStateHeading('Belum ada produk')
            ->emptyStateDescription('Klik tombol "Tambah Produk" untuk menambah produk ke katalog.')
            ->emptyStateIcon(Heroicon::OutlinedTag);
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
