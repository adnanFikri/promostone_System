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


        // Create or update Admin user
        $SecretaireUser = User::updateOrCreate(
            ['email' => 'Secretaire@ds.ma'],
            [
                'name' => 'Secretaire',
                'email' => 'Secretaire@ds.ma',
                'password' => Hash::make('ds012345ds'),
                'role' => 'Secretaire',
                'phone' => '00 00 00 00 01',
            ]
        );
        $SecretaireUser->assignRole('Secretaire');


        // Create or update Admin user
        $CaissierUser = User::updateOrCreate(
            ['email' => 'Caissier@ds.ma'],
            [
                'name' => 'Caissier',
                'email' => 'Caissier@ds.ma',
                'password' => Hash::make('ds012345ds'),
                'role' => 'Caissier',
                'phone' => '00 00 00 00 01',
            ]
        );
        $CaissierUser->assignRole('Caissier');


        // Create or update Admin user
        $CommercialUser = User::updateOrCreate(
            ['email' => 'Commercial@ds.ma'],
            [
                'name' => 'Commercial',
                'email' => 'Commercial@ds.ma',
                'password' => Hash::make('ds012345ds'),
                'role' => 'Commercial',
                'phone' => '00 00 00 00 01',
            ]
        );
        $CommercialUser->assignRole('Commercial');


        // Create or update Admin user
        $chefAtelierUser = User::updateOrCreate(
            ['email' => 'chefAtelier@ds.ma'],
            [
                'name' => 'chefAtelier',
                'email' => 'chefAtelier@ds.ma',
                'password' => Hash::make('ds012345ds'),
                'role' => 'chef Atelier',
                'phone' => '00 00 00 00 01',
            ]
        );
        $chefAtelierUser->assignRole('chef Atelier');


        // Create or update Admin user
        $MagasignerUser = User::updateOrCreate(
            ['email' => 'Magasigner@ds.ma'],
            [
                'name' => 'Magasigner',
                'email' => 'Magasigner@ds.ma',
                'password' => Hash::make('ds012345ds'),
                'role' => 'Magasigner',
                'phone' => '00 00 00 00 01',
            ]
        );
        $MagasignerUser->assignRole('Magasigner');


        // Create or update Admin user
        $ControleurUser = User::updateOrCreate(
            ['email' => 'Controleur@ds.ma'],
            [
                'name' => 'Controleur',
                'email' => 'Controleur@ds.ma',
                'password' => Hash::make('ds012345ds'),
                'role' => 'Controleur',
                'phone' => '00 00 00 00 01',
            ]
        );
        $ControleurUser->assignRole('Controleur');
        
    }
}
