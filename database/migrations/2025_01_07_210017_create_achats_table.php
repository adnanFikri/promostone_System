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
        Schema::create('achats', function (Blueprint $table) {
            $table->id();
            $table->integer('no_bl')->nullable();
            $table->integer('annee')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('id_fournisseur')->nullable(); // Foreign key
            $table->string('ref_produit')->nullable();
            $table->string('produit')->nullable();
            $table->decimal('longueur', 10, 3)->nullable();
            $table->decimal('largeur', 10, 3)->nullable();
            $table->decimal('nbr', 10, 3)->nullable();
            $table->decimal('qte', 10, 3)->nullable();
            $table->string('mode')->nullable();
            $table->decimal('prix_unitaire', 10, 2)->nullable();
            $table->decimal('montant', 10, 2)->nullable();
            $table->string('user-name')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achats');
    }
};
