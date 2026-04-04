<?php

use App\Exports\ProductExport;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentProofController;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

// ─────────────────────────────────────────────
// Public storefront
// ─────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('katalog')->name('catalog.')->group(function () {
    Route::get('/', [CatalogController::class, 'index'])->name('index');
    Route::get('/{product}', [CatalogController::class, 'show'])->name('show');
});

// Cart — guest boleh add, checkout wajib login
Route::prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/tambah/{product}', [CartController::class, 'add'])->name('add');
    Route::patch('/set/{product}', [CartController::class, 'set'])->name('set');
    Route::patch('/update/{productId}', [CartController::class, 'update'])->name('update');
    Route::delete('/hapus/{productId}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/kosongkan', [CartController::class, 'clear'])->name('clear');
});

// ─────────────────────────────────────────────
// Customer Authentication (hanya untuk tamu)
// Menggunakan guard 'customer' — terpisah dari admin Filament
// ─────────────────────────────────────────────

Route::middleware('guest:customer')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LogoutController::class, 'logout'])
    ->middleware('auth:customer')
    ->name('logout');

// ─────────────────────────────────────────────
// Customer protected area (wajib login sebagai customer)
// ─────────────────────────────────────────────

Route::middleware(['auth:customer'])->group(function () {
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'store'])->name('store');
    });

    Route::prefix('pesanan-saya')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/pembayaran', [OrderController::class, 'payment'])->name('payment');
        Route::post('/{order}/bukti-transfer', [PaymentProofController::class, 'upload'])
            ->name('payment.upload');
        Route::get('/{order}/bukti-transfer/{proof}', [PaymentProofController::class, 'show'])
            ->name('payment.proof');
    });
});

// ─────────────────────────────────────────────
// Admin exports (web guard — admin only)
// ─────────────────────────────────────────────

Route::middleware(['auth'])->group(function () {
    Route::get('/export/products/excel', function () {
        return Excel::download(new ProductExport, 'produk-' . now()->format('Ymd') . '.xlsx');
    })->name('export.products.excel');

    Route::get('/export/products/pdf', function () {
        $products = Product::with('gudang')->orderBy('merk')->get();
        $pdf      = Pdf::loadView('exports.products-pdf', compact('products'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('produk-' . now()->format('Ymd') . '.pdf');
    })->name('export.products.pdf');
});
