<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PollDetailRepo;
use App\Repositories\PollOptionDetailRepo;
use App\Repositories\PollOptionRepo;
use App\Repositories\LogProcesseRepo;
use Illuminate\Support\Facades\Auth;

class PollDetailsController extends Controller
{
    protected $pollDetailRepo;
    protected $pollOptionDetailRepo;
    protected $pollOptionRepo;
    protected $logProcesseRepo;

    public function __construct(LogProcesseRepo $logProcesseRepo, PollOptionRepo $pollOptionRepo,PollOptionDetailRepo $pollOptionDetailRepo,PollDetailRepo $pollDetailRepo)
    {
        // set the model
        $this->pollDetailRepo = $pollDetailRepo;
        $this->pollOptionDetailRepo = $pollOptionDetailRepo;
        $this->pollOptionRepo = $pollOptionRepo;
        $this->logProcesseRepo = $logProcesseRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getRegsForPollId($poll_id,$type,$ubigeo="0")
    {
        $valores = $this->pollDetailRepo->getPollDetailsForPollId($poll_id,$ubigeo);
        $myArray = collect($valores);
        if (($type==1) or ($type==3))
        {
            $filtered = $myArray->where('sino', 1);
            $sinoSi = $filtered->where('result',1);
            if (count($sinoSi)>0)
            {
                foreach ($sinoSi as $item) {
                    $storesSi[]=$item->store_id;
                }
            }else{
                $storesSi[]="";
            }

            $sinoNo = $filtered->where('result',0);
            if (count($sinoNo)>0)
            {
                foreach ($sinoNo as $item) {
                    $storesNo[]=$item->store_id;
                }
            }else{
                $storesNo[]="";
            }

            $results1 = array('type'=>'sino','poll_id'=>$poll_id,'si'=>$sinoSi->count(),'no'=>$sinoNo->count(),'storesSi'=>$storesSi,'storesNo'=>$storesNo);
            if ($type==1){
                return $results1;
            }

        }
        if (($type==2) or ($type==3))
        {
            $options = $this->pollOptionRepo->getOptionsForPollId($poll_id);
            foreach ($options as $option) {
                if ($option->options_abreviado<>'')
                {
                    $nomb_opciones[] = $option->options_abreviado;
                }else{
                    $nomb_opciones[] = $option->options;
                }

                $id_opciones[]  =  $option->id;
                $valores_opciones = $this->pollOptionDetailRepo->getResultForOption($option->id,$ubigeo);
                $storesOption[$option->id] = $valores_opciones;
                $myArray = collect($valores_opciones);
                $count_opciones[] = $myArray->count();
            }
            $results2 = array('type'=>'option','poll_id'=>$poll_id,'opciones'=>$nomb_opciones,'count'=>$count_opciones,'options_id'=>$id_opciones,'storesOption'=>$storesOption);
            if ($type==3)
            {
                $results3 = array('type'=>'sino/option','poll_id'=>$poll_id,'si'=>$results1['si'],'no'=>$results1['no'],'storesSi'=>$storesSi,'storesNo'=>$storesNo,'opciones'=>$nomb_opciones,'count'=>$count_opciones,'options_id'=>$id_opciones,'storesOption'=>$storesOption);
                return $results3;
            }
            return $results2;
        }

    }

    public function changeSiNo(Request $request)
    {
        $valoresPost= $request->all();//dd($valoresPost);
        $poll_details_id = $valoresPost['poll_details_id'];
        $result = $valoresPost['result'];
        $user_id = $valoresPost['user_id'];

        $objLog = $this->logProcesseRepo->getModel();
        $objLog->processes = 'sod';
        $objLog->status = 1;

        $objLog->user_id = $user_id;
        $objLog->type_operation = 'updated';
        $objLog->table_bd = 'poll_details';
        $objLog->reference_id = $poll_details_id;


        $objPollDetails = $this->pollDetailRepo->show($poll_details_id);
        $objLog->result_origin = $objPollDetails->result;
        $objPollDetails->result=$result;
        $objLog->result_new = $result;
        $objPollDetails->save();

        $objLog->company_id = $objPollDetails->company_id;
        $objLog->auditor_id = $objPollDetails->auditor;
        $objLog->publicity_id = $objPollDetails->publicity_id;
        $objLog->product_id = $objPollDetails->product_id;
        $objLog->poll_id = $objPollDetails->poll_id;
        $objLog->store_id = $objPollDetails->store_id;
        $objLog->sino = 1;
        $objLog->save();
        return 1;
    }

    public function getProcessingOsa(Request $request)
    {
        //$study_id,$user_id
        $valoresPost= $request->all();//dd($valoresPost);
        $study_id = $valoresPost['study_id'];
        $user_id = $valoresPost['user_id'];

        $regs = $this->pollDetailRepo->getOsaPolls($study_id,$user_id);
        return $regs;
    }

    public function getFilterOsaAll(Request $request)
    {
        $valoresPost= $request->all();//dd($valoresPost);
        $study_id = $valoresPost['study_id'];
        $user_id = $valoresPost['user_id'];
        $regs = $this->pollDetailRepo->getFilterAllOsa($study_id,$user_id);
        return $regs;
    }

    public function getFiltersProductsOsaAll(Request $request)
    {
        $valoresPost= $request->all();//dd($valoresPost);
        $study_id = $valoresPost['study_id'];
        $regs = $this->pollDetailRepo->getFiltersProductsOsa($study_id);
        return $regs;
    }

    public function getEffectivePointsOsa(Request $request)
    {
        $valoresPost= $request->all();//dd($valoresPost);
        $study_id = $valoresPost['study_id'];
        $user_id = $valoresPost['user_id'];
        $month = $valoresPost['month'];
        $week = $valoresPost['week'];
        $office = $valoresPost['office'];
        $dex = $valoresPost['dex'];
        $regs = $this->pollDetailRepo->puntosEfectivosOsa($study_id,$user_id,$month,$week,$office,$dex);
        return $regs;
    }

    public function calculateOsaOosVoid(Request $request)
    {
        $valoresPost= $request->all();//dd($valoresPost);
        $study_id = $valoresPost['study_id'];
        $user_id = $valoresPost['user_id'];
        $month = $valoresPost['month'];
        $week = $valoresPost['week'];
        $office = $valoresPost['office'];
        $dex = $valoresPost['dex'];
        $category_id = $valoresPost['category_id'];
        $product_id = $valoresPost['product_id'];
        $regsOsa = $this->pollDetailRepo->calculateDataOsa($study_id,$user_id,$month,$week,$office,$dex,$category_id,$product_id);$contOsa=0;
        foreach ($regsOsa as $item) {
            $contOsa = $item->osa;
            $contOOS = $item->oss;
            $contVoid = $item->void;
            $stores = $item->stores;
            $noHayStock = $item->poo_otion_orden_3;
            $incorrecto = $item->poo_otion_orden_1;
            $almacen = $item->poo_otion_orden_2;
            $noHacePedido = $item->poo_otion_orden_6;
            $noHaLLegado = $item->poo_otion_orden_7;
            $pedidoInsuficiente = $item->poo_otion_orden_8;
            $pedidoDias1a3 = $item->poo_otion_orden_9;
            $pedidoDias4amas = $item->poo_otion_orden_10;
            $acaboDias1a4 = $item->poo_otion_orden_11;
            $acaboDias5a10 = $item->poo_otion_orden_12;
            $acaboDias11amas = $item->poo_otion_orden_13;
        }
        //$regsOosVoid = $this->pollDetailRepo->calculateOosVoid($study_id,$user_id,$month,$week,$office,$dex,$category_id,$product_id);$contOOS=0;$contVoid=0;

        $totalValues = $contOsa + $contOOS  + $contVoid;

        if ($totalValues>0)
        {
            $regs[] = array('opcion'=>'OSA','valor'=>$contOsa,'porcent'=>round(($contOsa/$totalValues)*100,0));
            $regs[] = array('opcion'=>'OOS','valor'=>$contOOS,'porcent'=>round(($contOOS/$totalValues)*100,0));
            //$regs[] = array('opcion'=>'VOID','valor'=>$contVoid,'porcent'=>round(($contVoid/$totalValues)*100,0));
        }else{
            $regs[] = array('opcion'=>'OSA','valor'=>$contOsa,'porcent'=>0);
            $regs[] = array('opcion'=>'OOS','valor'=>$contOOS,'porcent'=>0);
            //$regs[] = array('opcion'=>'VOID','valor'=>$contVoid,'porcent'=>0);
        }

        $totalValuesOptions = $contOOS;

        if($totalValuesOptions>0)
        {
            $regs1[] = array('opcion'=>'No hay stock','valor'=>$noHayStock,'porcent'=>round(($noHayStock/$totalValuesOptions)*100,0));
            $regs1[] = array('opcion'=>'Lugar Incorrecto','valor'=>$incorrecto,'porcent'=>round(($incorrecto/$totalValuesOptions)*100,0));
            $regs1[] = array('opcion'=>'Está en almacén','valor'=>$almacen,'porcent'=>round(($almacen/$totalValuesOptions)*100,0));
        }else{
            $regs1[] = array('opcion'=>'No hay stock','valor'=>$noHayStock,'porcent'=>0);
            $regs1[] = array('opcion'=>'Lugar Incorrecto','valor'=>$incorrecto,'porcent'=>0);
            $regs1[] = array('opcion'=>'Está en almacén','valor'=>$almacen,'porcent'=>0);
        }

        $totalNohayStock = $noHacePedido + $noHaLLegado + $pedidoInsuficiente;

        if ($totalNohayStock>0)
        {
            $regs2[] = array('opcion'=>'Ya no hace pedido','valor'=>$noHacePedido,'porcent'=>round(($noHacePedido/$totalNohayStock)*100,0));
            $regs2[] = array('opcion'=>'Aún no llega pedido','valor'=>$noHaLLegado,'porcent'=>round(($noHaLLegado/$totalNohayStock)*100,0));
            $regs2[] = array('opcion'=>'Hizo pedido insuficiente','valor'=>$pedidoInsuficiente,'porcent'=>round(($pedidoInsuficiente/$totalNohayStock)*100,0));
        }else{
            $regs2[] = array('opcion'=>'No hay stock','valor'=>$noHacePedido,'porcent'=>0);
            $regs2[] = array('opcion'=>'Lugar Incorrecto','valor'=>$noHaLLegado,'porcent'=>0);
            $regs2[] = array('opcion'=>'Está en almacén','valor'=>$pedidoInsuficiente,'porcent'=>0);
        }

        $totalHaceCuantoPedido = $pedidoDias1a3 + $pedidoDias4amas;

        if ($totalHaceCuantoPedido>0)
        {
            $regs3[] = array('opcion'=>'1 a 3 días','valor'=>$pedidoDias1a3,'porcent'=>round(($pedidoDias1a3/$totalHaceCuantoPedido)*100,0));
            $regs3[] = array('opcion'=>'4 días a más','valor'=>$pedidoDias4amas,'porcent'=>round(($pedidoDias4amas/$totalHaceCuantoPedido)*100,0));
        }else{
            $regs3[] = array('opcion'=>'1 a 3 días','valor'=>$pedidoDias1a3,'porcent'=>0);
            $regs3[] = array('opcion'=>'4 días a más','valor'=>$pedidoDias4amas,'porcent'=>0);
        }

        $totalTiempoSeAcabo = $acaboDias1a4 + $acaboDias5a10 + $acaboDias11amas;

        if ($totalTiempoSeAcabo>0)
        {
            $regs4[] = array('opcion'=>'1 a 4 días','valor'=>$acaboDias1a4,'porcent'=>round(($acaboDias1a4/$totalTiempoSeAcabo)*100,0));
            $regs4[] = array('opcion'=>'5 a 10 días','valor'=>$acaboDias5a10,'porcent'=>round(($acaboDias5a10/$totalTiempoSeAcabo)*100,0));
            $regs4[] = array('opcion'=>'11 días a más','valor'=>$acaboDias11amas,'porcent'=>round(($acaboDias11amas/$totalTiempoSeAcabo)*100,0));
        }else{
            $regs4[] = array('opcion'=>'1 a 4 días','valor'=>$acaboDias1a4,'porcent'=>0);
            $regs4[] = array('opcion'=>'5 a 10 días','valor'=>$acaboDias5a10,'porcent'=>0);
            $regs4[] = array('opcion'=>'11 días a más','valor'=>$acaboDias11amas,'porcent'=>0);
        }

        $values = array('graph1'=>array('type'=>1,'datos'=>$regs),'graph2'=>array('type'=>2,'datos'=>$regs1),'graphNoHayStock'=>array('type'=>2,'datos'=>$regs2),'graphNoHacePedido'=>array('type'=>1,'datos'=>$regs3),'graphTiempoAcabo'=>array('type'=>1,'datos'=>$regs4),'stores'=>$stores);
        return $values;
    }

    public function getValuesOsaCategoriesProducts(Request $request)
    {
        $valoresPost= $request->all();//dd($valoresPost);
        $study_id = $valoresPost['study_id'];
        $user_id = $valoresPost['user_id'];
        $month = $valoresPost['month'];
        $week = $valoresPost['week'];
        $office = $valoresPost['office'];
        $dex = $valoresPost['dex'];
        $category_id = $valoresPost['category_id'];
        $product_id = $valoresPost['product_id'];

        $regsOsa = $this->pollDetailRepo->calculateOsaCategories($study_id,$user_id,$month,$week,$office,$dex,$category_id,$product_id);

        foreach ($regsOsa as $item) {
            $contOsa = $item->osa;
            $contOOS = $item->oss;
            // $contVoid = $item->void;
            $contVoid = 0;
            $total_product = $item->total_products;
            $category = $item->fullname_category;

            $totalValues = $contOsa + $contOOS  + $contVoid;

            $regs[] = array('category_product_id'=>$item->category_product_id,'fullname_category'=>$category,'total_products'=>$total_product,'OSA'=>$contOsa,'porcentOsa'=>round(($contOsa/$totalValues)*100,0),'OOS'=>$contOOS,'porcentOos'=>round(($contOOS/$totalValues)*100,0),'VOID'=>$contVoid,'porcentVoid'=>round(($contVoid/$totalValues)*100,0));
        }

        return $regs;

    }

    public function getValuesOsaProductsCategories(Request $request)
    {
        $valoresPost= $request->all();//dd($valoresPost);
        $study_id = $valoresPost['study_id'];
        $user_id = $valoresPost['user_id'];
        $month = $valoresPost['month'];
        $week = $valoresPost['week'];
        $office = $valoresPost['office'];
        $dex = $valoresPost['dex'];
        $category_id = $valoresPost['category_id'];
        $product_id = $valoresPost['product_id'];

        $regsOsa = $this->pollDetailRepo->calculateOsaProducts($study_id,$user_id,$month,$week,$office,$dex,$category_id,$product_id);

        foreach ($regsOsa as $item) {
            $contOsa = $item->osa;
            $contOOS = $item->oss;
            $contVoid = $item->void;
            $category = $item->fullname_category;

            $totalValues = $contOsa + $contOOS  + $contVoid;

            $regs[] = array('category_product_id'=>$item->category_product_id,'fullname_category'=>$category,'product_id'=>$item->product_id,'eam'=>$item->eam,'fullname_product'=>$item->fullname_product,'OSA'=>$contOsa,'porcentOsa'=>round(($contOsa/$totalValues)*100,0),'OOS'=>$contOOS,'porcentOos'=>round(($contOOS/$totalValues)*100,0),'VOID'=>$contVoid,'porcentVoid'=>round(($contVoid/$totalValues)*100,0));
        }

        return $regs;

    }

    public function getValuesOsaRanking1(Request $request)
    {
        $valoresPost= $request->all();//dd($valoresPost);
        $study_id = $valoresPost['study_id'];
        $user_id = $valoresPost['user_id'];
        $month = $valoresPost['month'];
        $week = $valoresPost['week'];
        $office = $valoresPost['office'];
        $dex = $valoresPost['dex'];
        $category_id = $valoresPost['category_id'];
        $product_id = $valoresPost['product_id'];

        $regsOsa = $this->pollDetailRepo->calculateRankingFFVV($study_id,$user_id,$month,$week,$office,$dex,$category_id,$product_id);

        foreach ($regsOsa as $item) {
            $contOsa = $item->osa;
            $contOOS = $item->oss;
            $contVoid = $item->void;
            $group = $item->group;

            $totalValues = $contOsa + $contOOS  + $contVoid;

            $regs[] = array('group'=>$group,'OSA'=>$contOsa,'porcentOsa'=>round(($contOsa/$totalValues)*100,0),'OOS'=>$contOOS,'porcentOos'=>round(($contOOS/$totalValues)*100,0),'VOID'=>$contVoid,'porcentVoid'=>round(($contVoid/$totalValues)*100,0));
        }

        return $regs;
    }

    public function getEvolutivoOsa(Request $request)
    {
        $valoresPost= $request->all();//dd($valoresPost);
        $study_id = $valoresPost['study_id'];
        $user_id = $valoresPost['user_id'];
        $month = $valoresPost['month'];
        $week = $valoresPost['week'];
        $office = $valoresPost['office'];
        $dex = $valoresPost['dex'];
        $category_id = $valoresPost['category_id'];
        $product_id = $valoresPost['product_id'];
        $regsOsa = $this->pollDetailRepo->calculateEvolutiveOsa($study_id,$user_id,$month,$week,$office,$dex,$category_id,$product_id);

        foreach ($regsOsa as $item) {
            $contOsa = $item->osa;
            $contOOS = $item->oos;
            $contVoid = $item->void;
            $fecha = $item->fortnight;

            $totalValues = $contOsa + $contOOS  + $contVoid;

            //$regs[] = array('date'=>$fecha,'OSA'=>$contOsa,'porcentOsa'=>round(($contOsa/$totalValues)*100,0),'OOS'=>$contOOS,'porcentOos'=>round(($contOOS/$totalValues)*100,0),'VOID'=>$contVoid,'porcentVoid'=>round(($contVoid/$totalValues)*100,0));
            $regs[] = array('date'=>$fecha,'OSA'=>$contOsa,'porcentOsa'=>round(($contOsa/$totalValues)*100,0),'OOS'=>$contOOS,'porcentOos'=>round(($contOOS/$totalValues)*100,0));
        }


        $values = array('graph3'=>array('type'=>3,'datos'=>$regs));
        return $values;


    }

    public function getVendorsForGroup(Request $request)
    {
        $valoresPost= $request->all();//dd($valoresPost);
        $study_id = $valoresPost['study_id'];
        $user_id = $valoresPost['user_id'];
        $month = $valoresPost['month'];
        $week = $valoresPost['week'];
        $office = $valoresPost['office'];
        $dex = $valoresPost['dex'];
        $category_id = $valoresPost['category_id'];
        $product_id = $valoresPost['product_id'];
        $group = $valoresPost['group'];
        $regsOsa = $this->pollDetailRepo->getVendorsOsa($study_id,$user_id,$month,$week,$office,$dex,$category_id,$product_id,$group);

        foreach ($regsOsa as $item) {
            $contOsa = $item->osa;
            $contOOS = $item->oss;
            $contVoid = $item->void;
            $group = $item->group;
            $cod_vendor = $item->cod_vendor;

            $totalValues = $contOsa + $contOOS  + $contVoid;

            $regs[] = array('group'=>$group,'cod_vendor'=>$cod_vendor,'OSA'=>$contOsa,'porcentOsa'=>round(($contOsa/$totalValues)*100,0),'OOS'=>$contOOS,'porcentOos'=>round(($contOOS/$totalValues)*100,0),'VOID'=>$contVoid,'porcentVoid'=>round(($contVoid/$totalValues)*100,0));
        }


        return $regs;


    }

    public function getClientsOsa(Request $request)
    {
        $valoresPost= $request->all();//dd($valoresPost);
        $study_id = $valoresPost['study_id'];
        $user_id = $valoresPost['user_id'];
        $month = $valoresPost['month'];
        $week = $valoresPost['week'];
        $office = $valoresPost['office'];
        $dex = $valoresPost['dex'];
        $category_id = $valoresPost['category_id'];
        $product_id = $valoresPost['product_id'];

        $regsOsa = $this->pollDetailRepo->getClientsOsa($study_id,$user_id,$month,$week,$office,$dex,$category_id,$product_id);

        foreach ($regsOsa as $item) {
            $contOsa = $item->osa;
            $contOOS = $item->oss;
            $contVoid = $item->void;

            $totalValues = $contOsa + $contOOS  + $contVoid;

            $regs[] = array('store_id'=>$item->store_id,'vendors'=>$item->vendors,'codclient'=>$item->codclient,'OSA'=>$contOsa,'porcentOsa'=>round(($contOsa/$totalValues)*100,0),'OOS'=>$contOOS,'porcentOos'=>round(($contOOS/$totalValues)*100,0),'VOID'=>$contVoid,'porcentVoid'=>round(($contVoid/$totalValues)*100,0));
        }

        return $regs;

    }

    public function getDexOsa(Request $request)
    {
        $valoresPost= $request->all();//dd($valoresPost);
        $study_id = $valoresPost['study_id'];
        $user_id = $valoresPost['user_id'];
        $month = $valoresPost['month'];
        $week = $valoresPost['week'];
        $office = $valoresPost['office'];
        $dex = $valoresPost['dex'];
        $category_id = $valoresPost['category_id'];
        $product_id = $valoresPost['product_id'];

        $regsOsa = $this->pollDetailRepo->getDexOsa($study_id,$user_id,$month,$week,$office,$dex,$category_id,$product_id);

        foreach ($regsOsa as $item) {
            $contOsa = $item->osa;
            $contOOS = $item->oss;
            $contVoid = $item->void;

            $totalValues = $contOsa + $contOOS  + $contVoid;

            $regs[] = array('distributor'=>$item->distributor,'OSA'=>$contOsa,'porcentOsa'=>round(($contOsa/$totalValues)*100,0),'OOS'=>$contOOS,'porcentOos'=>round(($contOOS/$totalValues)*100,0),'VOID'=>$contVoid,'porcentVoid'=>round(($contVoid/$totalValues)*100,0));
        }

        return $regs;

    }

}
