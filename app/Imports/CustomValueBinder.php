<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\IValueBinder;

class CustomValueBinder extends DefaultValueBinder implements IValueBinder
{
    public function bindValue(Cell $cell, $value)
    {
        // If the cell contains a formula, use its calculated value
        if ($cell->isFormula()) {
            $value = $cell->getCalculatedValue(); // Evaluate formula
        }

        // Call the parent method to bind the value
        return parent::bindValue($cell, $value);
    }
}
