<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class GantiPassword extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Ganti Password';

    protected string $view = 'filament.pages.ganti-password';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('password_lama')
                    ->label('Password Lama')
                    ->password()
                    ->revealable()
                    ->required(),

                TextInput::make('password_baru')
                    ->label('Password Baru')
                    ->password()
                    ->revealable()
                    ->required()
                    ->rule(Password::min(8))
                    ->different('password_lama'),

                TextInput::make('password_baru_confirmation')
                    ->label('Konfirmasi Password Baru')
                    ->password()
                    ->revealable()
                    ->required()
                    ->same('password_baru'),
            ])
            ->statePath('data');
    }

    public function simpan(): void
    {
        $data = $this->form->getState();

        if (! Hash::check($data['password_lama'], auth()->user()->password)) {
            Notification::make()
                ->title('Password lama salah!')
                ->danger()
                ->send();

            $this->addError('data.password_lama', 'Password lama tidak sesuai.');
            return;
        }

        auth()->user()->update([
            'password' => Hash::make($data['password_baru']),
        ]);

        $this->form->fill();

        Notification::make()
            ->title('Password berhasil diubah!')
            ->success()
            ->send();
    }
}
