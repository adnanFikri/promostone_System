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
        Schema::create('bon_coupes', function (Blueprint $table) {
            $table->id();
            $table->string('no_bl')->nullable(); 
            $table->string('coupe')->nullable()->default('Non'); 
            $table->dateTime('date_coupe')->nullable(); 
            $table->dateTime('date_encours')->nullable(); 
            $table->string('finition')->nullable()->default('Non'); 
            $table->integer('print_nbr')->nullable()->default(0); 
            $table->dateTime('print_date')->nullable(); 
            $table->string('userName')->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_coupes');
    }
};
