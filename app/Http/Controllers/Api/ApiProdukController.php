<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProdukResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiProdukController extends Controller
{
    /**
     * List produk berdasarkan gudang kasir.
     * Optional filter: ?kategori_id=1
     */
    public function index(Request $request): JsonResponse
    {
        $user     = $request->user();
        $gudangId = $user->gudang_id;

        $produk = Product::with(['gudang', 'kategori'])
            ->active()
            ->when($gudangId, fn ($q) => $q->where('gudang_id', $gudangId))
            ->when($request->kategori_id, fn ($q) => $q->where('kategori_id', $request->kategori_id))
            ->where('harga_karton', '>', 0)
            ->orderBy('merk')
            ->get();

        return response()->json([
            'data' => ProdukResource::collection($produk),
        ]);
    }

    /**
     * Search produk by merk atau ukuran.
     * ?q=keyword
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => 'required|string|min:1']);

        $user     = $request->user();
        $gudangId = $user->gudang_id;
        $keyword  = $request->q;

        $produk = Product::with(['gudang', 'kategori'])
            ->active()
            ->when($gudangId, fn ($q) => $q->where('gudang_id', $gudangId))
            ->where(fn ($q) => $q
                ->where('merk', 'like', "%{$keyword}%")
                ->orWhere('ukuran', 'like', "%{$keyword}%")
            )
            ->where('harga_karton', '>', 0)
            ->orderBy('merk')
            ->limit(50)
            ->get();

        return response()->json([
            'data' => ProdukResource::collection($produk),
        ]);
    }

    /**
     * Detail satu produk + stok terkini.
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $user = $request->user();

        $produk = Product::with(['gudang', 'kategori'])
            ->when($user->gudang_id, fn ($q) => $q->where('gudang_id', $user->gudang_id))
            ->findOrFail($id);

        return response()->json([
            'data' => new ProdukResource($produk),
        ]);
    }
}
