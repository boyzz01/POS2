<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeuanganResource\Pages;
use App\Models\Keuangan;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KeuanganResource extends Resource
{
    protected static ?string $model = Keuangan::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Keuangan';

    protected static ?string $modelLabel = 'Transaksi Keuangan';

    protected static ?string $pluralModelLabel = 'Pemasukan & Pengeluaran';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Jenis Transaksi')
                    ->schema([
                        Radio::make('jenis')
                            ->label('Jenis')
                            ->options([
                                'pemasukan'   => 'Pemasukan',
                                'pengeluaran' => 'Pengeluaran',
                            ])
                            ->required()
                            ->inline()
                            ->live(),
                    ]),

                Section::make('Detail Transaksi')
                    ->schema([
                        TextInput::make('judul')
                            ->label('Judul / Nama Transaksi')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Pembayaran listrik bulan Maret'),

                        Select::make('kategori')
                            ->label('Kategori')
                            ->required()
                            ->native(false)
                            ->options(function ($get) {
                                return $get('jenis') === 'pengeluaran'
                                    ? Keuangan::$kategoriPengeluaran
                                    : Keuangan::$kategoriPemasukan;
                            })
                            ->placeholder('Pilih kategori'),

                        TextInput::make('jumlah')
                            ->label('Jumlah (Rp)')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->prefix('Rp')
                            ->placeholder('Contoh: 500000'),

                        DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d M Y'),
                    ])
                    ->columns(2),

                Section::make('Keterangan & Bukti')
                    ->schema([
                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(3)
                            ->placeholder('Keterangan tambahan (opsional)')
                            ->columnSpanFull(),

                        FileUpload::make('bukti')
                            ->label('Upload Bukti / Nota')
                            ->image()
                            ->directory('keuangan/bukti')
                            ->maxSize(2048)
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

                TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn (string $state) => match ($state) {
                        'pemasukan'   => 'success',
                        'pengeluaran' => 'danger',
                        default       => 'gray',
                    })
                    ->icon(fn (string $state) => match ($state) {
                        'pemasukan'   => Heroicon::OutlinedArrowTrendingUp,
                        'pengeluaran' => Heroicon::OutlinedArrowTrendingDown,
                        default       => null,
                    }),

                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                TextColumn::make('judul')
                    ->label('Keterangan')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->judul),

                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->formatStateUsing(function ($state, $record) {
                        $prefix = $record->jenis === 'pemasukan' ? '+ ' : '- ';
                        return $prefix . 'Rp ' . number_format($state, 0, ',', '.');
                    })
                    ->color(fn ($record) => $record->jenis === 'pemasukan' ? 'success' : 'danger')
                    ->weight('bold')
                    ->sortable(),

                ImageColumn::make('bukti')
                    ->label('Bukti')
                    ->circular()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Dicatat oleh')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('tanggal', 'desc')
            ->filters([
                SelectFilter::make('jenis')
                    ->label('Jenis')
                    ->options([
                        'pemasukan'   => 'Pemasukan',
                        'pengeluaran' => 'Pengeluaran',
                    ]),

                SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options(
                        array_merge(Keuangan::$kategoriPemasukan, Keuangan::$kategoriPengeluaran)
                    ),

                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('dari')->label('Dari Tanggal')->native(false)->displayFormat('d M Y'),
                        DatePicker::make('sampai')->label('Sampai Tanggal')->native(false)->displayFormat('d M Y'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['dari'], fn ($q) => $q->whereDate('tanggal', '>=', $data['dari']))
                            ->when($data['sampai'], fn ($q) => $q->whereDate('tanggal', '<=', $data['sampai']));
                    })
                    ->columns(2),
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
            ->emptyStateHeading('Belum ada transaksi keuangan')
            ->emptyStateDescription('Klik tombol "Tambah Transaksi" untuk mencatat pemasukan atau pengeluaran.')
            ->emptyStateIcon(Heroicon::OutlinedBanknotes);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKeuangans::route('/'),
            'create' => Pages\CreateKeuangan::route('/create'),
            'view'   => Pages\ViewKeuangan::route('/{record}'),
            'edit'   => Pages\EditKeuangan::route('/{record}/edit'),
        ];
    }
}
