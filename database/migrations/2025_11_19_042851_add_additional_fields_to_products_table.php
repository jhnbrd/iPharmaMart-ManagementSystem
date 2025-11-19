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
            $table->string('unit')->default('pcs')->after('price'); // e.g., pcs, box, bottle, pack
            $table->integer('unit_quantity')->default(1)->after('unit'); // quantity per unit
            $table->integer('stock_danger_level')->default(10)->after('low_stock_threshold'); // critical stock level
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['unit', 'unit_quantity', 'stock_danger_level']);
        });
    }
};
