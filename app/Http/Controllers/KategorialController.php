<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



use App\Models\Ikan;
use App\Models\Berita;

use App\Models\Warta;
use App\Models\Renungan;

use App\Models\Kategorial;

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

class KategorialController extends BaseController
{
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



	public function ambil_listkategorial(Request $request){
    	//$amb = Renungan::orderBy('id_renungan' , 'asc')->firstOrfail();

        $session = Session::get('level');
        $id_user = Session::get('idloginpt');

        $data = Kategorial::orderBy('id_kategorial' , 'desc')->get();

    	$dt = array();
    	$no = 1; 
    	foreach($data as $row){
    		$rows = array();

    		$img_berita = url('public/berita/judul/'.$row->foto);

    		$rows["id_kategorial"] = $row->id_kategorial;
            $rows["title"] = $row->title;
            $rows["hari"] = $row->hari;
    		$rows["tanggal"] = tgl_indo($row->tanggal);
    		$rows["keterangan"] = $row->keterangan;
    		$rows["foto"] = $row->foto;
    		$rows["aktif"] = $row->aktif;

    		$dt[] = $rows;

    		$no++;
    	}

    	return response()->json($dt); 
    }
	
	public function tambahkategorial(Request $request){
    	//echo $request->input('DSC');
    	//return;
    	$profile      = $request->file('uploadinput');
        
        $filename     = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789").time() . '.' . $profile->guessExtension();
        
        $profile->move(public_path('kategorial'), $filename);

        $img = Image::make(public_path('kategorial/'.$filename))->resize(300, 200);


        $img->save(public_path('renungan/thumbnail/thumb_'.$filename));

	    $list          				= new Kategorial;
	    $list->title    		    = $request->input('judul');
	    $list->keterangan        	= $request->input('DSC');
	    $list->foto   		        = $filename;
        $list->tanggal              = $request->input('tanggal');
	    $list->aktif   				= "1";
	
	    $list->save();
		 //return view('admin_perikanan/berita');
		echo $request->input('judul');
    }

    public function ambil_kategorial(Request $request , $index){

    	$data = Kategorial::where('id_kategorial' , $index)->orderBy('id_kategorial' , 'desc')->firstOrfail();

    	echo json_encode($data);

    }

    public function update_kategorial(Request $request){
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
            
            $profile->move(public_path('kategorial'), $filename);

            $img = Image::make(public_path('kategorial/'.$filename))->resize(300, 200);


            $img->save(public_path('kategorial/thumbnail/thumb_'.$filename));

    
                Kategorial::where('id_kategorial', $request->input('t_idberita'))->update(array(
                    'title' =>  $request->input('judul') , 'tanggal' =>  $request->input('tanggal') , 'foto' => $filename, 'keterangan' => $request->input('DSC')));

                
            }
            else{

                Kategorial::where('id_kategorial', $request->input('t_idberita'))->update(array(
                    'title' =>  $request->input('judul') , 'tanggal' =>  $request->input('tanggal') ,  'keterangan' => $request->input('DSC')));
    

            }
            
    
            echo $request->input('kategori');
    
            //return redirect('view-kategori-barang');
            //return redirect('admin_perikanan/berita');
        }

        public function hapus_renungan(Request $request){

            Kategorial::where('id_kategorial' , $request->id)->delete();
            
        }
	
}
