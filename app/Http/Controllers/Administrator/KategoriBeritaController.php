<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Barryvdh\DomPDF\Facade as PDF;

use App\Models\User;
use App\Models\Kategori_Berita;

use DB;
use File;
use Carbon;
use View;
use Blade;
use Hash;

use Config;

use App\Helper\Helper;

use App\Mail\EmailSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

use Session;

class KategoriBeritaController extends BaseController
{

        public function index(Request $request){
            $kategori   = Kategori_Berita::get_kategoriberita($request);
            
             return view('admin.pages.data_kategoriberita.table',compact('kategori'));
            //$datalist = Banjar::get_databanjar($request);
            //echo $datalist;
        }
        
        public function post_kategori_berita(Request $request){
            $usaha = Kategori_Berita::post_kategori_berita($request);
            //echo "tes";
            
            return redirect(Config::get('myconfig.devUrl')."/data_kategoriberita");
           // return view('admin.pages.data_usaha.table',compact('usaha'));
        }

        public function post_user_kategori_berita(Request $request){
            $usaha = Kategori_Berita::post_user_kategori_berita($request);
            //echo "tes";
            
            return redirect(Config::get('myconfig.devUrl')."/data_kategoriberita");
           // return view('admin.pages.data_usaha.table',compact('usaha'));
        }
        
        public function hapus_kategori_berita(Request $request){
            $usaha = Kategori_Berita::hapus_kategori_berita($request);
            //echo "tes";
            
            return redirect(Config::get('myconfig.devUrl')."/data_kategoriberita");
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
        

}
?>