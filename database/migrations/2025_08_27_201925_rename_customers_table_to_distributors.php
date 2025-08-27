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
        Schema::rename('customers', 'distributors');
        
        // Also update the foreign key column name in orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->renameColumn('customer_id', 'distributor_id');
            $table->foreign('distributor_id')->references('id')->on('distributors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the foreign key changes
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['distributor_id']);
            $table->renameColumn('distributor_id', 'customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
        
        Schema::rename('distributors', 'customers');
    }
};
