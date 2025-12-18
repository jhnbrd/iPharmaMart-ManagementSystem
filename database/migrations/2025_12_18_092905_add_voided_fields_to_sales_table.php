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
            $table->boolean('is_voided')->default(false)->after('change_amount');
            $table->timestamp('voided_at')->nullable()->after('is_voided');
            $table->foreignId('voided_by')->nullable()->constrained('users')->after('voided_at');
            $table->text('void_reason')->nullable()->after('voided_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['voided_by']);
            $table->dropColumn(['is_voided', 'voided_at', 'voided_by', 'void_reason']);
        });
    }
};
