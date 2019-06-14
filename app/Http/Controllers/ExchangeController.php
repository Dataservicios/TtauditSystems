<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ExchangeRepo;
use App\Repositories\InconsistencyRepo;
use App\Repositories\InconsistencyDetailRepo;

class ExchangeController extends Controller
{

    protected $exchangeRepo;
    protected $inconsistencyRepo;
    protected $inconsistencyDetailRepo;

    public function __construct(InconsistencyDetailRepo $inconsistencyDetailRepo,InconsistencyRepo $inconsistencyRepo,ExchangeRepo $exchangeRepo)
    {
        $this->exchangeRepo = $exchangeRepo;
        $this->inconsistencyRepo = $inconsistencyRepo;
        $this->inconsistencyDetailRepo = $inconsistencyDetailRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return $this->companyRepo->all();
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

    /**
     * @param $company_id
     * @return array
     */
    public function getOfficeForCampaigne($company_id)
    {
        $values=$this->exchangeRepo->getOfficeForCampaigne($company_id);
        return $values;
    }

    public function getResultsVerifyForCompany($company_id,$ubigeo="0")
    {
        $objExchange = $this->exchangeRepo->getModel();
        if ($ubigeo=="0")
        {
            $numBase = $objExchange->where('company_id',$company_id)->count();//num reg exchange
            $objVerifi = $objExchange->where('verify',1)->where('company_id',$company_id)->get();//reg verificados en exchange
            $numVerify = $objVerifi->count();
            $objExchangeInconsistency = $objExchange->where('inconsistency',1)->where('company_id',$company_id)->get();//reg con inconsistencias en exchange
        }else{
            $numBase = $objExchange->where('company_id',$company_id)->where('ubigeo',$ubigeo)->count();
            $objVerifi = $objExchange->where('verify',1)->where('company_id',$company_id)->where('office',$ubigeo)->count();
            $numVerify = $objVerifi->count();
            $objExchangeInconsistency = $objExchange->where('inconsistency',1)->where('company_id',$company_id)->where('office',$ubigeo)->get();
        }


        $objInconsistencies = $this->inconsistencyRepo->all();//inconsistencias
        $objInconsistencyDetails = $this->inconsistencyDetailRepo->getRegForCompany($company_id,$ubigeo);//detalle inconsistencias
        $values = array('base'=>$numBase,'numVerify'=>$numVerify,'objVerifyReg'=>$objVerifi,'objInconsistencyReg'=>$objExchangeInconsistency,'objInconsistencies'=>$objInconsistencies,'objInconsistencyDetails'=>$objInconsistencyDetails);
        return $values;
    }


}
