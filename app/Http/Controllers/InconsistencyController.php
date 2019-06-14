<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\InconsistencyRepo;
use App\Repositories\InconsistencyDetailRepo;

class InconsistencyController extends Controller
{

    protected $inconsistencyRepo;
    protected $inconsistencyDetailRepo;

    public function __construct(InconsistencyDetailRepo $inconsistencyDetailRepo,InconsistencyRepo $inconsistencyRepo)
    {
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
        $values=$this->inconsistencyRepo->getOfficeForCampaigne($company_id);
        return $values;
    }



}
