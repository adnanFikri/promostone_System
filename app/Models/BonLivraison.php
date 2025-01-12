<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BonLivraison extends Model
{
    
    use HasFactory;

    protected $table = 'bon_livraisons';
    protected $fillable = ['no_bl','livree','userName'];

    public function sales()
{
    return $this->hasMany(Sale::class, 'no_bl', 'no_bl');
}

public function paymentStatuses()
{
    return $this->hasMany(PaymentStatus::class, 'no_bl', 'no_bl');
}


}
