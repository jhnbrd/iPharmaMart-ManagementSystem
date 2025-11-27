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
        Schema::table('products', function (Blueprint $table) {
            // Add index for name searches (most common query)
            $table->index('name');

            // Add index for barcode lookups (POS system)
            $table->index('barcode');

            // Composite index for common queries
            $table->index(['product_type', 'category_id']);
            $table->index(['shelf_stock', 'back_stock']);
        });

        Schema::table('customers', function (Blueprint $table) {
            // Add index for name searches
            $table->index('name');

            // Add index for email and phone lookups
            $table->index('email');
            $table->index('phone');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            // Add index for name searches
            $table->index('name');

            // Add index for email lookups
            $table->index('email');
        });

        Schema::table('sales', function (Blueprint $table) {
            // Add composite indexes for common report queries
            $table->index(['created_at', 'payment_method']);
            $table->index(['user_id', 'created_at']);
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            // Add composite index for filtering by product and date
            $table->index(['product_id', 'created_at']);
            $table->index(['type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['barcode']);
            $table->dropIndex(['product_type', 'category_id']);
            $table->dropIndex(['shelf_stock', 'back_stock']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['email']);
            $table->dropIndex(['phone']);
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['email']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['created_at', 'payment_method']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'created_at']);
            $table->dropIndex(['type', 'created_at']);
        });
    }
};
