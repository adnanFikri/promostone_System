<?php

namespace App\Imports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use App\Imports\CustomValueBinder;

class SalesImport implements ToModel, WithHeadingRow
{
    /**
     * Map each row of the Excel file to the `sales` table.
     *
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

     protected $lastRow;
    
    // public function model(array $row)
    // {
    //     // Check if all fields in the row are null or empty
    //     $fields = [
    //         $row['bl_n'] ?? null,
    //         $row['annee'] ?? null,
    //         $row['date'] ?? null,
    //         $row['ref_produit'] ?? null,
    //         $row['produit'] ?? null,
    //         $row['prix_unitaire'] ?? null,
    //         $row['longueur'] ?? null,
    //         $row['largeur'] ?? null,
    //         $row['nbr'] ?? null,
    //         $row['montant'] ?? null,
    //         $row['code_client'] ?? null,
    //     ];

    //     if (collect($fields)->filter()->isEmpty()) {
    //         // Skip this row if all fields are null or empty
    //         return null;
    //     }

    //     // Return the new Sale instance if data is valid
    //     return new Sale([
    //         'bln'           => $row['bl_n'],  
    //         'annee'         => $row['annee'],
    //         'date'          => \Carbon\Carbon::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date'])->format('Y-m-d')),
    //         'ref_produit'   => $row['ref_produit'],
    //         'produit'       => $row['produit'],
    //         'prix_unitaire' => $row['prix_unitaire'],
    //         'longueur'      => $row['longueur'],
    //         'largeur'       => $row['largeur'],
    //         'nbr'           => $row['nbr'],
    //         'montant'       => $row['montant'],
    //         'code_client'   => $row['code_client'],
    //     ]);
    // }



    public function model(array $row)
{
    // Check if all required fields are null or empty
    // dd($row);
    $fields = [
        $row['no_bl'] ?? null,
        $row['annee'] ?? null,
        $row['date_bl'] ?? null,
        $row['ref_produit'] ?? null,
        $row['produit'] ?? null,
        $row['long'] ?? null,
        $row['larg'] ?? null,
        $row['nbr'] ?? null,
        $row['qte'] ?? null,
        $row['prix_u'] ?? null,
        $row['bl_mont'] ?? null,
        $row['code_client'] ?? null,
    ];

    if (collect($fields)->filter()->isEmpty()) {
        // Skip this row if all fields are null or empty
        return null;
    }

    $qte = is_numeric($row['qte']) 
        ? $row['qte'] 
        : ($row['long'] * $row['larg'] * $row['nbr']);
    
    return new Sale([
        'no_bl'         => $row['no_bl'], 
        'annee'         => $row['annee'],
        'date'          => \Carbon\Carbon::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_bl'])->format('Y-m-d')),
        'ref_produit'   => $row['ref_produit'],
        'produit'       => $row['produit'],
        'longueur'      => $row['long'],
        'largeur'       => $row['larg'],
        'nbr'           => $row['nbr'],
        'qte'           => $qte,
        'prix_unitaire' => $row['prix_u'],
        'montant'       => $row['bl_mont'],
        'code_client'   => $row['code_client'],
    ]);
}



    
}
