<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



use App\Models\Ikan;
use App\Models\Berita;

use App\Models\Laporan;

use App\Models\Ibadah;

use DB;
use File;
use Carbon;
use View;
use Blade;

use Session;

use App\Helper\Helper;

use App\Mail\EmailSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

use Intervention\Image\ImageManagerStatic as Image;

class LaporanController extends BaseController
{
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



	public function ambil_listlaporan(Request $request){
    	//$amb = Laporan::orderBy('id_warta_berita' , 'asc')->firstOrfail();

        $session = Session::get('level');
        $id_user = Session::get('idloginpt');

        $data = Laporan::orderBy('id_laporan' , 'desc')->get();

    	$dt = array();
    	$no = 1; 
    	foreach($data as $row){
    		$rows = array();

    		$img_berita = url('public/laporan/'.$row->foto);

    		$rows["id_warta_berita"] = $row->id_warta_berita;
            $rows["title"] = $row->judul;
    		$rows["tahun"] = $row->tahun;
    		$rows["foto"] = $row->foto;
    		$rows["aktif"] = $row->aktif;

    		$dt[] = $rows;

    		$no++;
    	}

    	return response()->json($dt); 
    }
	
	public function tambahlaporan(Request $request){
    	$profile      = $request->file('uploadinput');
        $filename     = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789").time() . '.' . $profile->guessExtension();
        $profile->move(public_path('laporan'), $filename);

	    $list          				= new Laporan;
	    $list->judul    		    = $request->input('textinputan');
	    $list->file   		        = $filename;
        $list->tahun                = $request->input('tanggalinput');
	    $list->aktif   				= "1";
	
	    $list->save();
		echo "success";
    }

    public function updatelaporan(Request $request){
        $profile = $request->file('uploadinput');
        $data = [
            'judul' => $request->input('textinputan'),
            'tahun' => $request->input('tanggalinput'),
        ];

        if($profile){
            $filename = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789").time() . '.' . $profile->guessExtension();
            $profile->move(public_path('laporan'), $filename);
            $data['file'] = $filename;
        }

        Laporan::where('id_laporan', $request->input('t_idberita'))->update($data);
        echo "success";
    }

    public function hapus_laporan(Request $request){
        Laporan::where('id_laporan' , $request->id)->delete();
        echo "success";
    }
}
