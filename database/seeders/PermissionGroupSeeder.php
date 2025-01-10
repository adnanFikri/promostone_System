<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            ['name' => 'Sales'],
            ['name' => 'Clients'],
            ['name' => 'PaymentStatus'],
            ['name' => 'Reglements'],
            ['name' => 'Products'],
            ['name' => 'Profile'],
            ['name' => 'Roles'],
            ['name' => 'Permissions'],
            ['name' => 'Users'],
        ];

        DB::table('permission_groups')->insert($groups);
    }
}
