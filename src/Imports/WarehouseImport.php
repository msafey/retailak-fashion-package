<?php

namespace App\Imports;

use Products;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class WarehouseImport implements ToModel
{


    public function model(array $row)
    {
    }

}
