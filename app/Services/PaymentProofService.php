<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\PaymentProof;
use App\Notifications\PaymentUploadedNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentProofService
{
    /**
     * Store uploaded proof file and update order status.
     * Files are stored in the private local disk — NOT publicly accessible.
     */
    public function upload(Order $order, UploadedFile $file): PaymentProof
    {
        $extension = $file->getClientOriginalExtension();
        $filename  = Str::uuid() . '.' . $extension;
        $path      = 'payment_proofs/' . $filename;

        Storage::disk('local')->put($path, $file->get());

        // Remove old proof files to save storage (keep DB record for history)
        $order->paymentProofs->each(function (PaymentProof $old) {
            if ($old->fileExists()) {
                Storage::disk('local')->delete($old->file_path);
            }
            $old->delete();
        });

        $proof = PaymentProof::create([
            'order_id'          => $order->id,
            'file_path'         => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size'         => $file->getSize(),
            'mime_type'         => $file->getMimeType(),
        ]);

        $order->update(['status' => OrderStatus::PaymentUploaded]);

        try {
            $order->customer->notify(new PaymentUploadedNotification($order));
        } catch (\Throwable) {
            // Silent fail
        }

        return $proof;
    }

    /**
     * Stream private file to browser (requires authorization check in controller).
     */
    public function streamProof(PaymentProof $proof): StreamedResponse
    {
        abort_unless($proof->fileExists(), 404, 'File tidak ditemukan.');

        return Storage::disk('local')->response($proof->file_path, $proof->original_filename);
    }
}
