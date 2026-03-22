<?php

use App\Exports\ProductExport;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/export/products/excel', function () {
        return Excel::download(new ProductExport, 'produk-' . now()->format('Ymd') . '.xlsx');
    })->name('export.products.excel');

    Route::get('/export/products/pdf', function () {
        $products = Product::with('gudang')->orderBy('merk')->get();
        $pdf = Pdf::loadView('exports.products-pdf', compact('products'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('produk-' . now()->format('Ymd') . '.pdf');
    })->name('export.products.pdf');
});
