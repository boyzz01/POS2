<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadPaymentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('customer')->check();
    }

    public function rules(): array
    {
        $maxKb      = config('payment.proof_max_size_kb', 5120);
        $allowedExt = implode(',', config('payment.proof_allowed_ext', ['jpg', 'jpeg', 'png', 'pdf']));

        return [
            'proof' => [
                'required',
                'file',
                "mimes:{$allowedExt}",
                "max:{$maxKb}",
            ],
        ];
    }

    public function messages(): array
    {
        $maxMb = round(config('payment.proof_max_size_kb', 5120) / 1024, 0);

        return [
            'proof.required' => 'File bukti transfer wajib diupload.',
            'proof.file'     => 'Upload harus berupa file.',
            'proof.mimes'    => 'Format file harus JPG, PNG, atau PDF.',
            'proof.max'      => "Ukuran file maksimal {$maxMb} MB.",
        ];
    }
}
