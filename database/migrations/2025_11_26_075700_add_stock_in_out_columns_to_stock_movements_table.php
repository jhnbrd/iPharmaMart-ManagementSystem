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
        Schema::table('stock_movements', function (Blueprint $table) {
            // Add stock_in and stock_out columns
            $table->integer('stock_in')->default(0)->after('type');
            $table->integer('stock_out')->default(0)->after('stock_in');

            // Remove quantity column as it's now replaced by stock_in/stock_out
            $table->dropColumn('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropColumn(['stock_in', 'stock_out']);
            $table->integer('quantity')->after('type');
        });
    }
};
