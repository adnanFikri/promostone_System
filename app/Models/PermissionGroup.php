<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermissionGroup extends Model
{
    use HasFactory;

    protected $table = 'permission_groups';
    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'group_id'); // Assuming 'group_id' is the foreign key in the permissions table
    }
}
