<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => 'COUPE C95 P SUR 2',
                'type' => 'DEBITE',
                'category' => 'DEBITGAE',
                'unit_price' => 120.00,
                'quantity' => 497,
                'color' => 'Blanc',
                'product_code' => 'A1',
                'image_path' => 'products/0ros47QAk8uCow9gL1taffwL3k2giB4g5n9q4bJM.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'COUPE CYRAMIC 25P DECORATIF',
                'type' => 'DEBITE',
                'category' => 'ASCALE',
                'unit_price' => 100.00,
                'quantity' => 497,
                'color' => 'Blanc',
                'product_code' => 'A2',
                'image_path' => 'products/M11SqQJBDbgYEddYOSf2FiHwKh3sLc4tsZUnmLA8.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'ML NOIR KHENIFRA TRANCHE',
                'type' => 'TRANCHE',
                'category' => 'MARBRE LOCAL',
                'unit_price' => 280.00,
                'quantity' => 493,
                'color' => 'Noir',
                'product_code' => 'A3',
                'image_path' => 'products/JXE20ocDaaSyYGTbp0ghuHtCcPpiojtydyG7Srvn.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'ESCALIERS SILVIA POLI',
                'type' => 'ESCALIERS',
                'category' => 'MARBRE',
                'unit_price' => 170.00,
                'quantity' => 290,
                'color' => 'Beige',
                'product_code' => 'A4',
                'image_path' => 'products/VmNn3dvNbVQKjbDubAxJRjv23ZNrEw2lAywfLwCl.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'M SILVIA TRANCHE',
                'type' => 'TRANCHE',
                'category' => 'MARBRE',
                'unit_price' => 150.00,
                'quantity' => 458,
                'color' => 'Rose',
                'product_code' => 'A5',
                'image_path' => 'products/ZxvRea37ckRPIvCUDf3ETaFMomuRRSL09JcH9PuY.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
