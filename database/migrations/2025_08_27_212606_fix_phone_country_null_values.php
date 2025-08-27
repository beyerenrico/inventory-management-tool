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
        // Update any NULL phone_country values to default '+1'
        \DB::table('distributors')
            ->whereNull('phone_country')
            ->update(['phone_country' => '+1']);
        
        // Make phone_country not nullable
        Schema::table('distributors', function (Blueprint $table) {
            $table->string('phone_country', 10)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->string('phone_country', 10)->nullable()->change();
        });
    }
};
