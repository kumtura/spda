<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\User;
use App\Models\Menu;

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

class MenuController extends BaseController
{

        public function ambil_listmenu(Request $request){

            $datalist = Menu::get_datamenu($request);

            echo json_encode($datalist);

        }

        public function post_data_menu(Request $request){

        if($request->t_id_menu == ""){
            $datalist = Menu::post_data_menu($request);
        }
        else{
            $datalist = Menu::post_editdata_menu($request);
        }

            return redirect("administrator/datamenu");
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
            $datalist = Menu::get_datamenu($request);

            return view('admin.pages.data_menu.table' ,compact('datalist'));
        }

}
?>