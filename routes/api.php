<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiGudangController;
use App\Http\Controllers\Api\ApiProdukController;
use App\Http\Controllers\Api\ApiTransaksiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Kasir Android App
|--------------------------------------------------------------------------
*/

// Public: login
Route::post('/login', [ApiAuthController::class, 'login']);

// Protected: hanya kasir & super_admin
Route::middleware(['auth:sanctum', 'kasir.only'])->group(function () {

    // Auth
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/me',      [ApiAuthController::class, 'me']);

    // Gudang
    Route::get('/gudang', [ApiGudangController::class, 'index']);

    // Produk
    Route::get('/produk',        [ApiProdukController::class, 'index']);
    Route::get('/produk/search', [ApiProdukController::class, 'search']);
    Route::get('/produk/{id}',   [ApiProdukController::class, 'show']);

    // Transaksi
    Route::get('/transaksi',        [ApiTransaksiController::class, 'index']);
    Route::post('/transaksi',       [ApiTransaksiController::class, 'store']);
    Route::get('/transaksi/{id}',   [ApiTransaksiController::class, 'show']);
});
