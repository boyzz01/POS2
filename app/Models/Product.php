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

    protected $appends = ['total_pcs', 'stok_karton'];

    public function getStokKartonAttribute(): int
    {
        $masuk  = $this->barangMasuks()->sum('jumlah');
        $keluar = $this->transaksiItems()
            ->whereHas('transaksi', fn ($q) => $q->where('status', 'selesai'))
            ->sum('jumlah');

        return max(0, $masuk - $keluar);
    }

    public function getTotalPcsAttribute(): int
    {
        return $this->stok_karton * ($this->pcs_per_karton ?? 0);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function barangMasuks()
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function transaksiItems()
    {
        return $this->hasMany(TransaksiItem::class);
    }
}
