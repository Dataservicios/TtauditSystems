<?php

namespace App\Imports;

use App\Models\Exchange;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;

class ExchangeImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Exchange([
            'invoice_id'     => $row[0],
            'invoice_detail_id'     => $row[1],
            'date_admission'     => Carbon::parse($row[2])->format('Y-m-d H:i:s'),
            'gie_id'     => $row[3],
            'gie_user'     => $row[4],
            'office'     => $row[5],
            'market'     => $row[6],
            'pdv_description'     => $row[7],
            'pdv_id'     => $row[8],
            'dni'     => $row[9],
            'type_commerce'     => $row[10],
            'location'     => $row[11],
            'invoice_number'     => $row[12],
            'campaigne_id'     => $row[13],
            'campaigne'     => $row[14],
            'brand'     => $row[15],
            'category'     => $row[16],
            'price'     => $row[17],
            'product_id'     => $row[18],
            'product'     => $row[19],
            'sell_out'     => $row[20],
            'quantity'     => $row[21],
            'amount'     => $row[22],
            'scale'     => $row[23],
            'award_id'     => $row[24],
            'award'     => $row[25],
            'document_id'     => $row[26],
            'document_type'     => $row[27],
            'client_id'     => $row[28],
            'client_type'     => $row[29],
            'client'     => $row[30],
            'exchange_master_id'     => 1,
        ]);
    }
}
