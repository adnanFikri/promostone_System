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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); 
            $table->string('name'); 
            $table->string('type'); 
            $table->string('category'); 
            $table->decimal('unit_price', 10, 2); 
            $table->integer('quantity'); 
            $table->string('color'); 
            $table->datetime('inventory_date'); 
            $table->datetime('update_date'); 
            $table->string('product_code')->unique(); 
            $table->string('image_path')->nullable(); 
            $table->string('user-name')->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};