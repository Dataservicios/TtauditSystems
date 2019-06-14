<?php
/**
 * Created by PhpStorm.
 * User: Franco
 * Date: 22/10/2018
 * Time: 05:17 PM
 */

namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\InconsistencyDetail;
use Illuminate\Support\Facades\DB;

class InconsistencyDetailRepo extends Repository
{

    public function getModel()
    {
        return new InconsistencyDetail;
    }

    public function getRegForCompany($company_id,$ubigeo="0")
    {
        //$inconsistencyDetails = InconsistencyDetail::join();
        if ($ubigeo=="0")
        {
            $where = " WHERE
  `inconsistency_details`.`company_id` = ?";
        }else{
            $where = " WHERE
  `inconsistency_details`.`company_id` = ? and `exchanges`.`office`=?";
        }

        $sql="SELECT 
  `inconsistency_details`.`id`,
  `inconsistency_details`.`inconsistency_id`,
  `inconsistency_details`.`exchange_id`,
  `exchanges`.`office`,
  `exchanges`.`market`,
`exchanges`.`pdv_description`,
`exchanges`.`invoice_id`,
`exchanges`.`invoice_number`,
`exchanges`.`campaigne`,
`exchanges`.`product`,
`exchanges`.`amount`,
  `inconsistency_details`.`comment`,
  `inconsistency_details`.`photo_url`,
  `inconsistency_details`.`company_id`,
  `inconsistency_details`.`created_at`,
  `inconsistency_details`.`updated_at`,
  `inconsistencies`.`fullname`,
  `inconsistencies`.`type`
FROM
  `inconsistency_details`
  LEFT OUTER JOIN `exchanges` ON (`inconsistency_details`.`exchange_id` = `exchanges`.`id`)
  INNER JOIN `inconsistencies` ON (`inconsistency_details`.`inconsistency_id` = `inconsistencies`.`id`) ".$where;


        if ($ubigeo=="0")
        {
            $results = DB::select($sql, [ $company_id]);
        }else{
            $results = DB::select($sql, [ $company_id,$ubigeo]);
        }

        return $results;
    }
}