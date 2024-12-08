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
        Schema::create('reglements', function (Blueprint $table) {
            $table->id();
            $table->string('no_bl')->nullable();
            $table->string('code_client'); // Foreign key to clients
            $table->string('nom_client'); // Foreign key to clients
            $table->decimal('montant', 10, 2); // Amount paid by the client
            $table->date('date')->default(now()); // Payment date
            $table->string('type_pay'); // Payment type
            $table->string('reference_chq')->nullable(); // Payment type
            $table->date('date_chq')->nullable(); // Payment type
            $table->timestamps();

            // Foreign key constraint (assuming clients table has a column 'code_client')
            $table->foreign('code_client')->references('code_client')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reglements');
    }
};
