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
use App\Models\Usaha;
use App\Models\PengaturanBagiHasil;

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
            $month = date("n");
            $year = date("Y");
            return $this->render_dashboard_punia($request, $month, $year);
        }
        
        public function list_datapunia_wajib_param(Request $request, $month, $year){
            return $this->render_dashboard_punia($request, $month, $year);
        }

        private function render_dashboard_punia(Request $request, $month, $year) {
            $monthPadded = str_pad($month, 2, '0', STR_PAD_LEFT);
            $awal = $year."-".$monthPadded."-01";
            $akhir = $year."-".$monthPadded."-31";
            $monthKey = $year . '-' . $monthPadded;
            $hasRiwayatBagiHasil = \Illuminate\Support\Facades\Schema::hasTable('tb_riwayat_bagi_hasil');

            // === GET PENGATURAN BAGI HASIL ===
            $pengaturanTamiu = \App\Models\PengaturanBagiHasil::where('jenis_punia', 'tamiu')
                ->whereNull('id_data_banjar')
                ->where('aktif', 1)
                ->orderBy('berlaku_sejak', 'desc')
                ->first();
            
            $pengaturanUsaha = \App\Models\PengaturanBagiHasil::where('jenis_punia', 'usaha')
                ->whereNull('id_data_banjar')
                ->where('aktif', 1)
                ->orderBy('berlaku_sejak', 'desc')
                ->first();

            // === UNIT USAHA DATA ===
            $usahaList = Usaha::select('tb_usaha.id_usaha', 'tb_usaha.aktif_status', 'tb_detail_usaha.nama_usaha', 'tb_detail_usaha.minimal_bayar', 'tb_penanggung_jawab.nama')
                ->join("tb_detail_usaha", "tb_detail_usaha.id_detail_usaha", "tb_usaha.id_detail_usaha")
                ->join("tb_penanggung_jawab", "tb_penanggung_jawab.id_penanggung_jawab", 'tb_usaha.id_penanggung_jawab')
                ->where('tb_usaha.aktif_status', '1')
                ->orderBy("tb_usaha.id_usaha", "desc")
                ->get();

            $usahaPaid = 0; $usahaUnpaid = 0; $totalUsaha = 0;
            $totalUsahaDesa = 0; $totalUsahaBanjar = 0;
            foreach($usahaList as $u) {
                $payment = Danapunia::where('id_usaha', $u->id_usaha)
                    ->where('aktif', '1')
                    ->where('status_pembayaran', 'completed')
                    ->where('tanggal_pembayaran', '>=', $awal)
                    ->where('tanggal_pembayaran', '<=', $akhir)
                    ->first();
                if($payment) {
                    $usahaPaid++;
                    $totalUsaha += $payment->jumlah_dana;
                    
                    // Calculate allocation if pengaturan exists
                    if ($pengaturanUsaha) {
                        $totalUsahaDesa += ($payment->jumlah_dana * $pengaturanUsaha->persen_desa / 100);
                        $totalUsahaBanjar += ($payment->jumlah_dana * $pengaturanUsaha->persen_banjar / 100);
                    }
                } else {
                    $usahaUnpaid++;
                }
            }

            if ($hasRiwayatBagiHasil) {
                $totalUsahaDesa = (float) \App\Models\RiwayatBagiHasil::where('aktif', 1)
                    ->where('jenis_punia', 'usaha')
                    ->whereDate('tanggal', '>=', $awal)
                    ->whereDate('tanggal', '<=', $akhir)
                    ->sum('nominal_desa');
                $totalUsahaBanjar = (float) \App\Models\RiwayatBagiHasil::where('aktif', 1)
                    ->where('jenis_punia', 'usaha')
                    ->whereDate('tanggal', '>=', $awal)
                    ->whereDate('tanggal', '<=', $akhir)
                    ->sum('nominal_banjar');
            }

            // === KRAMA TAMIU DATA ===
            $pendatangAktif = Pendatang::where('aktif', '1')->where('status', 'aktif')->count();
            $tamiuPaid = PuniaPendatang::where('aktif', '1')
                ->where('jenis_punia', 'rutin')
                ->where('bulan_tahun', 'LIKE', $monthKey . '%')
                ->where('status_pembayaran', 'lunas')
                ->count();
            $totalTamiu = PuniaPendatang::where('aktif', '1')
                ->where('status_pembayaran', 'lunas')
                ->where('bulan_tahun', 'LIKE', $monthKey . '%')
                ->sum('nominal');
            $totalTamiuDesa = 0; $totalTamiuBanjar = 0;
            if ($hasRiwayatBagiHasil) {
                $totalTamiuDesa = (float) \App\Models\RiwayatBagiHasil::where('aktif', 1)
                    ->where('jenis_punia', 'tamiu')
                    ->whereDate('tanggal', '>=', $awal)
                    ->whereDate('tanggal', '<=', $akhir)
                    ->sum('nominal_desa');
                $totalTamiuBanjar = (float) \App\Models\RiwayatBagiHasil::where('aktif', 1)
                    ->where('jenis_punia', 'tamiu')
                    ->whereDate('tanggal', '>=', $awal)
                    ->whereDate('tanggal', '<=', $akhir)
                    ->sum('nominal_banjar');
            } elseif ($pengaturanTamiu) {
                $totalTamiuDesa = $totalTamiu * $pengaturanTamiu->persen_desa / 100;
                $totalTamiuBanjar = $totalTamiu * $pengaturanTamiu->persen_banjar / 100;
            }
            $tamiuUnpaid = $pendatangAktif - $tamiuPaid;
            if($tamiuUnpaid < 0) $tamiuUnpaid = 0;

            // === RIWAYAT TERBARU GABUNGAN ===
            $recentUsaha = Danapunia::select('tb_dana_punia.jumlah_dana as nominal', 'tb_dana_punia.tanggal_pembayaran as tanggal', 'tb_dana_punia.metode_pembayaran', 'tb_detail_usaha.nama_usaha as nama', DB::raw("'usaha' as sumber"))
                ->join('tb_usaha', 'tb_usaha.id_usaha', 'tb_dana_punia.id_usaha')
                ->join('tb_detail_usaha', 'tb_detail_usaha.id_detail_usaha', 'tb_usaha.id_detail_usaha')
                ->where('tb_dana_punia.aktif', '1')
                ->where('tb_dana_punia.status_pembayaran', 'completed')
                ->orderBy('tb_dana_punia.tanggal_pembayaran', 'desc')
                ->limit(10)
                ->get();

            $recentTamiu = PuniaPendatang::select('nominal', 'tanggal_bayar as tanggal', 'metode_pembayaran', DB::raw("'pendatang' as sumber"))
                ->with('pendatang:id_pendatang,nama')
                ->where('aktif', '1')
                ->where('status_pembayaran', 'lunas')
                ->orderBy('tanggal_bayar', 'desc')
                ->limit(10)
                ->get()
                ->map(function($item) {
                    $item->nama = $item->pendatang->nama ?? '-';
                    return $item;
                });

            // Merge and sort
            $recentAll = $recentUsaha->concat($recentTamiu)->sortByDesc('tanggal')->take(15);

            $totalGabungan = $totalUsaha + $totalTamiu;
            $totalDesaGabungan = $totalUsahaDesa + $totalTamiuDesa;
            $totalBanjarGabungan = $totalUsahaBanjar + $totalTamiuBanjar;

            return view('admin.pages.data_punia_wajib.table', compact(
                'month', 'year',
                'usahaPaid', 'usahaUnpaid', 'totalUsaha', 'totalUsahaDesa', 'totalUsahaBanjar',
                'tamiuPaid', 'tamiuUnpaid', 'totalTamiu', 'totalTamiuDesa', 'totalTamiuBanjar',
                'totalGabungan', 'totalDesaGabungan', 'totalBanjarGabungan', 'recentAll',
                'usahaList', 'pendatangAktif',
                'pengaturanTamiu', 'pengaturanUsaha'
            ));
        }

        // === IURAN PENDATANG ===
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

        // === IURAN UNIT USAHA ===
        public function list_datapunia_usaha(Request $request) {
            $month = date("n");
            $year = date("Y");
            return $this->list_datapunia_usaha_param($request, $month, $year);
        }

        public function list_datapunia_usaha_param(Request $request, $month, $year) {
            $monthPadded = str_pad($month, 2, '0', STR_PAD_LEFT);
            $awal = $year."-".$monthPadded."-01";
            $akhir = $year."-".$monthPadded."-31";

            $query = Usaha::select('tb_usaha.id_usaha', 'tb_usaha.aktif_status', 'tb_detail_usaha.nama_usaha', 'tb_detail_usaha.email_usaha', 'tb_detail_usaha.minimal_bayar', 'tb_detail_usaha.logo', 'tb_detail_usaha.id_banjar', 'tb_penanggung_jawab.nama', 'tb_penanggung_jawab.alamat')
                ->join("tb_detail_usaha", "tb_detail_usaha.id_detail_usaha", "tb_usaha.id_detail_usaha")
                ->join("tb_penanggung_jawab", "tb_penanggung_jawab.id_penanggung_jawab", 'tb_usaha.id_penanggung_jawab')
                ->leftJoin("tb_data_banjar", "tb_data_banjar.id_data_banjar", "=", "tb_detail_usaha.id_banjar")
                ->where('tb_usaha.aktif_status', '1');

            if ($request->filled('banjar')) {
                $query->where('tb_detail_usaha.id_banjar', $request->banjar);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('tb_detail_usaha.nama_usaha', 'LIKE', '%'.$search.'%')
                      ->orWhere('tb_penanggung_jawab.nama', 'LIKE', '%'.$search.'%');
                });
            }

            $usahaList = $query->addSelect('tb_data_banjar.nama_banjar')
                ->orderBy("tb_detail_usaha.nama_usaha", "asc")
                ->get();

            foreach($usahaList as $u) {
                $payment = Danapunia::where('id_usaha', $u->id_usaha)
                    ->where('aktif', '1')
                    ->where('status_pembayaran', 'completed')
                    ->where('tanggal_pembayaran', '>=', $awal)
                    ->where('tanggal_pembayaran', '<=', $akhir)
                    ->first();
                    
                $u->payment_status = $payment ? 'lunas' : 'belum';
                $u->payment_data = $payment;
            }

            $banjarList = Banjar::where('aktif', '1')->orderBy('nama_banjar')->get();

            return view('admin.pages.data_punia_usaha.table', compact('usahaList', 'month', 'year', 'banjarList'));
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