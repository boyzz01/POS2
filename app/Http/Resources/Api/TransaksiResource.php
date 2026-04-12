<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'kode_transaksi'     => $this->kode_transaksi,
            'kasir'              => $this->kasir?->name,
            'gudang'             => $this->gudang?->nama,
            'gudang_id'          => $this->gudang_id,
            'total_harga'        => $this->total_harga,
            'total_bayar'        => $this->total_bayar,
            'kembalian'          => $this->kembalian,
            'status'             => $this->status,
            'metode_pembayaran'  => $this->metode_pembayaran,
            'nama_pembeli'       => $this->nama_pembeli,
            'catatan'            => $this->catatan,
            'items'              => TransaksiItemResource::collection($this->whenLoaded('items')),
            'created_at'         => $this->created_at?->toISOString(),
        ];
    }
}
