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
        Schema::create('achatreglements', function (Blueprint $table) {
            $table->id();
            $table->string('no_bl')->nullable();
            $table->integer('id_fournisseur'); 
            $table->string('nom_fournisseur')->nullable(); 
            $table->decimal('montant', 10, 2); 
            $table->date('date')->default(now()); 
            $table->string('type_pay'); 
            $table->string('reference_chq')->nullable(); 
            $table->date('date_chq')->nullable(); 
            $table->string('user-name')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achatreglements');
    }
};
