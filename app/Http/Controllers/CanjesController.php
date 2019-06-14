<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ExchangeRepo;

class CanjesController extends Controller
{
    protected $exchangeRepo;

    public function __construct(ExchangeRepo $exchangeRepo)
    {
        // set the model
        //$this->model = new Repository($company);
        $this->exchangeRepo = $exchangeRepo;
        //$this->middleware('auth');

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

        $valuesGrapg3[] = $graphs_result_clients;
      

        $array_graph3 = array('type'=>4,'datos'=>$valuesGrapg3,'series'=>$series_graph);

       
        return array('reg'=>$nombre_campana,'nro_reg'=>$cant_reg,'graph_office'=>$graphs_offices,'count_offices'=>$cant_offices,'graph_office_invoice'=>$graphs_office_invoices,'count_office_invoices'=>$cant_invoices,'graph1'=>array('type'=>2,'datos'=>$graphs_types_docs),'count_type_doc'=>count($graphs_types_docs),'graph2'=>array('type'=>2,'datos'=>$graphs_result_tickets),'graph3'=>$array_graph3);

    }


    public function scalasCanjes(Request $request)
    {
        $valoresPost= $request->all();
        $company_id = $valoresPost['company_id'];
        $ubigeo = $valoresPost['ubigeo'];
        $datos_graphs = $this->exchangeRepo->getDatosGraphScala($company_id,$ubigeo);$acum=0;$c=0;
        foreach ($datos_graphs as $item) {
            if ($item->type==1)
            {
                $acum = $acum + intval($item->total);//acumula registros por oficina
                $graph_types[] = array('scale'=>$item->scale,'total' => intval($item->total));
            }
        }
        $graphs_result_clients = array('bloque'=>'Nro. Canjes por Escala');
        foreach ($graph_types as $item) {
            $totalValue = $item['total'];
            $valuePorcent = round(($totalValue/$acum)*100,2);
            $graphs_result_clients += ["$totalValue"=>$valuePorcent];
            $series_graph[] = array($totalValue,$item['scale']);
        }

        $valuesGrapg3[] = $graphs_result_clients;unset($graphs_result_clients);
      
        //grafico 2
        $c=0;$valor='';$valorPdv=[];
        foreach ($datos_graphs as $item) {
            $c=$c+1;
            if ($item->type==2)
            {
                //$acum = $acum + intval($item->total);//acumula registros por oficina
                if ($item->pdv_description<>$valor)
                {
                    $valor=$item->pdv_description;
                    if (in_array($valor, $valorPdv))
                    {
                        
                    }else{
                        $valorPdv[] =$valor;
                    }
                }
                //$graph_types[] = array('pdv'=>$item->pdv_descripton,'total' => intval($item->total));
            }
        }
        unset($graph_types);
        //dd($datos_graphs);
        foreach ($valorPdv as $pdv)
        {
            foreach ($datos_graphs as $item) {
                if ($item->type==2)
                {
                    if ($item->pdv_description == $pdv)
                    {
                        $acum = $acum + intval($item->total);//acumula registros por oficina
                        $graph_types[] = array('scale'=>$item->scale,'pdv'=>$pdv,'total' => intval($item->total));
                    }
                    
                }
            }
            //dd($valorPdv,$pdv,$datos_graphs);
            $graphs_result_clients = array('bloque'=>$pdv);
            foreach ($graph_types as $item) {
                if ($item['pdv'] == $pdv)
                {
                    $totalValue = $item['total'];
                    $valuePorcent = round(($totalValue/$acum)*100,2);
                    $graphs_result_clients += [$item['scale']=>$totalValue];
                    //$series_graph2[] = array($item['scale']." - ".$totalValue,$item['scale']);
                }
                
            }
            $valuesGrapg4[] = $graphs_result_clients;unset($graphs_result_clients);$acum=0;
            //$series_graph2[] = array($item['scale']." - ".$totalValue,$item['scale']);
        }

        $valorEscala=[];
        foreach ($graph_types as $item) {
            $valorEscala[$item['scale']] = 0;
        }
        foreach ($graph_types as $item) {
            $valorEscala[$item['scale']] = $valorEscala[$item['scale']] + $item['total'];
        }

        foreach($valorEscala as $index => $item)
        {
            $series_graph2[] = array($index,$index);
        }
        
        //dd($series_graph2);
        $array_graph3 = array('type'=>4,'datos'=>$valuesGrapg3,'series'=>$series_graph);
        $array_graph4 = array('type'=>4,'datos'=>$valuesGrapg4,'series'=>$series_graph2);
        return array('graph1'=>$array_graph3,'graph2'=>$array_graph4);
    }

    
}
