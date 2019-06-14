<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PollDetailRepo;
use App\Repositories\PollOptionDetailRepo;
use App\Repositories\PollOptionRepo;
use App\Repositories\LogProcesseRepo;
use Illuminate\Support\Facades\Auth;

class PollOptionDetailsController extends Controller
{
    protected $pollDetailRepo;
    protected $pollOptionDetailRepo;
    protected $pollOptionRepo;
    protected $logProcesseRepo;

    public function __construct(LogProcesseRepo $logProcesseRepo,PollOptionRepo $pollOptionRepo,PollOptionDetailRepo $pollOptionDetailRepo,PollDetailRepo $pollDetailRepo)
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

    public function updateOptions(Request $request)
    {

        $valoresPost= $request->all();//dd($valoresPost);
        $pollOptionType = $valoresPost['pollOptionType'];
        $responseOptions = $valoresPost['responseOptions'];
        $optionIdSelected = $valoresPost['optionIdSelected'];
        $pollId = $valoresPost['pollId'];
        $values1 = explode(",",$responseOptions);
        //return 1;
        if ($pollOptionType==0)
        {
            foreach ($values1 as $responseOption)
            {
                if ($responseOption<>"")
                {
                    $values2 = explode("|",$responseOption);
                    if ($values2[1]<>0)
                    {
                        $objLog = $this->logProcesseRepo->getModel();
                        $objPollOptionDetail = $this->pollOptionDetailRepo->show($values2[1]);
                        $objLog->result_origin = $objPollOptionDetail->poll_option_id;
                        $objPollOptionDetail->poll_option_id=$optionIdSelected;
                        $objPollOptionDetail->save();


                        $objLog->processes = 'sod';
                        $objLog->status = 1;

                        $objLog->user_id = 5;
                        $objLog->type_operation = 'updated';
                        $objLog->table_bd = 'poll__option_details';
                        $objLog->reference_id = $values2[1];

                        $objLog->result_new = $optionIdSelected;

                        $objLog->company_id = $objPollOptionDetail->company_id;
                        $objLog->auditor_id = $objPollOptionDetail->auditor;
                        $objLog->publicity_id = $objPollOptionDetail->publicity_id;
                        $objLog->product_id = $objPollOptionDetail->product_id;
                        $objLog->poll_id = $pollId;
                        $objLog->store_id = $objPollOptionDetail->store_id;
                        $objLog->sino = 0;
                        $objLog->save();
                    }
                }
            }
        }
        return 1;
    }

}
