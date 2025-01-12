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
        Schema::create('bon_sorties', function (Blueprint $table) {
            $table->id();
            $table->string('no_bl')->nullable(); 
            $table->string('sortie')->nullable()->default('Non'); 
            $table->dateTime('date_sortie')->nullable(); 
            $table->integer('print_nbr')->nullable()->default(0); 
            $table->integer('userName')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_sorties');
    }
};
