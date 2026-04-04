<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(): View
    {
        $items = $this->cart->all();
        $total = $this->cart->total();

        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        if (! $product->is_active || $product->stok_karton <= 0) {
            $message = 'Produk tidak tersedia atau stok habis.';

            return $request->expectsJson()
                ? response()->json(['error' => $message], 422)
                : back()->with('error', $message);
        }

        $this->cart->add($product, (int) $request->quantity);

        $message = "\"{$product->merk}\" berhasil ditambahkan ke keranjang.";

        return $request->expectsJson()
            ? response()->json(['message' => $message, 'count' => $this->cart->count()])
            : back()->with('success', $message);
    }

    public function update(Request $request, int $productId): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:999'],
        ]);

        $this->cart->update($productId, (int) $request->quantity);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(int $productId): RedirectResponse
    {
        $this->cart->remove($productId);

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return redirect(route('cart.index'))->with('success', 'Keranjang dikosongkan.');
    }
}
