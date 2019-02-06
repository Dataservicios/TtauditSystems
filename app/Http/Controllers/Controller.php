<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getMenus($audits)
    {
        $menus[] = array('name'=>'Panel de Control','url'=>'/panel','icon'=>'icon-speedometer');
        $menus[] = array('title'=>true,'name'=>'Auditorias');
        foreach ($audits as $audit) {
            $menus[] = array('id'=>$audit->id,'name'=>$audit->audit,'url'=>'/panel','icon'=>'icon-speedometer');
        }
        return $menus;
    }

    public function getAllPollsWeb($companyRepo,$pollRepo,$customer_id, $estudio)
    {
        $companies = $companyRepo->getCompaniesForClient($customer_id,"1",$estudio,"T");

        $group_poll_id='';$c=0;
        if (count($companies)>0)
        {
            foreach ($companies as $company_data)
            {
                $c=$c+1;
                $objPolls = $pollRepo->getQuestionForWeb($company_data->id);
                foreach ($objPolls as $objPoll) {
                    $pollWebs[] = array('company_id'=>$company_data->id,'identificador'=>$objPoll->identificador,'poll_id'=>$objPoll->id,'obj_poll'=>$objPoll);
                }
            }
        }else{
            $pollWebs = [];
        }

        return $pollWebs;
    }

    public function getResponsePolls($pollOptionRepo,$pollDetailRepo,$pollOptionDetailRepo,$store_id,$company_id,$publicity_id,$poll_id,$product_id="0")
    {
        $storeOpen = $pollDetailRepo->getResultForStore($company_id,$store_id,$poll_id,$publicity_id,$product_id);
        $options = $pollOptionRepo->getOptionsForPollId($poll_id);
        if (count($options)>0)
        {
            foreach ($options as $option) {
                $responseOption = $pollOptionDetailRepo->getResponseForStoreOption($option->id,$store_id,$publicity_id,$product_id);
                $objOptions[] = array('obj_option'=>$option,'obj_responses'=>$responseOption);
            }

        }else{
            $objOptions[]=array('obj_option'=>[],'obj_responses'=>[]);
        }
        $responses = array('poll_details' => $storeOpen,'optionsResults' => $objOptions);

        return $responses;
    }

    public function insertLog($log_process,$poll_id,$user_id,$process,$objeto,$sino,$result_origin,$result,$poll_objeto_id,$table,$type_operation)
    {

        $log_process->processes = $process;
        $log_process->company_id = $objeto->company_id;
        $log_process->user_id = $user_id;
        $log_process->status = 1;
        if ($process=='sod')
        {
            $log_process->auditor_id = $objeto->user_id;
        }else{
            $log_process->auditor_id = $objeto->auditor;
        }

        $log_process->publicity_id = $objeto->publicity_id;
        $log_process->product_id = $objeto->product_id;

        $log_process->sino = $sino;
        $log_process->poll_id = $poll_id;
        $log_process->category_product_id = $objeto->category_product_id;

        $log_process->result_origin = $result_origin;
        $log_process->result_new = $result;
        $log_process->type_operation = $type_operation;
        $log_process->table_bd = $table;
        $log_process->reference_id = $poll_objeto_id;
        $log_process->store_id = $objeto->store_id;

        if ($log_process->save())
        {
            return true;
        }else{
            return false;
        }
    }
}
