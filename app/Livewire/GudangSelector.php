<?php

namespace App\Livewire;

use App\Models\Gudang;
use Livewire\Component;

class GudangSelector extends Component
{
    public ?int $activeGudangId = null;

    public function mount(): void
    {
        $this->activeGudangId = session('active_gudang_id');
    }

    public function setGudang(?int $id): void
    {
        $this->activeGudangId = $id;
        session(['active_gudang_id' => $id]);
        $this->dispatch('gudang-changed', gudangId: $id);
    }

    public function render()
    {
        return view('livewire.gudang-selector', [
            'gudangs' => Gudang::where('aktif', true)->orderBy('nama')->get(),
            'active' => $this->activeGudangId ? Gudang::find($this->activeGudangId) : null,
        ]);
    }
}
