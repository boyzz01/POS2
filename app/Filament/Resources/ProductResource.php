<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use App\Models\Kategori;
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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $modelLabel = 'Produk';

    protected static ?string $pluralModelLabel = 'Katalog Produk';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return !$user?->isKasir() && !$user?->isKeuangan();
    }

    public static function canCreate(): bool
    {
        return !auth()->user()?->isKasir();
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

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

                        Select::make('kategori_id')
                            ->label('Kategori')
                            ->relationship('kategori', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('nama')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->unique('kategoris', 'nama')
                                    ->maxLength(100),
                            ])
                            ->createOptionUsing(fn (array $data) => Kategori::create($data)->getKey())
                            ->placeholder('Pilih atau buat kategori baru'),

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

                        \Filament\Forms\Components\Toggle::make('is_active')
                            ->label('Aktif di Katalog')
                            ->default(true)
                            ->helperText('Nonaktif = tidak muncul di storefront'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $gudangId = auth()->user()?->activeGudangId();
                if ($gudangId) {
                    $query->where('gudang_id', $gudangId);
                }
            })
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=P&color=7F9CF5&background=EBF4FF'),

                TextColumn::make('gudang.nama')
                    ->label('Gudang')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->toggleable()
                    ->hidden(fn () => (bool) session('active_gudang_id')),

                TextColumn::make('merk')
                    ->label('Merk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->toggleable(),

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

                \Filament\Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('merk')
            ->filters([
                SelectFilter::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('ukuran')
                    ->label('Ukuran')
                    ->options(fn () => Product::query()->distinct()->pluck('ukuran', 'ukuran')->toArray())
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
            ])
            ->toolbarActions([
                Action::make('exportExcel')
                    ->label('Export Excel')
                    ->icon(Heroicon::OutlinedTableCells)
                    ->color('success')
                    ->url(route('export.products.excel'))
                    ->openUrlInNewTab(),

                Action::make('exportPdf')
                    ->label('Export PDF')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->color('danger')
                    ->url(route('export.products.pdf'))
                    ->openUrlInNewTab(),

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
