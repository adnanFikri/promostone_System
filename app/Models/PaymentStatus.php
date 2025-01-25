<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'payment_statuses'; // Table name should match your migration

    // Mass assignable attributes
    protected $fillable = [
        'no_bl',
        'code_client', 
        'name_client',
        'date_bl',
        'montant_total', 
        'montant_payed', 
        'montant_restant',
        'destination',
        'commerçant',
        'tel-commerçant',
        'date-echeance',
        'chef-atelier',
        'user-name',
        'changeCount'
    ];

    // protected $fillable = ['no_bl', 'code_client', 'montant_payed', 'montant_restant', 'commerçant', 'tel-commerçant', 'date-echeance','user-name'];


    // Defining the relationship with the Client model (assuming a Client model exists)
    public function client()
    {
        return $this->belongsTo(Client::class, 'code_client', 'code_client');
    }

    // Optionally, add any helpful methods for this model
    public function updatePaymentStatus($salesAmount, $paymentsMade, $totalPaid, $remainingBalance)
    {
        $this->number_sales = $salesAmount;
        $this->montant_total = $salesAmount;
        $this->number_paid = $paymentsMade;
        $this->payed_total = $totalPaid;
        $this->remaining_balance = $remainingBalance;
        $this->save();
    }

    public function bonLivraison()
{
    return $this->belongsTo(BonLivraison::class, 'no_bl', 'no_bl');
}

}
