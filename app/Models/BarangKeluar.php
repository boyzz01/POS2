<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $fillable = [
        'gudang_id',
        'user_id',
        'tanggal',
        'tujuan',
        'metode_pembayaran',
        'nama_penerima',
        'total_harga',
        'transaksi_id',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(BarangKeluarItem::class);
    }
}
