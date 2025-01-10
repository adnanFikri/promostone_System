<?php

namespace App\Models\Achat;

use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Model;

class Achatreglement extends Model
{
    protected $fillable = [
        'no_bl', 
        'id_fournisseur', 
        'nom_fournisseur', 
        'montant', 
        'date', 
        'type_pay',
        'reference_chq',
        'date_chq',
        'user-name',
    ];

    // Defining the relationship with the Client model (assuming a Client model exists)
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'code_client', 'id');
    }
}
