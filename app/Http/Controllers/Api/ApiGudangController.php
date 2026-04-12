<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\GudangResource;
use App\Models\Gudang;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiGudangController extends Controller
{
    /**
     * Info gudang kasir.
     * Kasir terikat gudang → hanya return gudang miliknya.
     * Super admin → return semua gudang aktif.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->gudang_id) {
            $gudangs = Gudang::where('id', $user->gudang_id)->get();
        } else {
            $gudangs = Gudang::where('aktif', true)->orderBy('nama')->get();
        }

        return response()->json([
            'data' => GudangResource::collection($gudangs),
        ]);
    }
}
