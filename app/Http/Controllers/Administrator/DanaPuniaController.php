<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\User;
use App\Models\Banjar;
use App\Models\Danapunia;
use App\Models\Pendatang;
use App\Models\PuniaPendatang;

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

        public function list_datapunia_pendatang(Request $request) {
            $month = date("n");
            $year = date("Y");
            return $this->list_datapunia_pendatang_param($request, $month, $year);
        }

        public function list_datapunia_pendatang_param(Request $request, $month, $year) {
            $monthKey = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
            
            $query = Pendatang::with('banjar')->where('aktif', '1')->where('status', 'aktif');
            
            if ($request->filled('banjar')) {
                $query->where('id_data_banjar', $request->banjar);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'LIKE', '%'.$search.'%')
                      ->orWhere('nik', 'LIKE', '%'.$search.'%');
                });
            }

            $pendatangList = $query->orderBy('nama', 'asc')->get();
            
            foreach($pendatangList as $p) {
                // Get payment for this specific month/year
                $payment = PuniaPendatang::where('id_pendatang', $p->id_pendatang)
                    ->where('jenis_punia', 'rutin')
                    ->where('bulan_tahun', 'LIKE', $monthKey . '%')
                    ->where('status_pembayaran', 'lunas')
                    ->where('aktif', '1')
                    ->first();
                    
                $p->payment_status = $payment ? 'lunas' : 'belum';
                $p->payment_data = $payment;
            }

            $banjarList = Banjar::where('aktif', '1')->orderBy('nama_banjar')->get();

            return view('admin.pages.data_punia_pendatang.table', compact('pendatangList', 'month', 'year', 'banjarList'));
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