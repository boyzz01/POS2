<?php

namespace App\Enums;

enum OrderStatus: string
{
    case AwaitingPayment  = 'awaiting_payment';
    case PaymentUploaded  = 'payment_uploaded';
    case Paid             = 'paid';
    case Rejected         = 'rejected';
    case Cancelled        = 'cancelled';
    case Completed        = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::AwaitingPayment => 'Menunggu Pembayaran',
            self::PaymentUploaded => 'Bukti Transfer Dikirim',
            self::Paid            => 'Pembayaran Dikonfirmasi',
            self::Rejected        => 'Pembayaran Ditolak',
            self::Cancelled       => 'Dibatalkan',
            self::Completed       => 'Selesai',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::AwaitingPayment => 'warning',
            self::PaymentUploaded => 'info',
            self::Paid            => 'success',
            self::Rejected        => 'danger',
            self::Cancelled       => 'gray',
            self::Completed       => 'success',
        };
    }

    /** Tailwind CSS badge classes for user-facing views */
    public function badgeClass(): string
    {
        return match ($this) {
            self::AwaitingPayment => 'bg-amber-100 text-amber-800',
            self::PaymentUploaded => 'bg-blue-100 text-blue-800',
            self::Paid            => 'bg-green-100 text-green-800',
            self::Rejected        => 'bg-red-100 text-red-800',
            self::Cancelled       => 'bg-gray-100 text-gray-700',
            self::Completed       => 'bg-emerald-100 text-emerald-800',
        };
    }

    /** User can re-upload proof when in these statuses */
    public function canUploadProof(): bool
    {
        return in_array($this, [self::AwaitingPayment, self::Rejected]);
    }
}
