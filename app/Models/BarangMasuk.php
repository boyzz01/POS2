<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $fillable = [
        'product_id',
        'gudang_id',
        'user_id',
        'jumlah',
        'supplier',
        'tanggal',
        'keterangan',
        'foto',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
