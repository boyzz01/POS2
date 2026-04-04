<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::active()->with('gudang');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('merk', 'like', "%{$search}%")
                  ->orWhere('ukuran', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->byKategori($request->kategori);
        }

        $products = $query->orderBy('merk')->paginate(12)->withQueryString();

        $categories = Product::active()
            ->whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori')
            ->sort()
            ->values();

        return view('catalog.index', compact('products', 'categories'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->when($product->kategori, fn ($q) => $q->where('kategori', $product->kategori))
            ->take(4)
            ->get();

        return view('catalog.show', compact('product', 'related'));
    }
}
