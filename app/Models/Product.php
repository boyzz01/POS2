<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'foto',
        'merk',
        'kategori_id',
        'ukuran',
        'pcs_per_karton',
        'harga_karton',
        'jumlah_masuk',
        'supplier',
        'gudang_id',
        'keterangan',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
    ];

    protected $appends = ['total_pcs', 'stok_karton'];

    public function getStokKartonAttribute(): int
    {
        $masuk        = $this->barangMasuks()->sum('jumlah');
        $keluarPOS    = $this->transaksiItems()
            ->whereHas('transaksi', fn ($q) => $q->where('status', 'selesai'))
            ->sum('jumlah');
        $keluarManual = $this->barangKeluarItems()->sum('jumlah');

        return max(0, $masuk - $keluarPOS - $keluarManual);
    }

    public function getTotalPcsAttribute(): int
    {
        return $this->stok_karton * ($this->pcs_per_karton ?? 0);
    }

    /** Price per pcs, computed */
    public function getHargaSatuanAttribute(): int
    {
        if (! $this->pcs_per_karton) {
            return 0;
        }

        return (int) round($this->harga_karton / $this->pcs_per_karton);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    public function scopeByKategori(Builder $query, ?string $kategori): Builder
    {
        if (! $kategori) {
            return $query;
        }

        return $query->where('kategori', $kategori);
    }

    // -------------------------------------------------------------------------
    // Relations
    // -------------------------------------------------------------------------

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function barangMasuks(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function transaksiItems(): HasMany
    {
        return $this->hasMany(TransaksiItem::class);
    }

    public function barangKeluarItems(): HasMany
    {
        return $this->hasMany(BarangKeluarItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
