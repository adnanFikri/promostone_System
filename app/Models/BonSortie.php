<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonSortie extends Model
{
    protected $fillable = ['no_bl','sortie','date_sortie','print_nbr','userName'];

    public function sales()
    {
        return $this->hasMany(Sale::class, 'no_bl', 'no_bl');
    }
    
    public function paymentStatuses()
    {
        return $this->hasMany(PaymentStatus::class, 'no_bl', 'no_bl');
    }
}
