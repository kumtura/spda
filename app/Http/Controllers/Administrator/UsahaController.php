<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Barryvdh\DomPDF\Facade as PDF;

use App\Models\User;
use App\Models\Banjar;
use App\Models\Danapunia;
use App\Models\Usaha;
use App\Models\Detail_Usaha;
use App\Models\Penanggung_Jawab;
use App\Models\Kategori_Usaha;

use App\Models\Sumbangan;
use App\Models\Jadwal_Interview;
use App\Services\BagiHasilService;


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

class UsahaController extends BaseController
{

        public function ambil_listUsaha(Request $request){
            $usaha = Usaha::get_dataUsaha($request);
            $kategori = Kategori_Usaha::get_kategoriusaha();
            $banjar = Banjar::get_databanjar($request);
            
            // print_r($usaha);
            // return;
            
             return view('admin.pages.data_usaha.table',compact('usaha','kategori','banjar'));
            //$datalist = Banjar::get_databanjar($request);
            //echo $datalist;
        }

        public function setting()
        {
            return view('admin.pages.data_usaha.setting');
        }

        public function updateSetting(Request $request)
        {
            $request->validate([
                'punia_usaha_global' => 'required|numeric|min:0'
            ]);

            $settingsPath = storage_path('app/settings.json');
            $settings = json_decode(file_get_contents($settingsPath), true);
            $settings['punia_usaha_global'] = $request->punia_usaha_global;
            
            // Sync bagi hasil to tb_pengaturan_bagi_hasil
            $keDesa = $request->has('punia_usaha_ke_desa');
            $tipeKeDesa = $request->input('punia_usaha_tipe_ke_desa', 'persentase');
            $nilaiKeDesa = (float) $request->input('punia_usaha_nilai_ke_desa', 0);
            
            // Keep settings.json in sync for display
            $settings['punia_usaha_ke_desa'] = $keDesa;
            $settings['punia_usaha_tipe_ke_desa'] = $tipeKeDesa;
            $settings['punia_usaha_nilai_ke_desa'] = $nilaiKeDesa;
            file_put_contents($settingsPath, json_encode($settings, JSON_PRETTY_PRINT));
            
            // Sync to database (the actual source of truth for BagiHasilService)
            if ($keDesa && $tipeKeDesa === 'persentase' && $nilaiKeDesa > 0) {
                $persenDesa = $nilaiKeDesa;
                
                // Deactivate old global usaha setting
                \App\Models\PengaturanBagiHasil::where('jenis_punia', 'usaha')
                    ->whereNull('id_data_banjar')
                    ->where('aktif', 1)
                    ->update(['aktif' => 0]);
                
                // Create new setting
                \App\Models\PengaturanBagiHasil::create([
                    'jenis_punia' => 'usaha',
                    'id_data_banjar' => null,
                    'persen_desa' => $persenDesa,
                    'persen_banjar' => 100 - $persenDesa,
                    'berlaku_sejak' => now()->toDateString(),
                    'keterangan' => 'Disinkronkan dari pengaturan punia usaha',
                ]);
            }

            return redirect()->back()->with('success', 'Pengaturan punia usaha berhasil diupdate');
        }
        
        public function get_detailUsaha(Request $request , $index){
            $rows = Usaha::get_detailUsaha($index);
            $sumbangan = Sumbangan::get_detailUsaha($request , $index);
            
            $arr_sts_punia = array();
            $arr_danas = array();
            
            for($an = 1; $an <= 12; $an++){
                $awal = $an;
                
                if($an < 10){
                    $awal = "0".$an;    
                }
                
                $awal_bln  = date("Y")."-".$awal."-01";
                $akhir_bln = date("Y")."-".$awal."-31";
                
                //echo $awal_bln."<br />".$akhir_bln."<p></p>";
                
                $cek_dana = Danapunia::where("id_usaha",$index)->where("tanggal_pembayaran", ">=" , $awal_bln)->where("tanggal_pembayaran", "<=" , $akhir_bln)->count();
                
                //echo "tes".$cek_dana."<p></p>";
                
                if($cek_dana > 0){
                    array_push($arr_sts_punia,"y");
                    
                    $dana_rows = Danapunia::where("id_usaha",$index)->where("tanggal_pembayaran", ">=" , $awal_bln)->where("tanggal_pembayaran", "<=" , $akhir_bln)->firstOrfail();
                    
                    array_push($arr_danas,$dana_rows);
                }
                else{
                    array_push($arr_sts_punia,"n");
                    //array_push($arr_sts_punia,"y");
                }
                
                
            }
            $kategori = Kategori_Usaha::get_kategoriusaha();
            $banjar = Banjar::get_databanjar($request);
            $total_usaha = Sumbangan::get_datasumbangan_usahaIndex($request,$index);
            $usaha_karyawan = Jadwal_Interview::get_datakaryawan_usaha($request,$index);
            
            //print_r($arr_danas);
            //return;
            
            return view('admin.pages.data_usaha.detail',compact('rows','arr_sts_punia','arr_danas','kategori','sumbangan','banjar','total_usaha','usaha_karyawan'));
            //$datalist = Banjar::get_databanjar($request);
            //echo $datalist;
        }
        
        public function submit_post_add_usaha(Request $request){
            $usaha = Usaha::post_data_usaha($request);
            //echo "tes";
            
            return redirect("administrator/data_usaha");
           // return view('admin.pages.data_usaha.table',compact('usaha'));
        }
        
        public function post_search_usaha(Request $request){
            $usaha = Usaha::post_search_usaha($request);
            //echo "tes";

            echo json_encode($usaha);
            
            //return redirect("administrator/data_usaha");
           // return view('admin.pages.data_usaha.table',compact('usaha'));
        }
        
        public function approve_usaha(Request $request , $index){
            $usaha = Usaha::approve_usaha($index);
            //echo "tes";
            return redirect("administrator/data_usaha");;
        }

        public function upload_gambar_usaha(Request $request , $index){
            $usaha = Detail_Usaha::update_logo_usaha($request , $index);
            
            echo $usaha;
        }
        
        public function update_post_add_usaha(Request $request){
            $usaha = Usaha::update_data_usaha($request);
            
            // Handle logo upload if provided via the edit modal
            if ($request->hasFile('logo_usaha')) {
                $usahaRecord = Usaha::where('id_usaha', $request->tb_hidden_usaha)->first();
                if ($usahaRecord) {
                    Detail_Usaha::update_logo_usaha($request, $usahaRecord->id_detail_usaha);
                }
            }
            
            return redirect("administrator/data_usaha");
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

        // Kelian: Detail Usaha (mobile view)
        public function kelian_detailUsaha(Request $request, $index)
        {
            $rows = Usaha::get_detailUsaha($index);
            return view('backend.kelian.detail_usaha', compact('rows'));
        }

        // Kelian: Store new Usaha
        public function kelian_storeUsaha(Request $request)
        {
            $request->validate([
                'text_title_new' => 'required|string|max:255',
                'cmb_kategori_usaha' => 'required',
                'text_namapngg_new' => 'required|string|max:255',
                'text_username_new' => 'required|string|max:255',
                'text_password_new' => 'required|string|min:6',
            ]);

            Usaha::post_data_usaha($request);

            return redirect('administrator/kelian/data_usaha')->with('success', 'Unit usaha berhasil ditambahkan.');
        }

        // Kelian: Manual payment for punia
        public function kelian_manualPayment(Request $request)
        {
            $request->validate([
                'id_usaha' => 'required|integer',
                'bulan' => 'required|integer|min:1|max:12',
                'tahun' => 'required|integer',
                'jumlah_dana' => 'required|numeric|min:1000',
                'tanggal_pembayaran' => 'required|date',
                'metode_pembayaran' => 'required|string|in:tunai,transfer,qris',
            ]);

            $buktiFile = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $buktiFile = 'manual_' . time() . '_' . $request->id_usaha . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('bukti_pembayaran'), $buktiFile);
            }

            $danapunia = Danapunia::create([
                'id_usaha' => $request->id_usaha,
                'jumlah_dana' => $request->jumlah_dana,
                'tanggal_pembayaran' => $request->tanggal_pembayaran,
                'bulan_punia' => $request->bulan,
                'tahun_punia' => $request->tahun,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'metode_pembayaran' => $request->metode_pembayaran,
                'metode' => $request->metode_pembayaran,
                'bukti_pembayaran' => $buktiFile,
                'status_pembayaran' => 'completed',
                'status_verifikasi' => 'approved',
                'aktif' => '1',
            ]);

            // Split bagi hasil
            $usaha = Usaha::with('detail')->find($request->id_usaha);
            $idBanjar = $usaha && $usaha->detail ? $usaha->detail->id_banjar : null;
            if ($idBanjar) {
                BagiHasilService::splitPayment(
                    'usaha',
                    $danapunia->id_dana_punia,
                    $idBanjar,
                    $request->jumlah_dana,
                    $request->metode_pembayaran === 'tunai' ? 'cash' : $request->metode_pembayaran,
                    $request->tanggal_pembayaran
                );
            }

            return redirect('administrator/kelian/detail_usaha/' . $request->id_usaha)
                ->with('success', 'Pembayaran bulan ' . $request->bulan . '/' . $request->tahun . ' berhasil disimpan.');
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
        

        public function index(Request $request){

            // $datalist = Banjar::get_databanjar($request);

            // echo $datalist;
            $datalist = Banjar::get_databanjar($request);

            return view('admin.pages.data_banjar.table' ,compact('datalist'));
        }

}
?>