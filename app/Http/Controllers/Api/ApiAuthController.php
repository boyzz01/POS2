<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\GudangResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::guard('web')->attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $user = Auth::guard('web')->user();

        if (! ($user->isKasir() || $user->isSuperAdmin())) {
            Auth::guard('web')->logout();
            return response()->json([
                'message' => 'Akses ditolak. Hanya kasir yang dapat login di aplikasi ini.',
            ], 403);
        }

        $token = $user->createToken('kasir-android', ['kasir'])->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'role'      => $user->role,
                'gudang_id' => $user->gudang_id,
                'gudang'    => $user->gudang ? new GudangResource($user->gudang) : null,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil.']);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load('gudang');

        return response()->json([
            'id'        => $user->id,
            'name'      => $user->name,
            'email'     => $user->email,
            'role'      => $user->role,
            'gudang_id' => $user->gudang_id,
            'gudang'    => $user->gudang ? new GudangResource($user->gudang) : null,
        ]);
    }
}
