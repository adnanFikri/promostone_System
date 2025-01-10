<?php

namespace App\Models\Achat;

use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Model;

class AchatStatus extends Model
{
    protected $fillable = [
        'no_bl',
        'id_fournisseur', 
        'name_fournisseur',
        'date_bl',
        'montant_total', 
        'montant_payed', 
        'montant_restant',
        // 'destination',
        // 'commerçant',
        // 'tel-commerçant',
        // 'date-echeance',
        'user-name',
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur', 'id');
    }
}
