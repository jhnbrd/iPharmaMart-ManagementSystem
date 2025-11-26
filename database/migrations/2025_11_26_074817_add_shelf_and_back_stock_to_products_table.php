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
            // Remove the single stock column
            $table->dropColumn('stock');

            // Add shelf and back stock columns
            $table->integer('shelf_stock')->default(0)->after('barcode');
            $table->integer('back_stock')->default(0)->after('shelf_stock');

            // Remove expiry_date from products as it will be tracked per batch
            $table->dropColumn('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['shelf_stock', 'back_stock']);
            $table->integer('stock')->default(0);
            $table->date('expiry_date')->nullable();
        });
    }
};
