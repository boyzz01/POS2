<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kategoris')) {
            Schema::create('kategoris', function (Blueprint $table) {
                $table->id();
                $table->string('nama')->unique();
                $table->timestamps();
            });
        }

        // Migrate existing kategori string values (only if kategori column still exists)
        if (Schema::hasColumn('products', 'kategori')) {
            $existing = DB::table('products')
                ->whereNotNull('kategori')
                ->where('kategori', '!=', '')
                ->distinct()
                ->pluck('kategori');

            foreach ($existing as $nama) {
                DB::table('kategoris')->insertOrIgnore(['nama' => $nama, 'created_at' => now(), 'updated_at' => now()]);
            }
        }

        if (!Schema::hasColumn('products', 'kategori_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('kategori_id')->nullable()->after('merk')->constrained('kategoris')->nullOnDelete();
            });
        }

        // Map existing string values to IDs
        if (Schema::hasColumn('products', 'kategori')) {
            DB::table('products')->whereNotNull('kategori')->orderBy('id')->each(function ($product) {
                $kategori = DB::table('kategoris')->where('nama', $product->kategori)->first();
                if ($kategori) {
                    DB::table('products')->where('id', $product->id)->update(['kategori_id' => $kategori->id]);
                }
            });

            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('kategori');
            });
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('kategori')->nullable()->after('merk');
        });

        // Restore string values from relation
        DB::table('products')->whereNotNull('kategori_id')->each(function ($product) {
            $kategori = DB::table('kategoris')->where('id', $product->kategori_id)->first();
            if ($kategori) {
                DB::table('products')->where('id', $product->id)->update(['kategori' => $kategori->nama]);
            }
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });

        Schema::dropIfExists('kategoris');
    }
};
