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
        Schema::table('sales', function (Blueprint $table) {
            $table->string('card_bank_name')->nullable()->after('reference_number');
            $table->string('card_holder_name')->nullable()->after('card_bank_name');
            $table->string('card_last_four')->nullable()->after('card_holder_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['card_bank_name', 'card_holder_name', 'card_last_four']);
        });
    }
};
