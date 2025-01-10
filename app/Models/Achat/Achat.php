<?php

namespace App\Models\Achat;

use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achat extends Model
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
        'mode',
        'montant',
        'user-name',
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur', 'id');
    }
}
