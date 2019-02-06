<?php
/**
 * Created by PhpStorm.
 * User: Franco
 * Date: 22/10/2018
 * Time: 05:17 PM
 */

namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\PollOptionDetail;
use Illuminate\Support\Facades\DB;

class PollOptionDetailRepo extends Repository
{

    public function getModel()
    {
        return new PollOptionDetail;
    }

    public function getResultForOption($poll_option_id,$ubigeo)
    {
        if ($ubigeo == "0")
        {
            $results = DB::select('select * from `poll_option_details` where `poll_option_id` = ?', [ $poll_option_id]);
        }else{
            $results = DB::select('SELECT *
FROM
  `poll_option_details`
  INNER JOIN `stores` ON (`poll_option_details`.`store_id` = `stores`.`id`)
WHERE
  `poll_option_details`.`poll_option_id` = ? AND 
  `stores`.`ubigeo` = ? ', [ $poll_option_id,$ubigeo]);
        }

        return $results;
    }

    public function getResponseForStoreOption($poll_option_id,$store_id,$publicity_id="0",$product_id="0")
    {
        $pollOptionDetail = DB::table('poll_option_details')->where('poll_option_details.poll_option_id', $poll_option_id)->where('poll_option_details.publicity_id', $publicity_id)->where('poll_option_details.product_id', $product_id)->where('poll_option_details.store_id', $store_id)->get();
        return $pollOptionDetail;
    }

    public function getResponseOptionPublicity($store_id,$poll_id,$publicity_id,$company_id,$product_id="0",$visit_id="0")
    {
        //$pollOptionDetail = \DB::table('poll_option_details')->join('poll_options','poll_option_details.poll_option_id','=','poll_options.id')->select('poll_options.id','poll_options.options','poll_option_details.created_at','poll_option_details.updated_at')->where('poll_options.poll_id', $poll_id)->where('poll_option_details.company_id', $company_id)->where('poll_option_details.publicity_id', $publicity_id)->where('poll_option_details.store_id', $store_id)->get();
        if ($visit_id=="0"){
            $pollOptionDetail = \DB::table('poll_option_details')->join('poll_options','poll_option_details.poll_option_id','=','poll_options.id')->select('poll_options.id','poll_options.options','poll_option_details.id as poll_option_details_id','poll_option_details.product_id','poll_option_details.priority','poll_option_details.otro as coment_otro','poll_option_details.created_at','poll_option_details.updated_at')->where('poll_options.poll_id', $poll_id)->where('poll_option_details.company_id', $company_id)->where('poll_option_details.publicity_id', $publicity_id)->where('poll_option_details.product_id', $product_id)->where('poll_option_details.store_id', $store_id)->get();
        }else{
            $pollOptionDetail = \DB::table('poll_option_details')->join('poll_options','poll_option_details.poll_option_id','=','poll_options.id')->select('poll_options.id','poll_options.options','poll_option_details.id as poll_option_details_id','poll_option_details.product_id','poll_option_details.priority','poll_option_details.otro as coment_otro','poll_option_details.created_at','poll_option_details.updated_at')->where('poll_options.poll_id', $poll_id)->where('poll_option_details.company_id', $company_id)->where('poll_option_details.publicity_id', $publicity_id)->where('poll_option_details.product_id', $product_id)->where('poll_option_details.store_id', $store_id)->where('poll_option_details.visit_id', $visit_id)->get();
        }

        return $pollOptionDetail;
    }

}