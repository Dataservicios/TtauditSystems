<?php
/**
 * Created by PhpStorm.
 * User: Franco
 * Date: 22/10/2018
 * Time: 05:17 PM
 */

namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\PollDetail;
use Illuminate\Support\Facades\DB;

class PollDetailRepo extends Repository
{

    public function getModel()
    {
        return new PollDetail;
    }

    public function getPollDetailsForPollId($poll_id,$ubigeo="0")
    {
        if ($ubigeo == "0")
        {
            $results = DB::select('select * from `poll_details` where `poll_id` = ?', [ $poll_id]);
        }else{
            $results = DB::select('SELECT *
FROM
  `poll_details`
  INNER JOIN `stores` ON (`poll_details`.`store_id` = `stores`.`id`)
WHERE
  `poll_details`.`poll_id` = ? AND 
  `stores`.`ubigeo` = ? ', [ $poll_id,$ubigeo]);
        }

        return $results;
    }

    /**
     * Optiene las respuestas de poll details segun store, company y poll_id
     * @param $poll_id
     * @param $store_id
     * @param $company_id
     * @param $publicity_id
     * @param $product_id
     * @param $visit_id
     * @return string
     */
    public function getResultForStore($company_id,$store_id,$poll_id,$publicity_id="0",$product_id="0",$visit_id="0")
    {
        if ($publicity_id=="0"){
            if ($product_id=="0"){
                $comentario = PollDetail::where('poll_id', $poll_id)->where('company_id',$company_id)->where('store_id',$store_id)->where('visit_id',$visit_id)->get();
            }else{
                $comentario = PollDetail::where('poll_id', $poll_id)->where('company_id',$company_id)->where('store_id',$store_id)->where('product_id',$product_id)->where('visit_id',$visit_id)->get();
            }
        }else{
            if ($product_id=="0"){
                $comentario = PollDetail::where('poll_id', $poll_id)->where('company_id',$company_id)->where('store_id',$store_id)->where('publicity_id',$publicity_id)->where('visit_id',$visit_id)->get();
            }else{
                $comentario = PollDetail::where('poll_id', $poll_id)->where('company_id',$company_id)->where('store_id',$store_id)->where('publicity_id',$publicity_id)->where('product_id',$product_id)->where('visit_id',$visit_id)->get();
            }
        }

        return $comentario;
    }

}