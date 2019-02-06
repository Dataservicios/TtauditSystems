<?php
/**
 * Created by PhpStorm.
 * User: Franco
 * Date: 22/10/2018
 * Time: 05:17 PM
 */

namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\PublicityDetail;
use Illuminate\Support\Facades\DB;

class PublicityDetailRepo extends Repository
{

    public function getModel()
    {
        return new PublicityDetail;
    }

}