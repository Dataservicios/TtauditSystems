<?php
/**
 * Created by PhpStorm.
 * User: Franco
 * Date: 22/10/2018
 * Time: 05:17 PM
 */

namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\Module;
use Illuminate\Support\Facades\DB;

class ModuleRepo extends Repository
{

    public function getModel()
    {
        return new Module;
    }

    

}
