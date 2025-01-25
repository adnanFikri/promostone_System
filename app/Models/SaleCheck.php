<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleCheck extends Model
{
    use HasFactory;

    protected $fillable = ['no_bl', 'code_client', 'user_name', 'sales_data', 'reglements_data', 'payment_status_data', 'changeDate'];

    protected $casts = [
        'sales_data' => 'array',
        'reglements_data' => 'array',
        'payment_status_data' => 'array',
    ];
}
