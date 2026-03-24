<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'kode_transaksi',
        'kasir_id',
        'gudang_id',
        'total_harga',
        'total_bayar',
        'kembalian',
        'status',
        'metode_pembayaran',
        'nama_pembeli',
        'catatan',
    ];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function gudang()
    {
        return $this->belongsTo(\App\Models\Gudang::class);
    }

    public function items()
    {
        return $this->hasMany(TransaksiItem::class);
    }
}
