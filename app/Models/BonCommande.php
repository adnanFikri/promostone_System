<?php

namespace App\Models;

use App\Models\Achat\Achat;
use App\Models\Achat\AchatStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BonCommande extends Model
{
    
    use HasFactory;

    protected $table = 'bon_commandes';
    protected $fillable = ['no_bl','reception','userName'];

    public function achats()
{
    return $this->hasMany(Achat::class, 'no_bl', 'no_bl');
}

public function achatStatuses()
{
    return $this->hasMany(AchatStatus::class, 'no_bl', 'no_bl');
}
}
