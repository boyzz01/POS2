<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $fillable = [
        'jenis',
        'kategori',
        'judul',
        'jumlah',
        'tanggal',
        'bukti',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public static array $kategoriPemasukan = [
        'Penjualan'  => 'Penjualan',
        'Investasi'  => 'Investasi',
        'Modal'      => 'Modal',
        'Piutang'    => 'Piutang',
        'Lain-lain'  => 'Lain-lain',
    ];

    public static array $kategoriPengeluaran = [
        'Pembelian Barang' => 'Pembelian Barang',
        'Operasional'      => 'Operasional',
        'Gaji Karyawan'    => 'Gaji Karyawan',
        'Sewa'             => 'Sewa',
        'Listrik & Air'    => 'Listrik & Air',
        'Transportasi'     => 'Transportasi',
        'Lain-lain'        => 'Lain-lain',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPemasukan(): bool
    {
        return $this->jenis === 'pemasukan';
    }
}
