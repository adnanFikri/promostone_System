<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonCoupe extends Model
{
    protected $fillable = ['no_bl','coupe','date_coupe','print_nbr'];
    
    public function sales()
    {
        return $this->hasMany(Sale::class, 'no_bl', 'no_bl');
    }
    
    public function paymentStatuses()
    {
        return $this->hasMany(PaymentStatus::class, 'no_bl', 'no_bl');
    }
}