<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PaymentProof extends Model
{
    protected $fillable = [
        'order_id',
        'file_path',
        'original_filename',
        'file_size',
        'mime_type',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    public function fileSizeForHumans(): string
    {
        $kb = $this->file_size / 1024;

        return $kb < 1024
            ? round($kb, 1) . ' KB'
            : round($kb / 1024, 1) . ' MB';
    }

    /** Check whether the underlying file still exists in private storage */
    public function fileExists(): bool
    {
        return Storage::disk('local')->exists($this->file_path);
    }
}
