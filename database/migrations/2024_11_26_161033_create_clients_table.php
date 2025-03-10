<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('clients', function (Blueprint $table) {
        $table->id(); // Primary key
        $table->string('code_client')->nullable()->index(); // Add an index to code_client
        $table->string('category')->nullable();
        $table->string('name')->nullable();
        $table->string('phone')->nullable();
        $table->string('type')->nullable();
        $table->string('ice')->nullable();
        $table->string('user-name')->nullable(); 
        
    // adding after merge for reglemnt multi bls logique 
        // $table->decimal('solde_restant', 10, 2)->default(0);
        $table->json('solde_restant')->nullable()->change();

        $table->timestamps();
    });
    
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
