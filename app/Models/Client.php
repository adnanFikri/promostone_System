<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'code_client',
        'name',
        'phone',
        'type',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class, 'code_client', 'code_client');
    }
}