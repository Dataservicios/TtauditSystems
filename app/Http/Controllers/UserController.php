<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepo;
use App\Repositories\ModuleRepo;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Closure;

class UserController extends Controller
{
    protected $userRepo;
    protected $moduleRepo;

    public function __construct(ModuleRepo $moduleRepo,UserRepo $userRepo)
    {
        // set the model
        //$this->model = new Repository($company);
        $this->userRepo = $userRepo;
        $this->moduleRepo = $moduleRepo;
        //$this->middleware('auth');

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


    public function getUserForType($type)
    {
        $auditors = $this->userRepo->listUserCondition($type);

        return $auditors;
    }

    public function getUserAuthenticated()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json');
        if (Auth::user()->id) {
            $user = Auth::user();
            return $user;
        }
    }

    public function loginUser(Request $request)
    {
        $valoresPost= $request->all();
        $email = $valoresPost['email'];
        $password = $valoresPost['password'];
        $objUser = $this->userRepo->getModel();
        if ($user = $objUser::whereEmail($email)->first())
        {
            //dd($password,$user->modules);
            if (Hash::check($password,$user->password))
            {
                //dd('user: ',$user,'modules: ',$user->modules,'roles: ',$user->roles);
                if (count($user->roles)>0)
                {
                    if ($user->roles[0]->id==1)
                    {
                        //dd('user: ',$user,'modules: ',$user->modules,'roles: ',$user->roles);
                        $obj_modules1 = $this->moduleRepo->getModel();
                        $obj_modules = $obj_modules1->orderBy('orden','ASC')->get();
                        $responses =['message' => 'Bienvenid@ '.$user->fullname,'api_token' => $user->api_token,'obj_user' => $user,'obj_modules' => $obj_modules,'obj_roles' => $user->roles];
                    }else{
                        $responses =['message' => 'Bienvenid@ '.$user->fullname,'api_token' => $user->api_token,'obj_user' => $user,'obj_modules' => $user->modules,'obj_roles' => $user->roles];
                    }
                }else{
                    $responses =['message' => 'Bienvenid@ '.$user->fullname,'api_token' => $user->api_token,'obj_user' => $user,'obj_modules' => $user->modules,'obj_roles' => $user->roles];
                }
                
            }else{
                $responses = ['message' => 'Tu password no concuerdan con nuestros registros','api_token' => null,'obj_user' => [],'obj_modules' => [],'obj_roles' => []];
            }
        }else{
            $responses = ['message' => 'Tus email no concuerdan con nuestros registros','api_token' => null,'obj_user' => [],'obj_modules' => [],'obj_roles' => []];
        }
        

        return $responses;
    }

    
}
