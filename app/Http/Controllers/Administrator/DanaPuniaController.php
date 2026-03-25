<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\User;
use App\Models\Banjar;
use App\Models\Danapunia;

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

class DanaPuniaController extends BaseController
{
    

        public function list_datapunia_wajib(Request $request){
            $month = (date("m") < 9) ? "0".date("m") : date("m"); 
            $awal  = date("Y")."-".$month."-"."01";
            $akhir = date("Y")."-".$month."-"."31";

            $lists = Danapunia::get_dataPunia_inDate($request , $awal , $akhir);
            $datalist = $lists;
            
            // print_r($datalist);
            
            // return;

            //echo $datalist;
            return view('admin.pages.data_punia_wajib.table' ,compact('datalist'));
        }
        
        public function list_datapunia_wajib_param(Request $request , $months,$year){
            $month = ($months < 9) ? "0".$months : $months; 
            $awal  = $year."-".$months."-"."01";
            $akhir = $year."-".$months."-"."31";
            
            // echo $awal." ".$akhir;
            // return;

            $datalist = Danapunia::get_dataPunia_inDate($request , $awal , $akhir);

            //echo $datalist;
             return view('admin.pages.data_punia_wajib.table' ,compact('datalist'));
        }

        public function download_pdf_danapunia(Request $request){
            $data = array();

            $usaha = Usaha::get_dataUsaha($request);
            $data["usaha"] = $usaha;

            $pdf = PDF::loadView('pdf.laporan_sumbangan.laporan', $data);
            return $pdf->download('laporan_sumbangan.pdf');
            //return view('admin.pages.download_pdf.table' ,compact('datalist'));
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

            return view('admin.pages.data_banjar.table' ,compact('datalist'));
        }

}
?>