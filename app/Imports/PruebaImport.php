<?php

namespace App\Imports;

use App\Models\Prueba;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;

class PruebaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Prueba([
            'fullname'     => $row[0],
            'sueldo'     => $row[1],
            'fecha'     => Carbon::parse($row[2])->format('Y-m-d H:i:s'),
        ]);
    }
}
