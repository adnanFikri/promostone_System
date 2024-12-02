<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientsImport implements ToModel, WithHeadingRow
{
    /**
     * Map each row of the Excel file to the `clients` table.
     *
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row);

        // Check if the 'code_client' and 'name' are present
        // if (empty($row['code_client']) || empty($row['name'])) {
        //     return null; // Skip row if required data is missing
        // }

        return new Client([
            'code_client' => $row['code'],
            'name'        => $row['name'],
            'phone'       => null,
            'type'        => null,
        ]);
    }
}
