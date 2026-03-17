<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Ubah Password Akun</x-slot>
        <x-slot name="description">Pastikan password baru minimal 8 karakter.</x-slot>

        <form wire:submit="simpan" class="space-y-6">
            {{ $this->form }}

            <div class="flex justify-end" style="margin-top: 20px">
                <x-filament::button type="submit" icon="heroicon-m-lock-closed">
                    Simpan Password
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-panels::page>
