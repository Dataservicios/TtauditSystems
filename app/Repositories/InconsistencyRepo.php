<?php
/**
 * Created by PhpStorm.
 * User: Franco
 * Date: 22/10/2018
 * Time: 05:17 PM
 */

namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\Inconsistency;
use Illuminate\Support\Facades\DB;

class InconsistencyRepo extends Repository
{

    public function getModel()
    {
        return new Inconsistency;
    }

    public function getOfficeForCampaigne($company_id)
    {
        $results = DB::select('SELECT 
  `exchanges`.`office`
FROM
  `inconsistency_details`
  LEFT OUTER JOIN `exchanges` ON (`inconsistency_details`.`exchange_id` = `exchanges`.`id`)
WHERE
  `inconsistency_details`.`company_id` = ?
GROUP BY
  `exchanges`.`office`', [ $company_id]);
        return $results;
    }

}