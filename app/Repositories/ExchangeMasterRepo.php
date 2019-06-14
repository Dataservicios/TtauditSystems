<?php
/**
 * Created by PhpStorm.
 * User: Franco
 * Date: 22/10/2018
 * Time: 05:17 PM
 */

namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\ExchangeMaster;
use Illuminate\Support\Facades\DB;

class ExchangeMasterRepo extends Repository
{

    public function getModel()
    {
        return new ExchangeMaster;
    }

    /*
    @final
    @method array methodName()
    @author Franco <franco@dataservicios.com>
    */
    public function getExchanges()
    {
        
    }

}