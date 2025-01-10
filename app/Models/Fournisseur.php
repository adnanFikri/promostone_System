<?php

namespace App\Models;

use App\Models\Achat\Achat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fournisseur extends Model
{
    use HasFactory;
    protected $fillable = [
        'raison',
        'phone',
        'address',
    ];

    public function achats()
    {
        return $this->hasMany(Achat::class, 'id_fournisseur', 'id');
    }
}
