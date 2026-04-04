<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluarItem extends Model
{
    protected $fillable = [
        'barang_keluar_id',
        'product_id',
        'nama_produk',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
