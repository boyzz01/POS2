<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TransaksiResource;
use App\Models\Product;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApiTransaksiController extends Controller
{
    /**
     * Riwayat transaksi kasir yang login.
     * Optional: ?per_page=15 (default 20), ?tanggal=2024-01-01
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $transaksi = Transaksi::with(['items', 'gudang'])
            ->where('kasir_id', $user->id)
            ->when($user->gudang_id, fn ($q) => $q->where('gudang_id', $user->gudang_id))
            ->when($request->tanggal, fn ($q) => $q->whereDate('created_at', $request->tanggal))
            ->orderByDesc('created_at')
            ->paginate($request->input('per_page', 20));

        return response()->json([
            'data'       => TransaksiResource::collection($transaksi->items()),
            'pagination' => [
                'current_page' => $transaksi->currentPage(),
                'last_page'    => $transaksi->lastPage(),
                'per_page'     => $transaksi->perPage(),
                'total'        => $transaksi->total(),
            ],
        ]);
    }

    /**
     * Detail satu transaksi.
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $user      = $request->user();
        $transaksi = Transaksi::with(['items', 'kasir', 'gudang'])
            ->where('kasir_id', $user->id)
            ->findOrFail($id);

        return response()->json([
            'data' => new TransaksiResource($transaksi),
        ]);
    }

    /**
     * Buat transaksi baru (checkout).
     *
     * Body JSON:
     * {
     *   "nama_pembeli": "Budi",
     *   "metode_pembayaran": "tunai",
     *   "total_bayar": 150000,
     *   "catatan": "...",        // optional
     *   "items": [
     *     { "product_id": 1, "jumlah": 2 },
     *     { "product_id": 5, "jumlah": 1 }
     *   ]
     * }
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'nama_pembeli'      => 'required|string|max:255',
            'metode_pembayaran' => 'required|string|in:tunai,transfer,qris,debit,kredit',
            'total_bayar'       => 'required|integer|min:0',
            'catatan'           => 'nullable|string|max:500',
            'items'             => 'required|array|min:1',
            'items.*.product_id'=> 'required|integer|exists:products,id',
            'items.*.jumlah'    => 'required|integer|min:1',
        ]);

        $gudangId = $user->gudang_id;
        if (! $gudangId) {
            return response()->json([
                'message' => 'Kasir tidak terikat gudang. Hubungi admin.',
            ], 422);
        }

        // Hitung total & validasi stok
        $totalHarga = 0;
        $itemsData  = [];

        foreach ($validated['items'] as $item) {
            $product = Product::where('gudang_id', $gudangId)
                ->where('is_active', true)
                ->findOrFail($item['product_id']);

            if ($product->stok_karton < $item['jumlah']) {
                return response()->json([
                    'message' => "Stok {$product->merk} tidak cukup. Tersedia: {$product->stok_karton} karton.",
                    'product_id' => $product->id,
                ], 422);
            }

            $subtotal    = $product->harga_karton * $item['jumlah'];
            $totalHarga += $subtotal;

            $itemsData[] = [
                'product_id'   => $product->id,
                'nama_produk'  => $product->merk,
                'ukuran'       => $product->ukuran,
                'harga_satuan' => $product->harga_karton,
                'jumlah'       => $item['jumlah'],
                'subtotal'     => $subtotal,
            ];
        }

        $totalBayar = (int) $validated['total_bayar'];
        if ($totalBayar < $totalHarga) {
            return response()->json([
                'message'    => 'Uang diterima kurang dari total harga.',
                'total_harga'=> $totalHarga,
                'total_bayar'=> $totalBayar,
            ], 422);
        }

        $transaksi = DB::transaction(function () use ($user, $gudangId, $validated, $totalHarga, $totalBayar, $itemsData) {
            $transaksi = Transaksi::create([
                'kode_transaksi'    => 'TRX-' . strtoupper(Str::random(8)),
                'kasir_id'          => $user->id,
                'gudang_id'         => $gudangId,
                'total_harga'       => $totalHarga,
                'total_bayar'       => $totalBayar,
                'kembalian'         => $totalBayar - $totalHarga,
                'status'            => 'selesai',
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'nama_pembeli'      => $validated['nama_pembeli'],
                'catatan'           => $validated['catatan'] ?? null,
            ]);

            foreach ($itemsData as $item) {
                TransaksiItem::create(array_merge($item, ['transaksi_id' => $transaksi->id]));
            }

            return $transaksi;
        });

        $transaksi->load(['items', 'kasir', 'gudang']);

        return response()->json([
            'message' => 'Transaksi berhasil.',
            'data'    => new TransaksiResource($transaksi),
        ], 201);
    }
}
