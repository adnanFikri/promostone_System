<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // ---- old methode for old header ----
//     public function up()
// {
//     Schema::create('sales', function (Blueprint $table) {
//         $table->id();
//         $table->integer('bln')->nullable();  // Unique BL N identifier
//         $table->integer('annee')->nullable();
//         $table->date('date')->nullable();
//         $table->string('ref_produit')->nullable();
//         $table->string('produit')->nullable();
//         $table->decimal('prix_unitaire', 10, 2)->nullable();
//         $table->decimal('longueur', 10, 3)->nullable();
//         $table->decimal('largeur', 10, 3)->nullable();
//         $table->decimal('nbr', 10, 3)->nullable();
//         $table->decimal('montant', 10, 2)->nullable();
//         $table->string('code_client')->nullable();
//         $table->timestamps();
//     });
// }

// --- new method for new header -----

public function up()
{
    Schema::create('sales', function (Blueprint $table) {
        $table->id(); // Primary key
        $table->integer('no_bl')->nullable();
        $table->integer('annee')->nullable();
        $table->date('date')->nullable();
        $table->string('code_client')->nullable(); // Foreign key
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
    
        // Define foreign key constraint
        $table->foreign('code_client')->references('code_client')->on('clients')->onDelete('set null');
    });
    
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
