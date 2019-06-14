<?php

namespace App\Http\Controllers;

//use App\Exports\CollectionExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
/*use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExchangeImport;*/
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
        Excel::import($obj_exchange, $nombre,'public');
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
        $objPublicityDetail->sod =1;
        $objPublicityDetail->photo = $foto;
        $objPublicityDetail->update();$sw=0;

        $log_process = $this->logProcesseRepo->getModel();
        $poll_id = $pollSodPorMarca;
        $user_id = $user_id;
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

    public function validacionCanjes(Request $request)
    {
        $valoresPost= $request->all();
        $company_id = $valoresPost['company_id'];
        $ubigeo = $valoresPost['ubigeo'];

        $datos_graphs = $this->exchangeRepo->getDatosGraphValidacion($company_id,$ubigeo);$acum=0;$c=0;
        foreach ($datos_graphs as  $group_office) {
            if ($group_office->type==1)
            {
                $acum = $acum + intval($group_office->cantidad);//acumula registros por oficina
                $graph_offices[] = array('opcion'=>$group_office->valor,'value' => intval($group_office->cantidad));
                $c ++;
            }
            $cant_reg = $acum;
            $cant_offices = $c;
            $nombre_campana = $group_office->fullname;
        }
        foreach ($graph_offices as $graph_office) {
            $graphs_offices[] = array('opcion'=>$graph_office['opcion'],'valor' => $graph_office['value'],'porcen'=>round(($graph_office['value']/$acum)*100,0));//calcula porct. de num reg. por offi.
        }

        $acum=0;
        foreach ($datos_graphs as  $group_office) {
            if ($group_office->type==2)
            {
                $acum = $acum + intval($group_office->cantidad);//acumula registros por oficina
                $graph_office_invoices[] = array('opcion'=>$group_office->valor,'value' => intval($group_office->cantidad));
            }
            $cant_invoices = $acum;
        }
        foreach ($graph_office_invoices as $graph_office_invoice) {
            $graphs_office_invoices[] = array('opcion'=>$graph_office_invoice['opcion'],'valor' => $graph_office_invoice['value'],'porcen'=>round(($graph_office_invoice['value']/$acum)*100,0));
        }

        foreach ($datos_graphs as $group_doc) {
            if ($group_doc->type==3)
            {
                $acum = $acum + intval($group_doc->cantidad);//acumula registros por oficina
                $graph_types[] = array('opcion'=>$group_doc->valor,'value' => intval($group_doc->cantidad));
            }
        }
        foreach ($graph_types as $group_doc) {
            $valuePorcent = round(($group_doc['value']/$acum)*100,0);
            $graphs_types_docs[] = array('opcion'=>$group_doc['opcion'],'valor' => $group_doc['value'],'porcent'=> $valuePorcent,'labelY' => $valuePorcent.' %');//calcula porct. de num reg. por offi.
        }

        $acum=0;
        foreach ($datos_graphs as $item) {
            if ($item->type==4)
            {
                $valoresCantidad = explode('|',$item->cantidad);
                $acum = $acum + intval($valoresCantidad[2]);
            }
        }
        foreach ($datos_graphs as $item) {
            if ($item->type==4)
            {
                $valoresCantidad = explode('|',$item->cantidad);
                $valuePorcent = round((intval($valoresCantidad[2])/$acum)*100,0);
                $graphs_result_tickets[] = array('opcion'=>$item->valor,'valor' => intval($valoresCantidad[2]),'porcent'=> $valuePorcent,'labelY'=>intval($valoresCantidad[0]) . ' - S/.'.intval($valoresCantidad[2]));
            }
        }
        
        $acum=0;$c=0;$acum_otros=0;
        foreach ($datos_graphs as $item) {
            if ($item->type==5)
            {
                $c ++;
                $acum = $acum + intval($item->cantidad);
                if ($c<6)
                {
                    $values_filters[] = $item;
                }else{
                    $acum_otros = $acum_otros + intval($item->cantidad);
                }
            }
            

        }
        $graphs_result_clients = array('bloque'=>'Ranking');
        foreach ($values_filters as $item) {
            $valuePorcent = round(($item->cantidad/$acum)*100,2);
            $graphs_result_clients += ["$item->cantidad"=>$valuePorcent];
            $series_graph[] = array($item->cantidad,$item->valor);
        }

        /*$obj_exchange = $this->exchangeRepo->getModel();
        $dataExchange= $obj_exchange->where('company_id',259)->get();

        $myArray = collect($dataExchange);
        $group_offices = $myArray->groupBy('office');$acum=0;
        foreach ($group_offices as $key => $group_office) {
            $acum = $acum + $group_office->count();//acumula registros por oficina
            $graph_offices[] = array('opcion'=>$key,'value' => $group_office->count());
        }
        foreach ($graph_offices as $graph_office) {
            $graphs_offices[] = array('opcion'=>$graph_office['opcion'],'valor' => $graph_office['value'],'porcen'=>round(($graph_office['value']/$acum)*100,0));//calcula porct. de num reg. por offi.
        }
        $acum=0;
        foreach ($group_offices as $key => $group_office) {
            $group_office_invoices = $group_office->groupBy('invoice_number');
            $acum = $acum + $group_office_invoices->count();
            $graph_office_invoices[] = array('opcion'=>$key,'value' => $group_office_invoices->count());
        }
        foreach ($graph_office_invoices as $graph_office_invoice) {
            $graphs_office_invoices[] = array('opcion'=>$graph_office_invoice['opcion'],'valor' => $graph_office_invoice['value'],'porcen'=>round(($graph_office_invoice['value']/$acum)*100,0));
        }*/


        /*$group_type_docs = $myArray->groupBy('document_type');$acum=0;
        foreach ($group_type_docs as $key => $group_doc) {
            $acum = $acum + $group_doc->count();//acumula registros por type document
            $graph_types[] = array('opcion'=>$key,'value' => $group_doc->count());
        }
        foreach ($graph_types as $group_doc) {
            $valuePorcent = round(($group_doc['value']/$acum)*100,0);
            $graphs_types_docs[] = array('opcion'=>$group_doc['opcion'],'valor' => $group_doc['value'],'porcent'=> $valuePorcent,'labelY' => $valuePorcent.' %');//calcula porct. de num reg. por offi.
        }*/

        /*$datos_graph2 = $this->exchangeRepo->getCalculateExchange(259);$acum=0;
        foreach ($datos_graph2 as $item) {
            $acum = $acum + $item->promxfact;
        }
        foreach ($datos_graph2 as $item) {
            $valuePorcent = round(($item->promxfact/$acum)*100,0);
            $graphs_result_tickets[] = array('opcion'=>$item->category,'valor' => $item->promxfact,'porcent'=> $valuePorcent,'labelY'=>$item->cantFact . ' - S/.'.$item->promxfact);
        }*/

        /*$datos_graph3 = $this->exchangeRepo->getCalculateExchange2(259);$acum=0;$c=0;$acum_otros=0;
        foreach ($datos_graph3 as $item) {
            $c ++;
            $acum = $acum + $item->cantClientes;
            if ($c<7)
            {
                $values_filters[] = $item;
            }else{
                $acum_otros = $acum_otros + $item->cantClientes;
            }

        }
        $graphs_result_clients = array('bloque'=>'Ranking');
        foreach ($values_filters as $item) {
            $valuePorcent = round(($item->cantClientes/$acum)*100,0);
            $graphs_result_clients += ["$item->pdv_id"=>$valuePorcent];
            $series_graph[] = array($item->pdv_id,$item->pdv_description);
        }*/

        $valuesGrapg3[] = $graphs_result_clients;
        /*$valuePorcent = round(($acum_otros/$acum)*100,0);
        $graphs_result_clients[] = array('otros'=>$valuePorcent);
        $series_graph[] = array('otros','Otros');*/

        $array_graph3 = array('type'=>4,'datos'=>$valuesGrapg3,'series'=>$series_graph);

        //$objExchangeMaster = $this->exchangeMasterRepo->getModel();
        //$dataExchangeMaster = $objExchangeMaster->where('company_id',259)->first();

        //return array('reg'=>$dataExchangeMaster,'nro_reg'=>$myArray->count(),'graph_office'=>$graphs_offices,'count_offices'=>count($graphs_offices),'graph_office_invoice'=>$graphs_office_invoices,'count_office_invoices'=>count($graphs_office_invoices),'graph1'=>array('type'=>2,'datos'=>$graphs_types_docs),'count_type_doc'=>count($graphs_types_docs),'graph2'=>array('type'=>2,'datos'=>$graphs_result_tickets),'graph3'=>$array_graph3);
        //return array('reg'=>$dataExchangeMaster,'graph2'=>array('type'=>2,'datos'=>$graphs_result_tickets),'graph3'=>$array_graph3);
        return array('reg'=>$nombre_campana,'nro_reg'=>$cant_reg,'graph_office'=>$graphs_offices,'count_offices'=>$cant_offices,'graph_office_invoice'=>$graphs_office_invoices,'count_office_invoices'=>$cant_invoices,'graph1'=>array('type'=>2,'datos'=>$graphs_types_docs),'count_type_doc'=>count($graphs_types_docs),'graph2'=>array('type'=>2,'datos'=>$graphs_result_tickets),'graph3'=>$array_graph3);

    }

    /*public function excelPruebas()
    {
        $arrayPrueba[] =[
            'name' => 'Franco',
            'surname' => 'Bill',
            'email' => 'povilas@laraveldaily.com',
            'twitter' => '@povilaskorop'
        ];
        $arrayPrueba[] = [
            'name' => 'Taylor',
            'surname' => 'Otwell',
            'email' => 'taylor@laravel.com',
            'twitter' => '@taylorotwell'
        ];
        $header = [
            'Name',
            'Surname',
            'Email',
            'Twitter',
        ];

        $ObjExport = new CollectionExport($arrayPrueba,$header);

        return Excel::download($ObjExport, 'exportPrueba.xlsx');
    }*/
}
