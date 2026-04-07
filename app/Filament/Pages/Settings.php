<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?string $title = 'Pengaturan';

    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public function mount(): void
    {
        $this->form->fill([
            'bank_name'    => Setting::get('bank_name', config('payment.bank_name')),
            'bank_account' => Setting::get('bank_account', config('payment.bank_account')),
            'bank_holder'  => Setting::get('bank_holder', config('payment.bank_holder')),
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Rekening Pembayaran')
                    ->description('Informasi ini akan ditampilkan ke customer saat checkout.')
                    ->schema([
                        TextInput::make('bank_name')
                            ->label('Nama Bank')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Contoh: BCA'),

                        TextInput::make('bank_account')
                            ->label('Nomor Rekening')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('Contoh: 1234567890'),

                        TextInput::make('bank_holder')
                            ->label('Atas Nama')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: PT Supplier MBG'),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function simpan(): void
    {
        $data = $this->form->getState();

        Setting::set('bank_name', $data['bank_name']);
        Setting::set('bank_account', $data['bank_account']);
        Setting::set('bank_holder', $data['bank_holder']);

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }
}
