<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
//use Hesto\MultiAuth\Traits\LogsoutGuard; 
use App\Kampus;
use App\JenisPT;
use Hash;

use Session;

use App\Models\Kategori;
use App\Models\User;


class LoginController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /*use AuthenticatesUsers, LogsoutGuard {
        LogsoutGuard::logout insteadof AuthenticatesUsers;
    }*/

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/adminguru/dashboardkampus';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.guest', ['except' => 'logout']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('adminguru.login');
    }
    
    

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function login(Request $request)
    {

      //GET DATA KAMPUS
      $datapt = Kampus::select(\DB::raw('id_pt, password_lama, password, username, id_jenisPT, status_pt'))->where('username', '=', $request->email)->get();

      if($datapt->count()){
        $fakultas = JenisPT::select(\DB::raw('fakultas'))->where('id_jenisPT', '=', $datapt[0]->id_jenisPT)->get();
        //CHECK APAKAH KAMPUS SUDAH MEMILIKI PASSWORD BARU
        if(isset($datapt[0]->password) && $datapt[0]->password != ""){
          //PASS BARU ADA
          return $this->runlogin($request, $fakultas, $datapt[0]->status_pt);
        } else{
          //PASS BARU KOSONG
          if($datapt[0]->password_lama == md5($request->password)){
            //UPDATE PASS BARU
            $update_passbaru = Kampus::where('id_pt',$datapt[0]->id_pt)->update(['password'=>Hash::make($request->password)]);

            //CHECK IF UPDATE PASS BARU BERHASIL
            if($update_passbaru){
                return $this->runlogin($request, $fakultas, $datapt[0]->status_pt);
            } else{
                return response()->json([
                  'status' => 'error',
                  'value'  => 'Terjadi Kesalahan, Silahkan Login Ulang'
                ]); 
            }
          } else{
            return response()->json([
              'status' => 'error',
              'value'  => 'Password Salah'
            ]); 
          }
        }
      } else{
        return response()->json([
          'status' => 'error',
          'value'  => 'Username Salah'
        ]); 
      }
    }

    public function runlogin(Request $request){
      //2->nonaktif, 4->terhapus
      //if($status_pt != '2' && $status_pt != '4' && $status_pt != '0'){
       
        if(Auth::guard('admin')->attempt(['email' => $request->username, 'password' => $request->password, 'aktif' => '1'])){ 
          
          //$guru = tb_guru::where('username' , $request->email)->where('password' , Hash::make($request->password))->where('aktif' , '1')->firstOrfail();
          
          
            $pt       = Auth::guard('admin')->user();
            //$namapt   = $pt->;
            $idpt     = $pt->id;
            
            $status = "";

            if($pt->id_level == config('myconfig.level.bendesa')){
                $status = config('myconfig.roles.1');
            }
            elseif($pt->id_level == config('myconfig.level.kelian')){
                $status = config('myconfig.roles.2');
            }
            elseif($pt->id_level == config('myconfig.level.usaha')){
                $status = config('myconfig.roles.3');
            }
            else{
                $status = "Guest";
            }
            
            Session::put('namapt', $pt->name);
            Session::put('email', $pt->email);
            Session::put('status', $status);
            Session::put('level', $pt->id_level);
            //Session::put('id_user', $pt->id_level);
            Session::put('idloginpt', $idpt);
            Session::put('boolsessionpt', 1);

            //return redirect('administrator/home');
            
           // echo "Guru : ".var_dump(Auth::guard('adminguru')->user());
            
            
            //$this->addloginlog($request, $idpt, "2");
            return response()->json([
              'status'   => 'success'
            ]); 
          } else { 
            return response()->json([
              'status' => 'error',
              'value'  => "Invalid Login"
            ]); 
          }
        
      //} 
      
    }

    public function logout(Request $request)
    {
        auth('admin')->logout();
        // session()->flush();

        session()->forget('idloginpt');
        session()->forget('namapt');
        session()->forget('email');
        session()->forget('boolsessionpt');
        session()->forget('status');
        session()->forget('level');

        
        return redirect('administrator/login');
    }
}
