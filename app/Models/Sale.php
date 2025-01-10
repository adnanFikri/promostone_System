<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_bl',
        'annee',
        'date',
        'ref_produit',
        'produit',
        'prix_unitaire',
        'longueur',
        'largeur',
        'nbr',
        'qte',
        'montant',
        'mode',
        'code_client',
        'user-name',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'code_client', 'code_client');
    }

    public function sales()
{
    return $this->hasMany(Sale::class, 'no_bl', 'no_bl');
}

public function paymentStatuses()
{
    return $this->hasMany(PaymentStatus::class, 'no_bl', 'no_bl');
}

}
