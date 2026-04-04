<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barang_keluars', function (Blueprint $table) {
            $table->string('metode_pembayaran')->default('transfer')->after('tujuan');
            $table->string('nama_penerima')->nullable()->after('metode_pembayaran');
            $table->unsignedBigInteger('total_harga')->default(0)->after('nama_penerima');
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksis')->nullOnDelete()->after('total_harga');
        });

        Schema::table('barang_keluar_items', function (Blueprint $table) {
            $table->unsignedBigInteger('harga_satuan')->default(0)->after('jumlah');
            $table->unsignedBigInteger('subtotal')->default(0)->after('harga_satuan');
        });
    }

    public function down(): void
    {
        Schema::table('barang_keluars', function (Blueprint $table) {
            $table->dropForeign(['transaksi_id']);
            $table->dropColumn(['metode_pembayaran', 'nama_penerima', 'total_harga', 'transaksi_id']);
        });

        Schema::table('barang_keluar_items', function (Blueprint $table) {
            $table->dropColumn(['harga_satuan', 'subtotal']);
        });
    }
};
