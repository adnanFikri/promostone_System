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
        Schema::create('achat_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('no_bl')->nullable(); // Add BL number
            $table->string('id_fournisseur'); // Foreign key to clients
            $table->string('name_fournisseur')->nullable();
            $table->dateTime('date_bl')->nullable();
            $table->decimal('montant_total', 10, 2)->default(0); // Total amount owed (sum of all sales amounts)
            $table->decimal('montant_payed', 10, 2)->default(0); // Total amount paid by the client
            $table->decimal('montant_restant', 10, 2)->default(0); // Remaining balance to be paid
            // $table->string('destination')->nullable();
            // $table->string('commerçant')->nullable();
            // $table->string('tel-commerçant')->nullable();
            // $table->date('date-echeance')->nullable();
            // $table->date('chef-atelier')->nullable();
            $table->string('user-name')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achat_statuses');
    }
};
