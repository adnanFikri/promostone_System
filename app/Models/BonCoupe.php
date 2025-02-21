<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonCoupe extends Model
{
    protected $fillable = ['no_bl','coupe','finition', 'date_coupe', 'date_encours','print_nbr','userName'];
    
    public function sales()
    {
        return $this->hasMany(Sale::class, 'no_bl', 'no_bl');
    }
    
    public function paymentStatuses()
    {
        return $this->hasMany(PaymentStatus::class, 'no_bl', 'no_bl');
    }
}
