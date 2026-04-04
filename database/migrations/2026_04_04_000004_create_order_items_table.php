<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            // nullable: if product is deleted, order history is preserved via snapshots
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            // Snapshots so order history is preserved even if product data changes
            $table->string('product_name');
            $table->string('product_size');
            $table->unsignedBigInteger('price_per_unit');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
