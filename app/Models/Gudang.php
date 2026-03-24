<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    protected $fillable = [
        'nama',
        'tipe',
        'kode',
        'lokasi',
        'penanggung_jawab',
        'keterangan',
        'aktif',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
