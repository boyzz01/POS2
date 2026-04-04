<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::featured()->active()->with('gudang')->orderBy('merk')->take(4)->get();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::active()->with('gudang')->orderBy('merk')->take(4)->get();
        }

        $categories = Product::active()
            ->whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori')
            ->sort()
            ->values();

        return view('home.index', compact('featuredProducts', 'categories'));
    }
}
