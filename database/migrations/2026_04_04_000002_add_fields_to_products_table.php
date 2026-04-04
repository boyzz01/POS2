<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('kategori')->nullable()->after('merk');
            $table->boolean('is_featured')->default(false)->after('keterangan');
            $table->boolean('is_active')->default(true)->after('is_featured');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'is_featured', 'is_active']);
        });
    }
};
