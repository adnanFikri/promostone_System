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
        Schema::create('sale_checks', function (Blueprint $table) {
                $table->id();
                
                $table->string('no_bl'); 
                $table->string('code_client'); 
                $table->dateTime('changeDate')->nullable(); 
                $table->string('user_name')->nullable(); 

                $table->json('sales_data'); // To store sales data
                $table->json('reglements_data'); // To store reglement data
                $table->json('payment_status_data'); // To store payment status data

                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_checks');
    }
};
