<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            // Add new phone field
            $table->string('phone')->nullable();
        });
        
        // Migrate existing data - combine country and number
        DB::statement("
            UPDATE distributors 
            SET phone = CASE 
                WHEN phone_number IS NOT NULL AND phone_country IS NOT NULL 
                THEN CONCAT(phone_country, phone_number)
                ELSE NULL 
            END
        ");
        
        Schema::table('distributors', function (Blueprint $table) {
            // Drop old fields
            $table->dropColumn(['phone_country', 'phone_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            // Add back old fields
            $table->string('phone_country')->nullable();
            $table->string('phone_number')->nullable();
            
            // Drop new field
            $table->dropColumn('phone');
        });
    }
};
