<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\User;
use App\Models\Banjar;
use App\Models\Danapunia;
use App\Models\Usaha;
use App\Models\Detail_Usaha;
use App\Models\Penanggung_Jawab;
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

class DashboardController extends BaseController
{

    public function indexhome(Request $request){
        $level = Session::get('level');
        $usaha = Usaha::get_dataUsaha($request);
        $jml_karyawan = Karyawan::get_jmltenaga($request);

        $totalpunia = Danapunia::get_totalPunia($request);
        
        if($level == "1" || $level == "4") {
            // Bendesa Adat & Admin Sistem use the Desktop Dashboard
            return view('admin.pages.home',compact('usaha','totalpunia','jml_karyawan'));
        } else if ($level == "2") {
            // Kelian Adat uses the Mobile Dashboard with filtered data
            $kelianBanjar = Auth::user()->banjar;
            
            if(!$kelianBanjar) {
                // If no banjar assigned, show empty data
                return view('backend.kelian.home', [
                    'usaha_count' => 0,
                    'totalpunia' => 0,
                    'jml_karyawan' => 0
                ]);
            }
            
            // Get usaha count for this banjar only
            $usaha_count = Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
                ->where('tb_detail_usaha.id_banjar', $kelianBanjar->id_data_banjar)
                ->where('tb_usaha.aktif_status', '1')
                ->count();
            
            // Get tenaga kerja count for usaha in this banjar
            $usahaIds = Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
                ->where('tb_detail_usaha.id_banjar', $kelianBanjar->id_data_banjar)
                ->where('tb_usaha.aktif_status', '1')
                ->pluck('tb_usaha.id_usaha');
            
            $jml_karyawan = \App\Models\Jadwal_Interview::whereIn('id_usaha', $usahaIds)
                ->where('status_diterima', '1')
                ->where('aktif', '1')
                ->count();
            
            // Get total punia for this banjar
            $totalpunia = Danapunia::whereIn('id_usaha', $usahaIds)
                ->where('aktif', '1')
                ->where('status_pembayaran', 'completed')
                ->sum('jumlah_dana');
            
            return view('backend.kelian.home',compact('usaha_count','totalpunia','jml_karyawan'));
        } else if ($level == "3") {
            // Unit Usaha uses the Mobile Dashboard
            return view('backend.usaha.home',compact('usaha','totalpunia','jml_karyawan'));
        } else if ($level == "5") {
            // Ticket Counter redirects to ticket counter dashboard
            return redirect()->to('administrator/ticketcounter');
        }

        // Fallback to desktop home if level not specified
        return view('admin.pages.home',compact('usaha','totalpunia','jml_karyawan'));
    }
    
    public function get_danapunia_range(){
        
        $arr_punia     = array();
        $arr_sumbangan = array();
        
        $arr_bln = array("01","02","03","04","05","06","07","08","09","10","11","12");
        
        $ans = 0;
        
        foreach($arr_bln as $rows){
            $awal = date("Y")."-".$rows."-01";
            $akhir = date("Y")."-".$rows."-31";
            
            //$totals = array();
            
            
            $totalpunia_range = Danapunia::get_totalPunia_inRange($awal,$akhir);
            
            $total = 0;
            
            if($totalpunia_range != ""){
                $total = $totalpunia_range;
            }
            
            $arr_sumbangan[$ans]["punia"] = $total;
            
            $ans++;
            
            //array_push($arr_punia,$totals);
        }
        
        $arr_json = array();
        
        $arr_punias = array();
        
        $arr_punias["data_punia"] = $arr_sumbangan;
        
        $arr_json["total_punia"] = json_encode($arr_sumbangan);
        
        echo json_encode($arr_json);
        
    }

    public function verifikasi_pembayaran(Request $request)
    {
        $level = Session::get('level');
        
        // Get pending payments for punia
        $pending_punia = Danapunia::where('metode_pembayaran', 'transfer_manual')
            ->where('status_verifikasi', 'pending')
            ->with('usaha.detail')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get pending payments for donasi
        $pending_donasi = \App\Models\Sumbangan::where('metode_pembayaran', 'transfer_manual')
            ->where('status_verifikasi', 'pending')
            ->with('programDonasi')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get pending payments for tiket
        $pending_tiket = \App\Models\TiketWisata::where('metode_pembayaran', 'transfer_manual')
            ->where('status_verifikasi', 'pending')
            ->with('objekWisata')
            ->orderBy('created_at', 'desc')
            ->get();
        
        if($level == "1" || $level == "4") {
            return view('admin.pages.verifikasi_pembayaran', compact('pending_punia', 'pending_donasi', 'pending_tiket'));
        } else if ($level == "2") {
            return view('backend.kelian.verifikasi_pembayaran', compact('pending_punia', 'pending_donasi', 'pending_tiket'));
        }
        
        return view('admin.pages.verifikasi_pembayaran', compact('pending_punia', 'pending_donasi', 'pending_tiket'));
    }

    public function verifikasi_approve(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'type' => 'required|in:punia,donasi,tiket'
        ]);

        if($request->type === 'punia') {
            $payment = Danapunia::find($request->id);
            if($payment) {
                $payment->update([
                    'status_verifikasi' => 'approved',
                    'status_pembayaran' => 'completed',
                    'tanggal_pembayaran' => now()
                ]);
            }
        } elseif($request->type === 'donasi') {
            $payment = \App\Models\Sumbangan::find($request->id);
            if($payment) {
                $payment->update([
                    'status_verifikasi' => 'approved',
                    'status_pembayaran' => 'completed',
                    'tanggal' => now()
                ]);
            }
        } else {
            $payment = \App\Models\TiketWisata::find($request->id);
            if($payment) {
                $payment->update([
                    'status_verifikasi' => 'approved',
                    'status_pembayaran' => 'completed'
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi');
    }

    public function verifikasi_reject(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'type' => 'required|in:punia,donasi,tiket',
            'alasan' => 'nullable|string'
        ]);

        if($request->type === 'punia') {
            $payment = Danapunia::find($request->id);
            if($payment) {
                $payment->update([
                    'status_verifikasi' => 'rejected',
                    'catatan_verifikasi' => $request->alasan
                ]);
            }
        } elseif($request->type === 'donasi') {
            $payment = \App\Models\Sumbangan::find($request->id);
            if($payment) {
                $payment->update([
                    'status_verifikasi' => 'rejected',
                    'catatan_verifikasi' => $request->alasan
                ]);
            }
        } else {
            $payment = \App\Models\TiketWisata::find($request->id);
            if($payment) {
                $payment->update([
                    'status_verifikasi' => 'rejected',
                    'catatan_verifikasi' => $request->alasan
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pembayaran ditolak');
    }
}
