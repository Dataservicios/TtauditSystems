<?php
/**
 * Created by PhpStorm.
 * User: Franco
 * Date: 22/10/2018
 * Time: 05:17 PM
 */

namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\Exchange;
use Illuminate\Support\Facades\DB;

class ExchangeRepo extends Repository
{

    public function getModel()
    {
        return new Exchange;
    }

    public function updatedExchangeId($id)
    {
        return Exchange::where('exchange_master_id',1)->update(['exchange_master_id' => $id]);
    }
}