<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class ItemsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public $rows = 0;
    public $skipped = [];

    public function model(array $row)
    {
         // Ensure barcode exists in file
        $barcode = trim($row['barcode'] ?? '');
        if ($barcode === '') {
            return null; // Skip if no barcode
        }

        // Check if barcode already exists in DB
        if (Item::where('barcode', $barcode)->exists()) {
            $this->skipped[] = $row['barcode'];
            return null; // Skip if duplicate
        }
        ++$this->rows;
        return new Item([
            //
            'name'        => trim($row['name'] ?? ''),
            'barcode'     => $row['barcode'] ?? 0,
            'price'       => $row['price'] ?? 0,
            'quantity'    => $row['quantity']?? 0,
            'created_at'  =>isset($row['date']) ? Carbon::parse($row['date']) : now(),
        ]);
    }
}
