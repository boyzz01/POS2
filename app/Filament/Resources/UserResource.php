<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Gudang;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Manajemen Pengguna';

    protected static ?int $navigationSort = 99;

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Akun')->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation) => $operation === 'create')
                    ->placeholder(fn (string $operation) => $operation === 'edit' ? 'Kosongkan jika tidak diubah' : null),
            ])->columns(2),

            Section::make('Role & Akses')->schema([
                Select::make('role')
                    ->label('Role')
                    ->options([
                        'super_admin'   => 'Super Admin',
                        'admin'         => 'Admin Gudang',
                        'kasir'         => 'Kasir',
                        'kepala_gudang' => 'Kepala Gudang',
                        'keuangan'      => 'Keuangan',
                    ])
                    ->default('kasir')
                    ->required()
                    ->live()
                    ->helperText(fn ($state) => match($state) {
                        'super_admin'   => 'Akses penuh ke semua gudang.',
                        'admin'         => 'Akses penuh ke gudang yang ditentukan.',
                        'kasir'         => 'Hanya akses menu Kasir/POS dan Transaksi.',
                        'kepala_gudang' => 'Akses menu Kepala Gudang.',
                        'keuangan'      => 'Hanya akses menu Keuangan.',
                        default         => '',
                    }),

                Select::make('gudang_id')
                    ->label('Gudang')
                    ->options(fn () => Gudang::where('aktif', true)->orderBy('nama')->pluck('nama', 'id'))
                    ->searchable()
                    ->nullable()
                    ->hidden(fn ($get) => $get('role') === 'super_admin')
                    ->required(fn ($get) => in_array($get('role'), ['admin', 'kasir']))
                    ->helperText('Pilih gudang yang dapat diakses.'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'super_admin' => 'danger',
                        'admin'       => 'primary',
                        'kasir'       => 'warning',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'super_admin' => 'Super Admin',
                        'admin'       => 'Admin Gudang',
                        'kasir'       => 'Kasir',
                        default       => $state,
                    }),

                TextColumn::make('gudang.nama')
                    ->label('Gudang')
                    ->badge()
                    ->color('warning')
                    ->default('Semua Gudang')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn (User $record) => $record->id === auth()->id()),
            ])
            ->emptyStateHeading('Belum ada pengguna')
            ->emptyStateIcon(Heroicon::OutlinedUsers);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
