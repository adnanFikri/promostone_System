<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create or update SuperAdmin user
        $superAdminUser = User::updateOrCreate(
            ['email' => 'sa@admin.ma'], 
            [
                'name' => 'Adnan',
                'email' => 'sa@admin.ma',
                'password' => Hash::make('SuperMario02'),
                'role' => 'SAdmin',
                'phone' => '00 00 00 00 01',
            ]
        );

        // Assign SuperAdmin role
        $superAdminUser->assignRole('SuperAdmin');

        // Create or update Admin user
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@admin.ma'],
            [
                'name' => 'Admin',
                'email' => 'admin@admin.ma',
                'password' => Hash::make('12121212'),
                'role' => 'Admin',
                'phone' => '00 00 00 00 01',
            ]
        );

        // Assign Admin role
        $adminUser->assignRole('Admin');
        
    }
}
