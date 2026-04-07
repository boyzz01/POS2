<?php

namespace App\Livewire;

use App\Models\Gudang;
use Livewire\Component;

class GudangSelector extends Component
{
    public ?int $activeGudangId = null;
    public bool $locked = false;

    public function mount(): void
    {
        $user = auth()->user();

        if ($user && $user->gudang_id) {
            // User terikat ke gudang tertentu — lock & force set session
            $this->locked = true;
            $this->activeGudangId = $user->gudang_id;
            session(['active_gudang_id' => $user->gudang_id]);
        } else {
            $this->activeGudangId = session('active_gudang_id');
        }
    }

    public function setGudang(?int $id): void
    {
        if ($this->locked) return;

        $this->activeGudangId = $id;
        session(['active_gudang_id' => $id]);
        $this->dispatch('gudang-changed', gudangId: $id);
    }

    public function render()
    {
        return view('livewire.gudang-selector', [
            'gudangs' => Gudang::where('aktif', true)->orderBy('nama')->get(),
            'active'  => $this->activeGudangId ? Gudang::find($this->activeGudangId) : null,
        ]);
    }
}
