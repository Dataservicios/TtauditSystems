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
    return Exchange::where('exchange_master_id', 1)->update(['exchange_master_id' => $id]);
  }

  public function getOfficeForCampaigne($company_id)
  {
    $results = DB::select('SELECT (case when `exchanges`.`office` = "" then "Sin oficina"  else `exchanges`.`office` end) as "office" FROM  `exchanges` WHERE `exchanges`.`company_id` = ? group by `exchanges`.`office`', [$company_id]);
    return $results;
  }

  /*
  @author Franco <Franco@dataservicios.com>
  @deprecated 1.10
  */
  //FIXME: Deprecated
  public function getCalculateExchange($company_id)
  {
    $results = DB::select('SELECT category,count(b) cantFact, sum(q) sumCantxFact, ROUND(avg(a)) promxfact FROM (
          SELECT
            `e`.`invoice_id` b,
            `e`.`category`,
            sum(`e`.`quantity`) q,
            sum(`e`.`amount`) a
          FROM
            `exchanges` `e`
          WHERE
            `company_id` = ?
          
          group by `e`.`invoice_id`,  `e`.`category`) tmp_prom group by tmp_prom.category', [$company_id]);
    return $results;
  }

  //FIXME: Deprecated
  public function getCalculateExchange2($company_id)
  {
    $results = DB::select('SELECT b pdv_id,pdv_description,count(q) cantClientes,o office,m mercado FROM (
            SELECT
              `e`.`pdv_id` b,
              `e`.`pdv_description`,
              count(`e`.`pdv_id`) q,`e`.`office` o,  `e`.`market` m
            FROM
              `exchanges` `e`
            WHERE
              `company_id` = ?

            group by `e`.`invoice_id`,`e`.`pdv_id`) tmp_prom  group by tmp_prom.b order by cantClientes desc', [$company_id]);
    return $results;
  }

  public function getDatosGraphValidacion($company_id, $ubigeo)
  {
    $results = DB::select('call sp_validacion_graph(?,?)', [$company_id, $ubigeo]);
    return $results;
  }

  public function getDatosGraphScala($company_id, $ubigeo)
  {
    $results = DB::select('call sp_validacion_graph_ranking(?,?)', [$company_id, $ubigeo]);
    return $results;
  }
}
