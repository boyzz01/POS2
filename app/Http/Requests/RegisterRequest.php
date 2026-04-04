<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_sppg'      => ['required', 'string', 'max:255'],
            'alamat_sppg'    => ['required', 'string', 'max:1000'],
            'phone'          => ['required', 'string', 'max:20'],
            'foto_dashboard' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:customers,email'],
            'password'       => ['required', 'string', 'confirmed', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_sppg.required'      => 'Nama SPPG wajib diisi.',
            'alamat_sppg.required'    => 'Alamat SPPG wajib diisi.',
            'phone.required'          => 'Nomor WA Ka. Purchasing wajib diisi.',
            'foto_dashboard.required' => 'Foto dashboard SPPG wajib diupload.',
            'foto_dashboard.file'     => 'File tidak valid.',
            'foto_dashboard.mimes'    => 'File harus berformat JPG, PNG, atau PDF.',
            'foto_dashboard.max'      => 'Ukuran file maksimal 5 MB.',
            'email.required'          => 'Email wajib diisi.',
            'email.email'             => 'Format email tidak valid.',
            'email.unique'            => 'Email sudah terdaftar.',
            'password.required'       => 'Password wajib diisi.',
            'password.confirmed'      => 'Konfirmasi password tidak cocok.',
            'password.min'            => 'Password minimal 8 karakter.',
        ];
    }
}
