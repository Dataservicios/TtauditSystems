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

    public function getOsaPolls($study_id,$user_id)
    {
        $results = DB::select('call sp_cp_osa_poll_beta(?,?)', [ $study_id,$user_id]);
        return $results;
    }

    public function getFilterAllOsa($study_id,$user_id)
    {
        $results = DB::select('call sp_cp_osa_filters_beta(?,?,?)', [ $study_id,$user_id,0]);
        return $results;
    }

    public function getFiltersProductsOsa($study_id)
    {
        $results = DB::select('SELECT
  `product_detail`.`product_id`,
  `products`.`fullname` as product,
  `products`.`category_product_id`,
  `products`.`eam`,
  `category_products`.`fullname` as category
FROM
  `products`
  INNER JOIN `category_products` ON (`products`.`category_product_id` = `category_products`.`id`)
  INNER JOIN `product_detail` ON (`products`.`id` = `product_detail`.`product_id`)
WHERE
  `product_detail`.`company_id` in (SELECT
                                 `companies`.`id`
                               FROM
                                 `companies`
                               WHERE
                                   `companies`.`study_id` = ?
                               UNION ALL
                               SELECT
                                 239 as "id"
                               UNION ALL
                               SELECT
                                 246 as "id")
                                 group by `products`.`category_product_id`,`product_detail`.`product_id`', [ $study_id]);

        return $results;
    }

    public function puntosEfectivosOsa($study_id,$user_id,$month='0',$week='0',$office='0',$dex='0')
    {
        $results = DB::select('call sp_cp_osa_count_stores_v1(?,?,?,?,?,?)', [ $study_id,$user_id,$office,$month,$week,$dex]);
        return $results;

    }

    public function calculateDataOsa($study_id,$user_id,$month='0',$week='0',$office='0',$dex='0',$category_id=0,$product_id=0)
    {
        //resumen OSA
        $results = DB::select('call sp_cp_osa_polloption_beta_v1(?,?,?,?,?,?,?,?)', [ $study_id,$user_id,$office,$month,$week,$dex,$category_id,$product_id]);/**/
        return $results;

    }

    public function calculateOsaCategories($study_id,$user_id,$month='0',$week='0',$office='0',$dex='0',$category_id=0,$product_id=0)
    {
        $results = DB::select('call sp_cp_osa_category_v1(?,?,?,?,?,?,?,?)', [ $study_id,$user_id,$office,$month,$week,$dex,$category_id,$product_id]);
        return $results;

    }

    public function calculateOsaProducts($study_id,$user_id,$month='0',$week='0',$office='0',$dex='0',$category_id=0,$product_id=0)
    {
        $results = DB::select('call sp_cp_osa_filter_category_product_v1(?,?,?,?,?,?,?,?)', [ $study_id,$user_id,$office,$month,$week,$dex,$category_id,$product_id]);
        return $results;

    }

    public function calculateRankingFFVV($study_id,$user_id,$month='0',$week='0',$office='0',$dex='0',$category_id=0,$product_id=0)
    {
        $results = DB::select('call sp_cp_osa_ranking1_v1(?,?,?,?,?,?,?,?)', [ $study_id,$user_id,$office,$month,$week,$dex,$category_id,$product_id]);
        return $results;

    }

    public function calculateEvolutiveOsa($study_id,$user_id,$month='0',$week='0',$office='0',$dex='0',$category_id=0,$product_id=0)
    {
        //evolutivo OSA
        $results = DB::select('call sp_cp_osa_evolutivo_v1(?,?,?,?,?,?,?,?)', [ $study_id,$user_id,$office,$month,$week,$dex,$category_id,$product_id]);
        return $results;

    }

    public function getVendorsOsa($study_id,$user_id,$month='0',$week='0',$office='0',$dex='0',$category_id=0,$product_id=0,$group)
    {
        $results = DB::select('call sp_cp_osa_vendors_v1(?,?,?,?,?,?,?,?,?)', [ $study_id,$user_id,$office,$month,$week,$dex,$category_id,$product_id,$group]);
        return $results;

    }

    public function getClientsOsa($study_id,$user_id,$month='0',$week='0',$office='0',$dex='0',$category_id=0,$product_id=0)
    {
        //$results = DB::select('call sp_cp_osa_cliente_v1(?,?,?,?,?,?,?,?)', [ $study_id,$user_id,$office,$month,$week,$dex,$category_id,$product_id]);
        $results = DB::select('call sp_cp_osa_cliente_v2(?,?,?,?,?,?,?,?)', [ $study_id,$user_id,$office,$month,$week,$dex,$category_id,$product_id]);
        return $results;

    }

    public function getDexOsa($study_id,$user_id,$month='0',$week='0',$office='0',$dex='0',$category_id=0,$product_id=0)
    {
        $results = DB::select('call sp_cp_osa_ranking_for_dex(?,?,?,?,?,?,?,?)', [ $study_id,$user_id,$office,$month,$week,$dex,$category_id,$product_id]);
        return $results;

    }

}
