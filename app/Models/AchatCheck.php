<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AchatCheck extends Model
{
    use HasFactory;

    protected $fillable = ['no_bl', 'id_fournisseur', 'user_name', 'achats_data', 'reglements_data', 'achat_status_data', 'changeDate'];

    protected $casts = [
        'achats_data' => 'array',
        'reglements_data' => 'array',
        'achat_status_data' => 'array',
    ];
}
