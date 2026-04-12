<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdukResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'merk'          => $this->merk,
            'ukuran'        => $this->ukuran,
            'kategori'      => $this->kategori?->nama,
            'kategori_id'   => $this->kategori_id,
            'harga_karton'  => $this->harga_karton,
            'harga_satuan'  => $this->harga_satuan,
            'pcs_per_karton'=> $this->pcs_per_karton,
            'stok_karton'   => $this->stok_karton,
            'total_pcs'     => $this->total_pcs,
            'foto'          => $this->foto ? asset('storage/' . $this->foto) : null,
            'gudang_id'     => $this->gudang_id,
            'gudang'        => $this->whenLoaded('gudang', fn () => $this->gudang?->nama),
            'is_active'     => $this->is_active,
        ];
    }
}
