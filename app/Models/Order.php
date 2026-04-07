<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'invoice_number',
        'customer_name',
        'customer_phone',
        'customer_address',
        'notes',
        'is_po',
        'subtotal',
        'shipping_cost',
        'total',
        'status',
        'rejection_reason',
        'bank_name',
        'bank_account',
        'bank_holder',
        'paid_at',
    ];

    protected $casts = [
        'status'  => OrderStatus::class,
        'is_po'   => 'boolean',
        'paid_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function canUploadProof(): bool
    {
        return $this->status->canUploadProof();
    }

    // -------------------------------------------------------------------------
    // Relations
    // -------------------------------------------------------------------------

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentProofs(): HasMany
    {
        return $this->hasMany(PaymentProof::class);
    }

    public function latestPaymentProof(): HasOne
    {
        return $this->hasOne(PaymentProof::class)->latestOfMany();
    }
}
