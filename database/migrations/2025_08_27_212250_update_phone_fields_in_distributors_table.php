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
        Schema::table('distributors', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->string('phone_country', 10)->default('+1')->after('email');
            $table->string('phone_number', 20)->nullable()->after('phone_country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->dropColumn(['phone_country', 'phone_number']);
            $table->string('phone')->nullable()->after('email');
        });
    }
};
