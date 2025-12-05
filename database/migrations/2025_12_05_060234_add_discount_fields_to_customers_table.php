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
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_senior_citizen')->default(false)->after('address');
            $table->string('senior_citizen_id')->nullable()->after('is_senior_citizen');
            $table->boolean('is_pwd')->default(false)->after('senior_citizen_id');
            $table->string('pwd_id')->nullable()->after('is_pwd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['is_senior_citizen', 'senior_citizen_id', 'is_pwd', 'pwd_id']);
        });
    }
};