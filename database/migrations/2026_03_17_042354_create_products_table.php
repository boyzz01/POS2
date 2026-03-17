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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('foto')->nullable();
            $table->string('merk');
            $table->string('ukuran');
            $table->unsignedInteger('pcs_per_karton');
            $table->unsignedInteger('jumlah_masuk')->default(0)->comment('dalam karton');
            $table->string('supplier')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
