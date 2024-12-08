<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reglement extends Model
{
    use HasFactory;

    // Specify the table name if it's different from the plural of the model name
    protected $table = 'reglements';

    // Mass assignable attributes
    protected $fillable = [
        'no_bl', 
        'code_client', 
        'nom_client', 
        'montant', 
        'date', 
        'type_pay',
        'reference_chq',
        'date_chq',
    ];

    // Defining the relationship with the Client model (assuming a Client model exists)
    public function client()
    {
        return $this->belongsTo(Client::class, 'code_client', 'code_client');
    }
}
