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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('nama_sppg')->nullable()->after('name');
            $table->text('alamat_sppg')->nullable()->after('nama_sppg');
            $table->string('foto_dashboard')->nullable()->after('alamat_sppg');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['nama_sppg', 'alamat_sppg', 'foto_dashboard']);
        });
    }
};
