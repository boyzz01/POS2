<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Replaced: users table stays clean for admin-only use.
 * Customers have their own separate table (see 2026_04_04_000006).
 * This migration is intentionally empty/no-op.
 */
return new class extends Migration
{
    public function up(): void
    {
        // No changes to users table — all users are admins.
        // Customer data lives in the `customers` table.
    }

    public function down(): void {}
};
