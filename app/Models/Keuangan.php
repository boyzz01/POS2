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
        'gudang_id',
        'reminder_aktif',
        'reminder_hari',
        'reminder_tanggal',
        'reminder_selesai',
    ];

    protected $casts = [
        'tanggal'          => 'date',
        'reminder_tanggal' => 'date',
        'reminder_aktif'   => 'boolean',
        'reminder_selesai' => 'boolean',
    ];

    public function isReminderJatuhTempo(): bool
    {
        return $this->reminder_aktif
            && ! $this->reminder_selesai
            && $this->reminder_tanggal
            && $this->reminder_tanggal->isPast();
    }

    public function sisaHariReminder(): int
    {
        if (! $this->reminder_tanggal) return 0;
        return max(0, now()->startOfDay()->diffInDays($this->reminder_tanggal, false));
    }

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

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function isPemasukan(): bool
    {
        return $this->jenis === 'pemasukan';
    }
}
