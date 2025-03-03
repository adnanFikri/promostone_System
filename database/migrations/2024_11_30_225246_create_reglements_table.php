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
            $table->string('code_client'); 
            $table->string('nom_client'); 
            $table->decimal('montant', 10, 2); 
            $table->string('mode'); 
            $table->dateTime('date')->default(now()); 
            $table->string('type_pay'); 
            $table->string('reference_chq')->nullable(); 
            $table->date('date_chq')->nullable(); 
            $table->string('user-name')->nullable(); 
            $table->timestamps();

        // adding after merge for reglemnt multi bls logique 
            $table->integer('bls_count')->default(1);
            $table->decimal('montant_total', 10, 2);
            $table->text('bls_list')->nullable();

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
