<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExchangeImport;
use App\Repositories\ExchangeMasterRepo;
use App\Repositories\LogProcesseRepo;
use App\Repositories\ExchangeRepo;
use App\Repositories\CompanyRepo;
use App\Repositories\PollRepo;
use App\Repositories\PublicityDetailRepo;
use App\Repositories\PollDetailRepo;

class OperationsController extends Controller
{
    protected $exchangeMasterRepo;
    protected $logProcesseRepo;
    protected $exchangeRepo;
    protected $companyRepo;
    protected $pollRepo;
    protected $publicityDetailRepo;
    protected $pollDetailRepo;

    public function __construct(PollDetailRepo $pollDetailRepo,PublicityDetailRepo $publicityDetailRepo,PollRepo $pollRepo,CompanyRepo $companyRepo,ExchangeRepo $exchangeRepo,ExchangeMasterRepo $exchangeMasterRepo,LogProcesseRepo $logProcesseRepo)
    {
        $this->exchangeMasterRepo = $exchangeMasterRepo;
        $this->logProcesseRepo = $logProcesseRepo;
        $this->exchangeRepo = $exchangeRepo;
        $this->companyRepo = $companyRepo;
        $this->pollRepo = $pollRepo;
        $this->publicityDetailRepo = $publicityDetailRepo;
        $this->pollDetailRepo = $pollDetailRepo;
    }


    public function uploadFileLucky(Request $request)
    {
        //obtenemos el campo file definido en el formulario
        $file = $request->file('file');
        $valoresPost= $request->all();
        $fullname = $valoresPost['fullname'];
        //obtenemos el nombre del archivo
        $nombre = $file->getClientOriginalName();
        $objUser = Auth::user();
        $objExchangeMaster = $this->exchangeMasterRepo->getModel();
        $objExchangeMaster->fullname = $fullname;
        $objExchangeMaster->file = $nombre;
        $objExchangeMaster->user_id = $objUser->id;
        $objExchangeMaster->auditor = $objUser->fullname;
        $objExchangeMaster->company_id = 244;
        $objExchangeMaster->save();
        $objLog = $this->logProcesseRepo->getModel();
        $objLog->processes = 'uploadLucky';
        $objLog->status = 1;
        $objLog->company_id = 244;
        $objLog->user_id = $objUser->id;
        $objLog->type_operation = 'insert';
        $objLog->table_bd = 'exchange_master';
        $objLog->reference_id = $objExchangeMaster->id;
        $objLog->save();

        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('public')->put($nombre,  \File::get($file));
        $url = \Storage::disk('public')->url($nombre);
        $path = public_path($url);

        $obj_exchange = new ExchangeImport;
        //Excel::import($obj_exchange, $nombre,'public');
        //$collection = Excel::toCollection($obj_exchange, $nombre,'public');
        $array = Excel::toArray($obj_exchange, $nombre,'public');
        $valoresArray = $array[0];
        $myArray = collect($valoresArray);
        $group_offices = $myArray->groupBy('5');$acum=0;
        foreach ($group_offices as $key => $group_office) {
            $acum = $acum + $group_office->count();
            $graph_offices[] = array('opcion'=>$key,'value' => $group_office->count());
        }
        foreach ($graph_offices as $graph_office) {
            $graphs_offices[] = array('opcion'=>$graph_office['opcion'],'valor' => $graph_office['value'],'porcen'=>round(($graph_office['value']/$acum)*100,0));
        }
        $acum=0;
        foreach ($group_offices as $key => $group_office) {
            $group_office_invoices = $group_office->groupBy('12');
            $acum = $acum + $group_office_invoices->count();
            $graph_office_invoices[] = array('opcion'=>$key,'value' => $group_office_invoices->count());
        }
        foreach ($graph_office_invoices as $graph_office_invoice) {
            $graphs_office_invoices[] = array('opcion'=>$graph_office_invoice['opcion'],'valor' => $graph_office_invoice['value'],'porcen'=>round(($graph_office_invoice['value']/$acum)*100,0));
        }
        //$this->exchangeRepo->updatedExchangeId($objExchangeMaster->id);
        return array('reg'=>$objExchangeMaster,'nro_reg'=>$myArray->count(),'graph_office'=>$graphs_offices,'count_offices'=>count($graphs_offices),'graph_office_invoice'=>$graphs_office_invoices,'count_office_invoices'=>count($graphs_office_invoices));



    }

    public function saveSod(Request $request)
    {
        $valoresPost= $request->all();
        $publicityDetail_id = $valoresPost['publicityDetail_id'];
        $sods = $valoresPost['sod'];
        $foto = $valoresPost['foto'];
        $company_id = $valoresPost['company_id'];
        $publicity_id = $valoresPost['publicity_id'];

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
        $objPublicityDetail->sod =1;
        $objPublicityDetail->photo = $foto;
        $objPublicityDetail->update();$sw=0;

        $log_process = $this->logProcesseRepo->getModel();
        $poll_id = $pollSodPorMarca;
        $user_id = Auth::id();
        $this->insertLog($log_process,$poll_id,$user_id,'sod',$objPublicityDetail,0,0,0,$publicityDetail_id,'publicity_details','updated');

        if (count($sods)>0)
        {
            for($i = 0; $i < count($sods); ++$i) {
                $valores = explode('|', $sods[$i]);
                $objPollDetail = $this->pollDetailRepo->getModel();
                $objPollDetail->poll_id = $pollSodPorMarca;
                $objPollDetail->product_id = $valores[0];
                $objPollDetail->comentario = $valores[1];
                $objPollDetail->store_id = $objPublicityDetail->store_id;
                $objPollDetail->created_at = $objPublicityDetail->created_at;
                $objPollDetail->auditor = $objPublicityDetail->user_id;
                $objPollDetail->coment = 1;
                $objPollDetail->publicity_id = $publicity_id;
                $objPollDetail->company_id = $company_id;
                $objPollDetail->save();
            }

        }else{
            $sw=1;
        }

        if ($sw==1)
        {
            return  [ 'success'=> 0];
        }else{

            return  [ 'success'=> 1];
        }

    }
}
