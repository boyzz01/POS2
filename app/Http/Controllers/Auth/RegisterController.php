<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $fotoDashboard = null;
        if ($request->hasFile('foto_dashboard')) {
            $file = $request->file('foto_dashboard');
            $fotoDashboard = $file->storeAs(
                'sppg_dashboard',
                Str::uuid() . '.' . $file->extension(),
                'local'
            );
        }

        $customer = Customer::create([
            'name'           => $request->nama_sppg,
            'nama_sppg'      => $request->nama_sppg,
            'alamat_sppg'    => $request->alamat_sppg,
            'phone'          => $request->phone,
            'foto_dashboard' => $fotoDashboard,
            'email'          => $request->email,
            'password'       => $request->password, // auto-hashed via cast
        ]);

        Auth::guard('customer')->login($customer);

        $request->session()->regenerate();

        $intended = $request->session()->pull('url.intended', route('home'));

        return redirect($intended);
    }
}
