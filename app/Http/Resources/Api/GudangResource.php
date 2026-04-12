<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GudangResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'nama'             => $this->nama,
            'kode'             => $this->kode,
            'tipe'             => $this->tipe,
            'lokasi'           => $this->lokasi,
            'penanggung_jawab' => $this->penanggung_jawab,
            'aktif'            => $this->aktif,
        ];
    }
}
