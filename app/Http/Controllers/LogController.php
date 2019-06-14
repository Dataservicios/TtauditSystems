<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\LogProcesseRepo;
use App\Repositories\CompanyRepo;
use App\Repositories\PollRepo;
use App\Repositories\PublicityDetailRepo;

class LogController extends Controller
{

    protected $logProcesseRepo;
    protected $companyRepo;
    protected $pollRepo;
    protected $publicityDetailRepo;


    public function __construct(PublicityDetailRepo $publicityDetailRepo,PollRepo $pollRepo,CompanyRepo $companyRepo,LogProcesseRepo $logProcesseRepo)
    {
        $this->logProcesseRepo = $logProcesseRepo;
        $this->companyRepo = $companyRepo;
        $this->pollRepo = $pollRepo;
        $this->publicityDetailRepo = $publicityDetailRepo;
    }


    public function insertBadPictureTaken(Request $request)
    {
        $valoresPost= $request->all();
        $publicityDetail_id = $valoresPost['publicityDetail_id'];
        $company_id = $valoresPost['company_id'];
        $user_id = $valoresPost['user_id'];

        $customer_id = 4;
        $estudio=6;
        $companyRepo = $this->companyRepo;
        $pollRepo = $this->pollRepo;
        $pollsWeb =$this->getAllPollsWeb($companyRepo,$pollRepo,$customer_id,$estudio);
        foreach ($pollsWeb as $pollWeb) {
            if (($pollWeb['identificador']=='sodPorMarca') and ($pollWeb['company_id']==$company_id))
            {
                $pollSodPorMarca = $pollWeb['poll_id'];
            }
        }

        $objPublicityDetail = $this->publicityDetailRepo->show($publicityDetail_id);


        $log_process = $this->logProcesseRepo->getModel();
        $objLog = $log_process->where('processes','operations')->where('company_id',$company_id)->where('store_id',$objPublicityDetail->store_id)->where('publicity_id',$objPublicityDetail->publicity_id)->where('poll_id',$pollSodPorMarca)->where('type_operation','errorPhoto')->where('table_bd','publicity_details')->where('reference_id',$publicityDetail_id)->get();
        if (count($objLog)>0)
        {
            $idLog = $objLog[0]->id;
            $objLogdelete = $this->logProcesseRepo->show($idLog);
            $objLogdelete->delete();
        }else{
            $log_process1 = $this->logProcesseRepo->getModel();
            $poll_id = $pollSodPorMarca;
            //$user_id = Auth::id();
            $user_id = $user_id;
            if ($this->insertLog($log_process1,$poll_id,$user_id,'operations',$objPublicityDetail,0,0,1,$publicityDetail_id,'publicity_details','errorPhoto'))
            {
                return  [ 'success'=> 1];
            }else{
                return  [ 'success'=> 0];
            }
        }


        


    }
}
