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
        Schema::table('keuangans', function (Blueprint $table) {
            $table->boolean('reminder_aktif')->default(false)->after('keterangan');
            $table->unsignedInteger('reminder_hari')->nullable()->after('reminder_aktif');
            $table->date('reminder_tanggal')->nullable()->after('reminder_hari');
            $table->boolean('reminder_selesai')->default(false)->after('reminder_tanggal');
        });
    }

    public function down(): void
    {
        Schema::table('keuangans', function (Blueprint $table) {
            $table->dropColumn(['reminder_aktif', 'reminder_hari', 'reminder_tanggal', 'reminder_selesai']);
        });
    }
};
