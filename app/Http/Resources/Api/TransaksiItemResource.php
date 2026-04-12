<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'product_id'   => $this->product_id,
            'nama_produk'  => $this->nama_produk,
            'ukuran'       => $this->ukuran,
            'harga_satuan' => $this->harga_satuan,
            'jumlah'       => $this->jumlah,
            'subtotal'     => $this->subtotal,
        ];
    }
}
