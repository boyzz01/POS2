<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'foto',
        'merk',
        'ukuran',
        'pcs_per_karton',
        'harga_karton',
        'jumlah_masuk',
        'supplier',
        'gudang_id',
        'keterangan',
    ];

    protected $appends = ['total_pcs'];

    public function getTotalPcsAttribute(): int
    {
        return ($this->jumlah_masuk ?? 0) * ($this->pcs_per_karton ?? 0);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }
}
