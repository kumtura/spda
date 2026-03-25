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
    	//echo $request->input('DSC');
    	//return;
    	$profile      = $request->file('uploadinput');
        
        $filename     = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789").time() . '.' . $profile->guessExtension();
        
        $profile->move(public_path('laporan'), $filename);

        // $img = Image::make(public_path('laporan/'.$filename))->resize(300, 200);


        // $img->save(public_path('warta/thumbnail/thumb_'.$filename));

	    $list          				= new Laporan;
	    $list->judul    		    = $request->input('judul');
	    $list->file   		        = $filename;
        $list->tahun                = $request->input('tanggal');
	    $list->aktif   				= "1";
	
	    $list->save();
		 //return view('admin_perikanan/berita');
		echo $request->input('judul');
    }

    public function ambil_warta(Request $request , $index){

    	$data = Warta::where('id_warta_berita' , $index)->orderBy('id_warta_berita' , 'desc')->firstOrfail();

    	echo json_encode($data);

    }

    public function updatewarta(Request $request){
        //$admin=tb_admin::findOrFail($id);
            $halaman="tb_customer";
            //echo $request->input('nama');
            //$idx = $request->session()->get('id');
            //$profile      = $request->file('uploadinput');
    
            $level = Session::get("level");
    
            $approved = "0";
    
            if($level == "3"){
                $approved = "1";
            }

            $profile      = $request->file('uploadinput');

            if($profile != null){
        
            $filename     = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789").time() . '.' . $profile->guessExtension();
            
            $profile->move(public_path('warta'), $filename);

            $img = Image::make(public_path('warta/'.$filename))->resize(300, 200);


            $img->save(public_path('warta/thumbnail/thumb_'.$filename));

    
            Warta::where('id_warta_berita', $request->input('t_idberita'))->update(array(
                'title' =>  $request->input('judul') , 'tanggal' =>  $request->input('tanggal') , 'foto' => $filename, 'keterangan' => $request->input('DSC')));

                
            }
            else{

                Warta::where('id_warta_berita', $request->input('t_idberita'))->update(array(
                    'title' =>  $request->input('judul') , 'tanggal' =>  $request->input('tanggal') ,  'keterangan' => $request->input('DSC')));
    

            }
            
    
            echo $request->input('kategori');
    
            //return redirect('view-kategori-barang');
            //return redirect('admin_perikanan/berita');
        }

        public function hapus_warta(Request $request){

            Warta::where('id_warta_berita' , $request->id)->delete();
        }
	
}
