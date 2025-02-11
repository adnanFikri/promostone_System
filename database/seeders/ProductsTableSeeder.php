<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $products = [
            // GRANIT
            // ['category' => 'GRANIT','type' => 'TRANCHE', 'name' => 'ROSE PORINO', 'unit_price' => 240.00],
            // ['category' => 'GRANIT','type' => 'TRANCHE', 'name' => 'CREMA JULIA', 'unit_price' => 280.00],
            // ['category' => 'GRANIT','type' => 'TRANCHE', 'name' => 'MONDARIZ', 'unit_price' => 280.00],
            // ['category' => 'GRANIT','type' => 'TRANCHE', 'name' => 'BLANC PERLE', 'unit_price' => 390.00],
            // ['category' => 'GRANIT','type' => 'TRANCHE', 'name' => 'GRIS RAFAYEL', 'unit_price' => 340.00],
            // ['category' => 'GRANIT','type' => 'TRANCHE', 'name' => 'BLANCO MINIOS', 'unit_price' => 320.00],
            // ['category' => 'GRANIT','type' => 'TRANCHE', 'name' => 'GRIS ESPANGOL', 'unit_price' => 340.00],
            // ['category' => 'GRANIT','type' => 'TRANCHE', 'name' => 'GRAND PERLA / GRAND TACHE', 'unit_price' => 420.00],
            // ['category' => 'GRANIT','type' => 'TRANCHE', 'name' => 'ROSAVEL', 'unit_price' => 340.00],
            // ['category' => 'GRANIT','type' => 'TRANCHE', 'name' => 'CREMA CAMELIA', 'unit_price' => 340.00],
            // ['category' => 'GRANIT','type' => 'TRANCHE', 'name' => 'ROUGE BALMORAL', 'unit_price' => 650.00],
            // ['category' => 'GRANIT','type' => 'TRANCHE', 'name' => 'BALTIC BROWN', 'unit_price' => 600.00],
            // ['category' => 'GRANIT','type' => 'TRANCHE', 'name' => 'LABRADOR NOIR', 'unit_price' => 950.00],
            // // MARBRE IMPORTE
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'MARMARA', 'unit_price' => 560.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'SILVIA', 'unit_price' => 160.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'PERLATINO', 'unit_price' => 540.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'CREMA MARFIL', 'unit_price' => 450.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'CREMA GALALA', 'unit_price' => 280.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'CREMA ROYALE', 'unit_price' => 900.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC CARRARE', 'unit_price' => 540.00],
            // // MARBRE LOCALE
            // ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS VIOLET', 'unit_price' => 210.00],
            // ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS BENSLIMANE', 'unit_price' => 210.00],
            // ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS TIFLET', 'unit_price' => 220.00],
            // ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'ROUGE TINGHIR', 'unit_price' => 180.00],
            // ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'BLANC ZAYANE', 'unit_price' => 260.00],
            // ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'NOIR AZILAL', 'unit_price' => 280.00],

            // GRANIT : 27
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'ROSE PORINO', 'unit_price' => 240.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'CREMA JULIA', 'unit_price' => 280.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'MONDARIZ', 'unit_price' => 280.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'BLANC PERLE', 'unit_price' => 390.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'GRIS RAFAYEL', 'unit_price' => 340.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'BLANCO MINIOS', 'unit_price' => 320.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'GRIS ESPANGOL', 'unit_price' => 340.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'GRAND PERLA / GRAND TACHE', 'unit_price' => 420.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'ROSAVEL', 'unit_price' => 340.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'CREMA CAMELIA', 'unit_price' => 340.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'ROUGE BALMORAL', 'unit_price' => 650.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'BALTIC BROWN', 'unit_price' => 600.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'LABRADOR NOIR', 'unit_price' => 950.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'LABRADOR GOLD', 'unit_price' => 950.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'NOIR GALAXY', 'unit_price' => 670.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'NOIR ABSOLUT', 'unit_price' => 670.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'NOIR ANGOLA', 'unit_price' => 650.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'GRANIT NOIR FLAME', 'unit_price' => 950.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'TITANUM GOLD', 'unit_price' => 1500.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'BLANC MONTREAL', 'unit_price' => 1500.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'GRIS ORIENT / HALAYEB', 'unit_price' => 240.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'LABRADOR BLEU', 'unit_price' => 950.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'ROUGE CARMEN', 'unit_price' => 700.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'NEW CREMA CALACATA / STATOUARIO', 'unit_price' => 950.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'NEW CREMA BLANC', 'unit_price' => 900.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'NEW CREMA GRIS', 'unit_price' => 800.00],
            // ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'ASCALE (PRIX POUR CUISINE)', 'unit_price' => 1300.00],

            
            // MARBRE IMPORTE : 22
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'MARMARA', 'unit_price' => 560.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'SILVIA', 'unit_price' => 160.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'PERLATINO', 'unit_price' => 540.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'CREMA MARFIL', 'unit_price' => 450.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'CREMA GALALA', 'unit_price' => 280.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'CREMA ROYALE', 'unit_price' => 900.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC CARRARE', 'unit_price' => 540.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC KAMALPASHA', 'unit_price' => 540.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC SAPHIR', 'unit_price' => 850.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC IBIZA', 'unit_price' => 540.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'ROUGE ALICANTE', 'unit_price' => 540.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'VERT GUATEMALA', 'unit_price' => 540.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'VERT ALPE', 'unit_price' => 540.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC CRYSTAL', 'unit_price' => 600.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'MARRON IMPERIAL', 'unit_price' => 520.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'MARRON LIGHT', 'unit_price' => 450.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'MARRON ROYALE', 'unit_price' => 560.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'ROSE PORTUGAL', 'unit_price' => 520.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC PORTUGAL', 'unit_price' => 560.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC POLARIS', 'unit_price' => 850.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'TOUNDRA GREY', 'unit_price' => 650.00],
            // ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'LILAC', 'unit_price' => 700.00],

                // // MARBRE LOCALE : 28
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS VIOLET', 'unit_price' => 210.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS BENSLIMANE', 'unit_price' => 190.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS TIFLET', 'unit_price' => 220.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'ROUGE TINGHIR', 'unit_price' => 180.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'BLANC ZAYANE', 'unit_price' => 260.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'NOIR AZILAL', 'unit_price' => 280.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'NOIR KHENIFRA', 'unit_price' => 260.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'ROUGE BENSLIMANE', 'unit_price' => 200.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'JAUNE BEJAAD', 'unit_price' => 190.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS OULMES', 'unit_price' => 160.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA BEIGE 2CM POLI OU BOUCHARDE', 'unit_price' => 150.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA GRIS 2CM POLI OU BOUCHARDE', 'unit_price' => 160.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA BEIGE 3CM POLI OU BOUCHARDE', 'unit_price' => 170.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA GRIS 3CM POLI OU BOUCHARDE', 'unit_price' => 190.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA BEIGE 2CM VIELLI', 'unit_price' => 180.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA GRIS 2CM VIELLI', 'unit_price' => 190.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA BEIGE 3CM VIELLI', 'unit_price' => 210.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA GRIS 3CM VIELLI', 'unit_price' => 220.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS SAHARA', 'unit_price' => 200.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'CREMA ATLAS', 'unit_price' => 170.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'VOLUBULIS BRUT', 'unit_price' => 200.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'VOLUBULIS POLI', 'unit_price' => 250.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'VOLUBILIS RESINE', 'unit_price' => 300.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS KHENIFRA', 'unit_price' => 190.00], 
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'ATLAS STONE', 'unit_price' => 400.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'ROUGE AGADIR', 'unit_price' => 260.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'NOIR BOUTFADA', 'unit_price' => 250.00],
//             ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'SAINT LAURENT', 'unit_price' => 450.00],

            // -------------------------------------------------------------------------------------------------------------------------
            // GRANIT : 27
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'ROSE PORINO TRANCHE', 'unit_price' => 240.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'CREMA JULIA TRANCHE', 'unit_price' => 280.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'MONDARIZ TRANCHE', 'unit_price' => 280.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'BLANC PERLE TRANCHE', 'unit_price' => 390.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'GRIS RAFAYEL TRANCHE', 'unit_price' => 340.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'BLANCO MINIOS TRANCHE', 'unit_price' => 320.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'GRIS ESPANGOL TRANCHE', 'unit_price' => 340.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'GRAND PERLA / GRAND TACHE TRANCHE', 'unit_price' => 420.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'ROSAVEL TRANCHE', 'unit_price' => 340.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'CREMA CAMELIA TRANCHE', 'unit_price' => 340.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'ROUGE BALMORAL TRANCHE', 'unit_price' => 650.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'BALTIC BROWN TRANCHE', 'unit_price' => 600.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'LABRADOR NOIR TRANCHE', 'unit_price' => 950.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'LABRADOR GOLD TRANCHE', 'unit_price' => 950.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'NOIR GALAXY TRANCHE', 'unit_price' => 670.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'NOIR ABSOLUT TRANCHE', 'unit_price' => 670.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'NOIR ANGOLA TRANCHE', 'unit_price' => 650.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'GRANIT NOIR FLAME TRANCHE', 'unit_price' => 950.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'TITANUM GOLD TRANCHE', 'unit_price' => 1500.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'BLANC MONTREAL TRANCHE', 'unit_price' => 1500.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'GRIS ORIENT / HALAYEB TRANCHE', 'unit_price' => 240.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'LABRADOR BLEU TRANCHE', 'unit_price' => 950.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'ROUGE CARMEN TRANCHE', 'unit_price' => 700.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'NEW CREMA CALACATA / STATOUARIO TRANCHE', 'unit_price' => 950.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'NEW CREMA BLANC TRANCHE', 'unit_price' => 900.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'NEW CREMA GRIS TRANCHE', 'unit_price' => 800.00],
            ['category' => 'GRANIT', 'type' => 'TRANCHE', 'name' => 'ASCALE (PRIX POUR CUISINE) TRANCHE', 'unit_price' => 1300.00],

            // MARBRE IMPORTE : 22
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'MARMARA TRANCHE', 'unit_price' => 560.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'SILVIA TRANCHE', 'unit_price' => 160.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'PERLATINO TRANCHE', 'unit_price' => 540.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'CREMA MARFIL TRANCHE', 'unit_price' => 450.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'CREMA GALALA TRANCHE', 'unit_price' => 280.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'CREMA ROYALE TRANCHE', 'unit_price' => 900.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC CARRARE TRANCHE', 'unit_price' => 540.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC KAMALPASHA TRANCHE', 'unit_price' => 540.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC SAPHIR TRANCHE', 'unit_price' => 850.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC IBIZA TRANCHE', 'unit_price' => 540.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'ROUGE ALICANTE TRANCHE', 'unit_price' => 540.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'VERT GUATEMALA TRANCHE', 'unit_price' => 540.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'VERT ALPE TRANCHE', 'unit_price' => 540.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC CRYSTAL TRANCHE', 'unit_price' => 600.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'MARRON IMPERIAL TRANCHE', 'unit_price' => 520.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'MARRON LIGHT TRANCHE', 'unit_price' => 450.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'MARRON ROYALE TRANCHE', 'unit_price' => 560.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'ROSE PORTUGAL TRANCHE', 'unit_price' => 520.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC PORTUGAL TRANCHE', 'unit_price' => 560.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'BLANC POLARIS TRANCHE', 'unit_price' => 850.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'TOUNDRA GREY TRANCHE', 'unit_price' => 650.00],
            ['category' => 'MARBRE IMPORTE', 'type' => 'TRANCHE', 'name' => 'LILAC TRANCHE', 'unit_price' => 700.00],


            // MARBRE LOCALE : 28
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS VIOLET TRANCHE', 'unit_price' => 210.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS BENSLIMANE TRANCHE', 'unit_price' => 190.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS TIFLET TRANCHE', 'unit_price' => 220.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'ROUGE TINGHIR TRANCHE', 'unit_price' => 180.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'BLANC ZAYANE TRANCHE', 'unit_price' => 260.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'NOIR AZILAL TRANCHE', 'unit_price' => 280.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'NOIR KHENIFRA TRANCHE', 'unit_price' => 260.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'ROUGE BENSLIMANE TRANCHE', 'unit_price' => 200.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'JAUNE BEJAAD TRANCHE', 'unit_price' => 190.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS OULMES TRANCHE', 'unit_price' => 160.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA BEIGE 2CM POLI OU BOUCHARDE TRANCHE', 'unit_price' => 150.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA GRIS 2CM POLI OU BOUCHARDE TRANCHE', 'unit_price' => 160.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA BEIGE 3CM POLI OU BOUCHARDE TRANCHE', 'unit_price' => 170.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA GRIS 3CM POLI OU BOUCHARDE TRANCHE', 'unit_price' => 190.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA BEIGE 2CM VIELLI TRANCHE', 'unit_price' => 180.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA GRIS 2CM VIELLI TRANCHE', 'unit_price' => 190.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA BEIGE 3CM VIELLI TRANCHE', 'unit_price' => 210.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'TAZA GRIS 3CM VIELLI TRANCHE', 'unit_price' => 220.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS SAHARA TRANCHE', 'unit_price' => 200.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'CREMA ATLAS TRANCHE', 'unit_price' => 170.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'VOLUBULIS BRUT TRANCHE', 'unit_price' => 200.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'VOLUBULIS POLI TRANCHE', 'unit_price' => 250.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'VOLUBILIS RESINE TRANCHE', 'unit_price' => 300.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'GRIS KHENIFRA TRANCHE', 'unit_price' => 190.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'ATLAS STONE TRANCHE', 'unit_price' => 400.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'ROUGE AGADIR TRANCHE', 'unit_price' => 260.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'NOIR BOUTFADA TRANCHE', 'unit_price' => 250.00],
            ['category' => 'MARBRE LOCALE', 'type' => 'TRANCHE', 'name' => 'SAINT LAURENT TRANCHE', 'unit_price' => 450.00],
        
            
            // ------------

            // GRANIT : 27
            ['category' => 'GRANIT', 'name' => 'ROSE PORINO DEBITE', 'type' => 'DEBITE', 'unit_price' => 450.00],
            ['category' => 'GRANIT', 'name' => 'CREMA JULIA DEBITE', 'type' => 'DEBITE', 'unit_price' => 480.00],
            ['category' => 'GRANIT', 'name' => 'MONDARIZ DEBITE', 'type' => 'DEBITE', 'unit_price' => 480.00],
            ['category' => 'GRANIT', 'name' => 'BLANC PERLE DEBITE', 'type' => 'DEBITE', 'unit_price' => 580.00],
            ['category' => 'GRANIT', 'name' => 'GRIS RAFAYEL DEBITE', 'type' => 'DEBITE', 'unit_price' => 560.00],
            ['category' => 'GRANIT', 'name' => 'BLANCO MINIOS DEBITE', 'type' => 'DEBITE', 'unit_price' => 520.00],
            ['category' => 'GRANIT', 'name' => 'GRIS ESPANGOL DEBITE', 'type' => 'DEBITE', 'unit_price' => 560.00],
            ['category' => 'GRANIT', 'name' => 'GRAND PERLA / GRAND TACHE DEBITE', 'type' => 'DEBITE', 'unit_price' => 620.00],
            ['category' => 'GRANIT', 'name' => 'ROSAVEL DEBITE', 'type' => 'DEBITE', 'unit_price' => 560.00],
            ['category' => 'GRANIT', 'name' => 'CREMA CAMELIA DEBITE', 'type' => 'DEBITE', 'unit_price' => 560.00],
            ['category' => 'GRANIT', 'name' => 'ROUGE BALMORAL DEBITE', 'type' => 'DEBITE', 'unit_price' => 900.00],
            ['category' => 'GRANIT', 'name' => 'BALTIC BROWN DEBITE', 'type' => 'DEBITE', 'unit_price' => 860.00],
            ['category' => 'GRANIT', 'name' => 'LABRADOR NOIR DEBITE', 'type' => 'DEBITE', 'unit_price' => 1300.00],
            ['category' => 'GRANIT', 'name' => 'LABRADOR GOLD DEBITE', 'type' => 'DEBITE', 'unit_price' => 1200.00],
            ['category' => 'GRANIT', 'name' => 'NOIR GALAXY DEBITE', 'type' => 'DEBITE', 'unit_price' => 1100.00],
            ['category' => 'GRANIT', 'name' => 'NOIR ABSOLUT DEBITE', 'type' => 'DEBITE', 'unit_price' => 1100.00], 
            ['category' => 'GRANIT', 'name' => 'NOIR ANGOLA DEBITE', 'type' => 'DEBITE', 'unit_price' => 960.00],
            ['category' => 'GRANIT', 'name' => 'GRANIT NOIR FLAME DEBITE', 'type' => 'DEBITE', 'unit_price' => 1300.00], 
            ['category' => 'GRANIT', 'name' => 'TITANUM GOLD DEBITE', 'type' => 'DEBITE', 'unit_price' => 2000.00],
            ['category' => 'GRANIT', 'name' => 'BLANC MONTREAL DEBITE', 'type' => 'DEBITE', 'unit_price' => 2000.00], 
            ['category' => 'GRANIT', 'name' => 'GRIS ORIENT / HALAYEB DEBITE', 'type' => 'DEBITE', 'unit_price' => 450.00], 
            ['category' => 'GRANIT', 'name' => 'LABRADOR BLEU DEBITE', 'type' => 'DEBITE', 'unit_price' => 1300.00], 
            ['category' => 'GRANIT', 'name' => 'ROUGE CARMEN DEBITE', 'type' => 'DEBITE', 'unit_price' => 950.00], 
            ['category' => 'GRANIT', 'name' => 'NEW CREMA CALACATA / STATOUARIO DEBITE', 'type' => 'DEBITE', 'unit_price' => 1300.00], 
            ['category' => 'GRANIT', 'name' => 'NEW CREMA BLANC DEBITE', 'type' => 'DEBITE', 'unit_price' => 1200.00], 
            ['category' => 'GRANIT', 'name' => 'NEW CREMA GRIS DEBITE', 'type' => 'DEBITE', 'unit_price' => 1000.00], 
            ['category' => 'GRANIT', 'name' => 'ASCALE (PRIX POUR CUISINE) DEBITE', 'type' => 'DEBITE', 'unit_price' => 1900.00], 

            // MARBRE IMPORTE : 22
            ['category' => 'MARBRE IMPORTE', 'name' => 'MARMARA DEBITE', 'type' => 'DEBITE', 'unit_price' => 900.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'SILVIA DEBITE', 'type' => 'DEBITE', 'unit_price' => 280.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'PERLATINO DEBITE', 'type' => 'DEBITE', 'unit_price' => 750.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'CREMA MARFIL DEBITE', 'type' => 'DEBITE', 'unit_price' => 750.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'CREMA GALALA DEBITE', 'type' => 'DEBITE', 'unit_price' => 420.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'CREMA ROYALE DEBITE', 'type' => 'DEBITE', 'unit_price' => 1300.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'BLANC CARRARE DEBITE', 'type' => 'DEBITE', 'unit_price' => 750.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'BLANC KAMALPASHA DEBITE', 'type' => 'DEBITE', 'unit_price' => 750.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'BLANC SAPHIR DEBITE', 'type' => 'DEBITE', 'unit_price' => 1200.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'BLANC IBIZA DEBITE', 'type' => 'DEBITE', 'unit_price' => 820.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'ROUGE ALICANTE DEBITE', 'type' => 'DEBITE', 'unit_price' => 750.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'VERT GUATEMALA DEBITE', 'type' => 'DEBITE', 'unit_price' => 820.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'VERT ALPE DEBITE', 'type' => 'DEBITE', 'unit_price' => 750.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'BLANC CRYSTAL DEBITE', 'type' => 'DEBITE', 'unit_price' => 900.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'MARRON IMPERIAL DEBITE', 'type' => 'DEBITE', 'unit_price' => 750.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'MARRON LIGHT DEBITE', 'type' => 'DEBITE', 'unit_price' => 620.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'MARRON ROYALE DEBITE', 'type' => 'DEBITE', 'unit_price' => 850.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'ROSE PORTUGAL DEBITE', 'type' => 'DEBITE', 'unit_price' => 750.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'BLANC PORTUGAL DEBITE', 'type' => 'DEBITE', 'unit_price' => 820.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'BLANC POLARIS DEBITE', 'type' => 'DEBITE', 'unit_price' => 650.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'TOUNDRA GREY DEBITE', 'type' => 'DEBITE', 'unit_price' => 1100.00],
            ['category' => 'MARBRE IMPORTE', 'name' => 'LILAC DEBITE', 'type' => 'DEBITE', 'unit_price' => 1200.00],
            
            // MARBRE LOCALE : 28
            ['category' => 'MARBRE LOCALE', 'name' => 'GRIS VIOLET DEBITE', 'type' => 'DEBITE', 'unit_price' => 320.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'GRIS BENSLIMANE DEBITE', 'type' => 'DEBITE', 'unit_price' => 320.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'GRIS TIFLET DEBITE', 'type' => 'DEBITE', 'unit_price' => 320.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'ROUGE TINGHIR DEBITE', 'type' => 'DEBITE', 'unit_price' => 360.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'BLANC ZAYANE DEBITE', 'type' => 'DEBITE', 'unit_price' => 460.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'NOIR AZILAL DEBITE', 'type' => 'DEBITE', 'unit_price' => 480.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'NOIR KHENIFRA DEBITE', 'type' => 'DEBITE', 'unit_price' => 450.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'ROUGE BENSLIMANE DEBITE', 'type' => 'DEBITE', 'unit_price' => 360.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'JAUNE BEJAAD DEBITE', 'type' => 'DEBITE', 'unit_price' => 320.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'GRIS OULMES DEBITE', 'type' => 'DEBITE', 'unit_price' => 260.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'TAZA BEIGE 2CM POLI OU BOUCHARDE DEBITE', 'type' => 'DEBITE', 'unit_price' => 240.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'TAZA GRIS 2CM POLI OU BOUCHARDE DEBITE', 'type' => 'DEBITE', 'unit_price' => 260.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'TAZA BEIGE 3CM POLI OU BOUCHARDE DEBITE', 'type' => 'DEBITE', 'unit_price' => 280.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'TAZA GRIS 3CM POLI OU BOUCHARDE DEBITE', 'type' => 'DEBITE', 'unit_price' => 340.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'TAZA BEIGE 2CM VIELLI DEBITE', 'type' => 'DEBITE', 'unit_price' => 280.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'TAZA GRIS 2CM VIELLI DEBITE', 'type' => 'DEBITE', 'unit_price' => 300.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'TAZA BEIGE 3CM VIELLI DEBITE', 'type' => 'DEBITE', 'unit_price' => 320.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'TAZA GRIS 3CM VIELLI DEBITE', 'type' => 'DEBITE', 'unit_price' => 380.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'GRIS SAHARA DEBITE', 'type' => 'DEBITE', 'unit_price' => 300.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'CREMA ATLAS DEBITE', 'type' => 'DEBITE', 'unit_price' => 270.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'VOLUBULIS BRUT DEBITE', 'type' => 'DEBITE', 'unit_price' => 420.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'VOLUBULIS POLI DEBITE', 'type' => 'DEBITE', 'unit_price' => 460.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'VOLUBILIS RESINE DEBITE', 'type' => 'DEBITE', 'unit_price' => 500.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'GRIS KHENIFRA DEBITE', 'type' => 'DEBITE', 'unit_price' => 290.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'ATLAS STONE DEBITE', 'type' => 'DEBITE', 'unit_price' => 600.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'ROUGE AGADIR DEBITE', 'type' => 'DEBITE', 'unit_price' => 480.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'NOIR BOUTFADA DEBITE', 'type' => 'DEBITE', 'unit_price' => 450.00],
            ['category' => 'MARBRE LOCALE', 'name' => 'SAINT LAURENT DEBITE', 'type' => 'DEBITE', 'unit_price' => 800.00],


            // CARREAUX : 39
            ['name' => 'CREMA ROYALE 60x30 EXTRA CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 560.00],
            ['name' => 'SILVIA ESCALIER POLI ', 'type' => 'ESCALIER', 'category' => 'CARREAUX', 'unit_price' => 160.00],
            ['name' => 'SILVIA 60x30 BRUT CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 130.00],
            ['name' => 'SILVIA 60x30 POLI CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 150.00],
            ['name' => 'PERLATINO ESCALIER', 'type' => 'ESCALIER', 'category' => 'CARREAUX', 'unit_price' => 560.00],
            ['name' => 'CREMA MARFIL 60x30 BRUT ST CARREAUXD', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 240.00],
            ['name' => 'CREMA MARFIL 60x30 BRUT 1ER CHOIX CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 300.00],
            ['name' => 'BLANC CARRARE 60x30 BRUT CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 380.00],
            ['name' => 'BLANC IBIZA 50x30 - 40x30 POLI CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 220.00],
            ['name' => 'BLANC IBIZA 60x30 POLI 1ER CHOIX CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 320.00],
            ['name' => 'BLANC IBIZA 60x30 POLI ORO CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 240.00],
            ['name' => 'BLANC IBIZA 120x60 POLI CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 480.00],
            ['name' => 'BLANC KAMALPASHA CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 380.00],
            ['name' => 'PERLATINO 60x30 POLI CP CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 380.00],
            ['name' => 'PERLATINO 60x30 ODDO CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 320.00],
            ['name' => 'PERLATINO 60x30 BRUT CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 240.00],
            ['name' => 'PERLATINO 40x40 POLI CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 380.00],
            ['name' => 'PERLATO 30x30 BRUT CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 250.00],
            ['name' => 'GRIS TIFLET 60x30 BRUT CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 150.00],
            ['name' => 'GRIS TIFLET 30x30 BRUT CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 120.00],
            ['name' => 'GRIS TIFLET 30x10 BRUT CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 60.00],
            ['name' => 'GRIS BENSLIMANE 60x30 BRUT CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 150.00],
            ['name' => 'JAUNE BEJAAD 60x30 BRUT CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 150.00],
            ['name' => 'JAUNE BEJAAD 30x30 BRUT CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 110.00],
            ['name' => 'JAUNE BEJAAD 30x10 BRUT CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 60.00],
            ['name' => 'NOIR KHENIFRA 60x30 BRUT CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 170.00],
            ['name' => 'NOIR KHENIFRA 30x10 BRUT CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 65.00],
            ['name' => 'TAZA BEIGE 60x30 BRUT CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 120.00],
            ['name' => 'TAZA GRIS 60x30 BRUT CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 130.00],
            ['name' => 'TAZA BEIGE 60x40 BRUT CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 140.00],
            ['name' => 'TAZA GRIS 60x40 BRUT CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 140.00],
            ['name' => 'CREMA ROYALE 60x30 CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 480.00],
            ['name' => 'BLANC ZAYANE 60x30 BRUT CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 200.00],
            ['name' => 'VOLUBILIS 60x30 CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 180.00],
            ['name' => 'VOLUBILIS 30x30 CLAIR CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 160.00],
            ['name' => 'VOLUBILIS 30x30 CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 140.00],
            ['name' => 'CHEVRON BLANC IMPORT 30x10 CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 160.00],
            ['name' => 'CHEVRON SILVIA 30x10 CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 70.00],
            ['name' => 'CHEVRON BEIGE IMPORT 30x10 CARREAUX', 'type' => 'CARREAUX', 'category' => 'CARREAUX', 'unit_price' => 150.00],

            
            // FINITION : 10
            ['name' => 'FINITION NORMAL', 'category' => 'FINITION', 'unit_price' => 30.00],
            ['name' => 'FINITION JOINT CREUX', 'category' => 'FINITION', 'unit_price' => 120.00],
            ['name' => 'FINITION BESEAUTE', 'category' => 'FINITION', 'unit_price' => 35.00],
            ['name' => 'FINITION BESEAUTE LARGE', 'category' => 'FINITION', 'unit_price' => 40.00],
            ['name' => 'FINITION RONDE', 'category' => 'FINITION', 'unit_price' => 80.00],
            ['name' => 'FINITION DEMI RONDE', 'category' => 'FINITION', 'unit_price' => 50.00],
            ['name' => 'FINITION LOUIS 15', 'category' => 'FINITION', 'unit_price' => 150.00],
            ['name' => 'FINITION BEC DE CANARD', 'category' => 'FINITION', 'unit_price' => 150.00],
            ['name' => 'FINITION LED', 'category' => 'FINITION', 'unit_price' => 150.00],
            ['name' => 'FINITION RECEVEUR DOUCHE', 'category' => 'FINITION', 'unit_price' => 700.00],
            
            // DEBITAGE : 15
            ['name' => 'COUPE GRAND EVIER / SOUS PLAN', 'category' => 'DEBITAGE', 'unit_price' => 450.00],  
            ['name' => 'COUPE GRAND EVIER / SUR PLAN', 'category' => 'DEBITAGE', 'unit_price' => 500.00],
            ['name' => 'COUPE GRAND EVIER / NORMALE', 'category' => 'DEBITAGE', 'unit_price' => 300.00],
            ['name' => 'COUPE PETIT EVIER / SOUS PLAN', 'category' => 'DEBITAGE', 'unit_price' => 350.00],
            ['name' => 'COUPE PETIT EVIER / SUR PLAN', 'category' => 'DEBITAGE', 'unit_price' => 350.00],
            ['name' => 'COUPE PETIT EVIER / NORMALE', 'category' => 'DEBITAGE', 'unit_price' => 250.00],
            ['name' => 'COUPE PLAQUE', 'category' => 'DEBITAGE', 'unit_price' => 200.00],
            ['name' => 'COUPE JOINT CREUX', 'category' => 'DEBITAGE', 'unit_price' => 50.00],
            ['name' => 'COUPE JOINT LED', 'category' => 'DEBITAGE', 'unit_price' => 50.00],
            ['name' => 'COUPE CANIVEAU', 'category' => 'DEBITAGE', 'unit_price' => 200.00],
            ['name' => 'COUPE SIPHON', 'category' => 'DEBITAGE', 'unit_price' => 120.00],
            ['name' => 'COUPE ROBINET', 'category' => 'DEBITAGE', 'unit_price' => 60.00],
            ['name' => 'COUPE DISTRI. SAVON', 'category' => 'DEBITAGE', 'unit_price' => 60.00],
            ['name' => 'COUPE PORTE SERVIETTE', 'category' => 'DEBITAGE', 'unit_price' => 500.00],
            ['name' => 'COUPE PORTE PAPIER HYGIENIQUE', 'category' => 'DEBITAGE', 'unit_price' => 180.00]

        ];

        foreach ($products as $data) {
            $categoryInitial = strtoupper(substr($data['category'], 0, 1));
        
            // Find the highest numeric part of the product_code for the given category
            $maxProductCode = Product::where('category', $data['category'])
                ->where('product_code', 'like', $categoryInitial . '%')  // Only products in the same category
                ->max('product_code');
        
            // If no products exist for this category, start with 001
            if ($maxProductCode) {
                // Extract the numeric part and increment it
                $lastNumber = (int) substr($maxProductCode, 1);
                $newProductCode = $categoryInitial . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                // If no products exist, start with the first code
                $newProductCode = $categoryInitial . '001';
            }
        
            // Ensure that the new product code is unique (prevent potential race conditions)
            while (Product::where('product_code', $newProductCode)->exists()) {
                // If the code already exists, increment the number
                $lastNumber++;
                $newProductCode = $categoryInitial . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }
        
            // Insert the product with the newly generated unique product code
            Product::create([
                'name' => $data['name'],
                'category' => $data['category'],
                'type' => isset($data['type']) ? $data['type'] : null,
                'unit_price' => $data['unit_price'],
                'product_code' => $newProductCode,
                'quantity' => 500,
                'color' => null,
                'image_path' => null,
                'user-name' => null,
            ]);
        }
        
        
    }
}
