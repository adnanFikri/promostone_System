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
        Schema::create('payment_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code_client'); // Foreign key to clients
            $table->integer('number_sales')->default(0); // Number of sales made by the client
            $table->decimal('montant_total', 10, 2)->default(0); // Total amount owed (sum of all sales amounts)
            $table->integer('number_paid')->default(0); // Number of payments made by the client
            $table->decimal('payed_total', 10, 2)->default(0); // Total amount paid by the client
            $table->decimal('remaining_balance', 10, 2)->default(0); // Remaining balance to be paid
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
        Schema::dropIfExists('payment_statuses');
    }
};
