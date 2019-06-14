<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 27/03/2019
 * Time: 16:21
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\VersionRepo;


class VersionController extends Controller
{
    protected $versionRepo;

    public function __construct(VersionRepo $versionRepo)
    {
        $this->versionRepo = $versionRepo;
    }

    public function versionsForVigent(Request $request)
    {

        $valoresPost= $request->all();//dd($valoresPost);
        $vigente = $valoresPost['vigente'];
        $versiones = $this->versionRepo->getRegsVersions($vigente);
        $myArray = collect($versiones);
        $groupCompanies = $myArray->groupBy('company_id');
        foreach ($groupCompanies as $key=> $groupCompany) {
            $comboCompanies[] =array('company_id'=>$key,'campaigne'=>$groupCompany[0]->fullname);
        }
        $dataFull = array('comboCompany'=>$comboCompanies,'data'=>$groupCompanies);

        return $dataFull;
    }
}
