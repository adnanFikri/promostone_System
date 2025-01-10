<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'category',
        'unit_price',
        'quantity',
        'color',
        'inventory_date',
        'update_date',
        'product_code',
        'image_path',
        'user-name',
    ];
}
