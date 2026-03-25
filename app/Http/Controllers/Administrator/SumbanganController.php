<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Barryvdh\DomPDF\Facade as PDF;

use App\Models\User;
use App\Models\Sumbangan;
use App\Models\Danapunia;
use App\Models\Usaha;
use App\Models\Detail_Usaha;
use App\Models\Penanggung_Jawab;
use App\Models\Kategori_Usaha;

use App\Models\Karyawan;

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

class SumbanganController extends BaseController
{

        public function index(Request $request){
            $sumbangan      = Sumbangan::get_datasumbangan($request);
            $total_anonim   = Sumbangan::get_datasumbangan_anonim($request);
            $total_usaha    = Sumbangan::get_datasumbangan_usaha($request);
            $total_karyawan = Sumbangan::get_datasumbangan_karyawan($request);

            $karyawan       = Karyawan::get_dataKaryawan_all($request);
            $usaha          = Usaha::get_dataUsaha($request);

            $total_all = $total_anonim + $total_usaha + $total_karyawan;
            
            // print_r($usaha);
            // return;
            
             return view('admin.pages.data_sumbangan.table',compact('sumbangan','total_anonim','total_usaha','total_karyawan','total_all','karyawan','usaha'));
            //$datalist = Banjar::get_databanjar($request);
            //echo $datalist;
        }
        
        public function submit_post_add_sumbangan(Request $request){
            $usaha = Sumbangan::submit_post_add_sumbangan($request);
            //echo "tes";
            
            return redirect("administrator/datasumbangan");
           // return view('admin.pages.data_usaha.table',compact('usaha'));
        }
        
        public function post_search_usaha(Request $request){
            $usaha = Usaha::post_search_usaha($request);
            //echo "tes";

            echo json_encode($usaha);
            
            //return redirect("administrator/data_usaha");
           // return view('admin.pages.data_usaha.table',compact('usaha'));
        }

        public function upload_gambar_usaha(Request $request , $index){
            $usaha = Detail_Usaha::update_logo_usaha($request , $index);
            
            echo $usaha;
        }
        
        public function update_post_add_usaha(Request $request){
            $usaha = Usaha::update_data_usaha($request);
            //echo "tes";
            
            return redirect("administrator/detail_usaha/".$request->tb_hidden_usaha);
           // return view('admin.pages.data_usaha.table',compact('usaha'));
        }
        
        public function get_pembayaran_detail(Request $request,$index){
            
            $detail_dana = Danapunia::get_detailPunia_only($index);
            
            echo json_encode($detail_dana);
            
        }
        
        public function post_pembayaran_baru(Request $request,$method){
            $usaha = Usaha::post_pembayaran_baru($request,$method);
            
            $detail_dana = Danapunia::get_detailPunia($usaha);
            
            echo json_encode($detail_dana);
            //echo "tes";
            
            //return redirect("administrator/data_usaha");
           // return view('admin.pages.data_usaha.table',compact('usaha'));
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

        public function download_usaha_pdf(Request $request){
            $data = array();

            $usaha = Usaha::get_dataUsaha($request);
            $data["usaha"] = $usaha;

            $pdf = PDF::loadView('pdf.invoice', $data);
            return $pdf->download('invoice.pdf');
        }

        public function download_pdf_sumbangan(Request $request){
            $data = array();

            $usaha = Usaha::get_dataUsaha($request);
            $data["usaha"] = $usaha;

            $pdf = PDF::loadView('pdf.laporan_sumbangan.laporan', $data);
            return $pdf->download('laporan_sumbangan.pdf');
            //return view('admin.pages.download_pdf.table' ,compact('datalist'));
        }
        

}
?>