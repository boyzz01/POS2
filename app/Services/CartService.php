<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

/**
 * Session-based shopping cart.
 *
 * Cart structure in session (key = 'cart'):
 * [
 *   $productId => [
 *     'product_id' => int,
 *     'name'       => string,   // merk + ukuran
 *     'size'       => string,
 *     'price'      => int,      // harga_karton
 *     'quantity'   => int,      // in karton
 *     'foto'       => string|null,
 *   ],
 *   ...
 * ]
 */
class CartService
{
    private const SESSION_KEY = 'cart';

    public function all(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function count(): int
    {
        return array_sum(array_column($this->all(), 'quantity'));
    }

    public function total(): int
    {
        return array_sum(array_map(
            fn ($item) => $item['price'] * $item['quantity'],
            $this->all()
        ));
    }

    public function add(Product $product, int $quantity = 1): void
    {
        if ($product->stok_karton <= 0) {
            return;
        }

        $cart       = $this->all();
        $productId  = $product->id;
        $maxAllowed = $product->stok_karton;

        if (isset($cart[$productId])) {
            $newQty              = min($cart[$productId]['quantity'] + $quantity, $maxAllowed);
            $cart[$productId]['quantity'] = $newQty;
            $cart[$productId]['stok']     = $maxAllowed;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'name'       => $product->merk,
                'size'       => $product->ukuran,
                'price'      => $product->harga_karton,
                'quantity'   => min($quantity, $maxAllowed),
                'stok'       => $maxAllowed,
                'foto'       => $product->foto,
            ];
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->all();

        if (! isset($cart[$productId])) {
            return;
        }

        if ($quantity <= 0) {
            $this->remove($productId);

            return;
        }

        $cart[$productId]['quantity'] = $quantity;
        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->all();
        unset($cart[$productId]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function getQuantity(int $productId): int
    {
        return $this->all()[$productId]['quantity'] ?? 0;
    }

    /** Set exact quantity — adds if new, updates if existing, removes if qty <= 0. */
    public function set(Product $product, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->remove($product->id);
            return;
        }

        $cart      = $this->all();
        $capped    = min($quantity, $product->stok_karton);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $capped;
            $cart[$product->id]['stok']     = $product->stok_karton;
        } else {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'name'       => $product->merk,
                'size'       => $product->ukuran,
                'price'      => $product->harga_karton,
                'quantity'   => $capped,
                'stok'       => $product->stok_karton,
                'foto'       => $product->foto,
            ];
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    public function isEmpty(): bool
    {
        return empty($this->all());
    }
}
