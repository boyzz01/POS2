<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GudangResource\Pages;
use App\Models\Gudang;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class GudangResource extends Resource
{
    protected static ?string $model = Gudang::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $navigationLabel = 'Gudang';

    protected static ?string $modelLabel = 'Gudang';

    protected static ?string $pluralModelLabel = 'Data Gudang';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return !$user?->isKasir() && !$user?->isAdmin() && !$user?->isKepalaGudang() && !$user?->isKeuangan();
    }

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Gudang')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Gudang')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Gudang A'),

                        TextInput::make('kode')
                            ->label('Kode Gudang')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('Contoh: GDG-A'),

                        TextInput::make('lokasi')
                            ->label('Lokasi / Alamat')
                            ->maxLength(255)
                            ->placeholder('Contoh: Jl. Raya No. 1, Jakarta')
                            ->columnSpanFull(),

                        TextInput::make('penanggung_jawab')
                            ->label('Penanggung Jawab')
                            ->maxLength(255)
                            ->placeholder('Nama penanggung jawab gudang'),

                        Toggle::make('aktif')
                            ->label('Status Aktif')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),

                Section::make('Keterangan')
                    ->schema([
                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(3)
                            ->placeholder('Keterangan tambahan tentang gudang ini')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama')
                    ->label('Nama Gudang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->searchable()
                    ->limit(40)
                    ->toggleable(),

                TextColumn::make('penanggung_jawab')
                    ->label('Penanggung Jawab')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('products_count')
                    ->label('Jumlah Barang')
                    ->counts('products')
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                IconColumn::make('aktif')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon(Heroicon::OutlinedCheckCircle)
                    ->falseIcon(Heroicon::OutlinedXCircle)
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('nama')
            ->filters([
                TernaryFilter::make('aktif')
                    ->label('Status Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
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
            ->emptyStateHeading('Belum ada data gudang')
            ->emptyStateDescription('Klik tombol "Tambah Gudang" untuk menambahkan gudang baru.')
            ->emptyStateIcon(Heroicon::OutlinedBuildingStorefront);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGudangs::route('/'),
            'create' => Pages\CreateGudang::route('/create'),
            'view'   => Pages\ViewGudang::route('/{record}'),
            'edit'   => Pages\EditGudang::route('/{record}/edit'),
        ];
    }
}
