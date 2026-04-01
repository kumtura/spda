<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\User;
use App\Models\Banjar;

use DB;
use File;
use Carbon;
use View;
use Blade;
use Hash;

use App\Helper\Helper;

use App\Mail\EmailSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

use Session;

class BanjarController extends BaseController
{

        public function ambil_listbanjar(Request $request){

            $datalist = Banjar::get_databanjar($request);

            return response()->json($datalist);

        }

        public function post_data_banjar(Request $request){

        if($request->t_id_banjar == ""){
            $datalist = Banjar::post_data_banjar($request);
        }
        else{
            $datalist = Banjar::post_editdata_banjar($request);
        }

            return redirect("administrator/databanjar");
        }

        public function hapusbanjar(Request $request){

            if($request->id != ""){
                Banjar::post_hapus_banjar($request);
            }
            

            echo "success";
    
                //return redirect("administrator/databanjar");
        }

        

        public function index(Request $request){

            // $datalist = Banjar::get_databanjar($request);

            // echo $datalist;
            $datalist = Banjar::get_databanjar($request);
            $kelianUsers = User::whereIn('id_level', [
                config('myconfig.level.bendesa', 1), 
                config('myconfig.level.kelian', 2)
            ])->get();

            return view('admin.pages.data_banjar.table' ,compact('datalist', 'kelianUsers'));
        }

}
?>