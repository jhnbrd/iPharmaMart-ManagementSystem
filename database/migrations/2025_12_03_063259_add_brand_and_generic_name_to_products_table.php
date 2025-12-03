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
            // Add brand_name for both pharmacy and mini_mart products
            $table->string('brand_name')->nullable()->after('name');

            // Add generic_name only for pharmacy products (nullable)
            $table->string('generic_name')->nullable()->after('brand_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['brand_name', 'generic_name']);
        });
    }
};
