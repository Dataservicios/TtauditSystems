<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PollDetailRepo;
use App\Repositories\PollOptionDetailRepo;
use App\Repositories\PollOptionRepo;

class PollOptionDetailsController extends Controller
{
    protected $pollDetailRepo;
    protected $pollOptionDetailRepo;
    protected $pollOptionRepo;

    public function __construct(PollOptionRepo $pollOptionRepo,PollOptionDetailRepo $pollOptionDetailRepo,PollDetailRepo $pollDetailRepo)
    {
        // set the model
        $this->pollDetailRepo = $pollDetailRepo;
        $this->pollOptionDetailRepo = $pollOptionDetailRepo;
        $this->pollOptionRepo = $pollOptionRepo;
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

    public function insertOption(Request $request)
    {
        $valoresPost= $request->all();//dd($valoresPost);
        $store_id = $valoresPost['store_id'];
        $company_id = $valoresPost['company_id'];
        $poll_option_id = $valoresPost['poll_option_id'];
        $auditor = $valoresPost['auditor'];
        $publicity_id = $valoresPost['publicity_id'];
        $product_id = $valoresPost['product_id'];
        $objPollOptionDetails = $this->pollOptionDetailRepo->getModel();
        $objPollOptionDetails->result=1;
        $objPollOptionDetails->store_id=$store_id;
        $objPollOptionDetails->company_id=$company_id;
        $objPollOptionDetails->poll_option_id=$poll_option_id;
        $objPollOptionDetails->auditor=$auditor;
        $objPollOptionDetails->publicity_id=$publicity_id;
        $objPollOptionDetails->product_id=$product_id;
        $objPollOptionDetails->save();
        return 1;
    }

    public function deleteOption(Request $request)
    {
        $valoresPost= $request->all();
        $poll_option_details_id = $valoresPost['poll_option_details_id'];
        $objPollOptionDetails = $this->pollOptionDetailRepo->delete($poll_option_details_id);
        return 1;
    }

}
