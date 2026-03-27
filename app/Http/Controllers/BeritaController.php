<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



use App\Models\Ikan;
use App\Models\Berita;

use App\Models\Warta;

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

class BeritaController extends BaseController
{
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function tgl_indo($tanggal){

        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);
        
        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun
     
        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];


    }

    public function ambil_listberita(Request $request){
    	$amb = Berita::orderBy('id_berita' , 'asc')->firstOrfail();

        $session = Session::get('level');
        $id_user = Session::get('idloginpt');

        if($session == "2"){
    	   $data = Berita::where('id_kategori_berita' , $amb->id_kategori_berita)->where('id_wartawan' , $id_user)->orderBy('id_berita' , 'desc')->get();
        }
        else{
            $data = Berita::where('id_kategori_berita' , $amb->id_kategori_berita)->orderBy('id_berita' , 'desc')->get();
        }

    	$dt = array();
    	$no = 1; 
    	foreach($data as $row){
    		$rows = array();

    		$url_video = url('public/berita/video/'.$row->video);
    		$img_berita = url('storage/berita/foto/'.$row->foto);

            $huruf = "kmb";
            $kodeWartawan = $huruf .  sprintf("%03s", $row->id_wartawan);

            $isi_berita = $row->isi_berita;

            if($isi_berita == "" || $isi_berita == null){
                $isi_berita = " <center> - Tidak ada keterangan - </center>";
            }

            $rows["kode_wartawan"] = $kodeWartawan;
    		$rows["id_berita"] = $row->id_berita;
            $rows["approved"] = $row->approved;
            $rows["sudah_update"] = $row->sudah_update;
    		$rows["judul_berita"] = $row->judul_berita;
    		$rows["tanggal"] = $row->hari." , ".$row->tanggal_berita;
    		$rows["slug"] = $row->slug;
    		$rows["isi_berita"] = $isi_berita;
    		$rows["urlfoto"] = $img_berita;
    		$rows["aksi"] = "<a href='".$url_video."' style='cursor:pointer;'><i class='fa fa-pencil'></i></a> &nbsp; <a onclick='deletedataikan(".$row->id_berita.")' style='cursor:pointer;'><i class='fa fa-trash'></i></a>";
    		$rows["foto"] = $row->foto;
    		$rows["aktif"] = $row->aktif;

    		$dt[] = $rows;

    		$no++;
    	}

    	return response()->json($dt); 
    }


    
    public function ambil_listwarta(Request $request){
    	$amb = Warta::orderBy('id_warta_berita' , 'asc')->firstOrfail();

        $session = Session::get('level');
        $id_user = Session::get('idloginpt');

        $data = Warta::orderBy('id_warta_berita' , 'desc')->get();

    	$dt = array();
    	$no = 1; 
    	foreach($data as $row){
    		$rows = array();

    		$img_berita = url('public/berita/foto/'.$row->foto);

    		$rows["id_warta_berita"] = $row->id_warta_berita;
            $rows["title"] = $row->title;
            $rows["hari"] = $row->hari;
    		$rows["tanggal"] = $this->tgl_indo($row->tanggal);
    		$rows["keterangan"] = $row->keterangan;
    		$rows["foto"] = $row->foto;
    		$rows["id_ibadah"] = $row->id_ibadah;
    		$rows["aktif"] = $row->aktif;

    		$dt[] = $rows;

    		$no++;
    	}

    	return response()->json($dt); 
    }

    public function ambil_listibadah(Request $request){
    	//$amb = Berita::orderBy('id_berita' , 'asc')->firstOrfail();
        $session = Session::get('level');
        $id_user = Session::get('idloginpt');

        $data = Ibadah::orderBy('id_ibadah' , 'desc')->get();
 
    	$dt = array();
    	$no = 1; 
    	foreach($data as $row){
    		$rows = array();

            $tanggal = explode(" " , $row->tanggal_berita);

            $rows["title"] = $row->title;
    		$rows["id_jenis_file"] = $row->id_jenis_file;
            $rows["keterangan"] = $row->keterangan;
            $rows["link"] = $row->link;
    		$rows["file"] = $row->file;
            $rows["id_ibadah"] = $row->id_ibadah;

    		$rows["created_at"] = $this->tgl_indo($tanggal[0]);

    		$dt[] = $rows;

    		$no++;
    	}

    	return response()->json($dt); 
    }

    public function ambil_listberita_kategori(Request $request){
    	//$amb = Berita::orderBy('id_berita' , 'asc')->firstOrfail();
        $session = Session::get('level');
        $id_user = Session::get('idloginpt');

    	if($request->cari != "" || $request->cari != null){
            if($session == "2"){
                if($request->status_update == "3"){
                    $data = Berita::with('kategori')->where(function($q) use ($request) {
                        if($request->id != "") $q->where('id_kategori_berita', $request->id);
                    })->where('judul_berita' , 'like' , "%".trim($request->cari)."%")->orderBy('id_berita' , 'desc')->where('id_wartawan' , $id_user)->where('approved' , '1')->get();
                }
                else{
                    if($request->status_update != "2"){
                      $data = Berita::with('kategori')->where(function($q) use ($request) {
                        if($request->id != "") $q->where('id_kategori_berita', $request->id);
                    })->where('judul_berita' , 'like' , "%".trim($request->cari)."%")->where('sudah_update' , $request->status_update)->orderBy('id_berita' , 'desc')->where('sudah_update' , $request->status_update)->where('id_wartawan' , $id_user)->get();
                    }
                    else{
                        $data = Berita::with('kategori')->where(function($q) use ($request) {
                            if($request->id != "") $q->where('id_kategori_berita', $request->id);
                        })->where('judul_berita' , 'like' , "%".trim($request->cari)."%")->orderBy('id_berita' , 'desc')->where('id_wartawan' , $id_user)->get();
                    }
                }
            }
            else{
                 if($request->status_update == "3"){
                    $data = Berita::where(function($q) use ($request) {
                        if($request->id != "") $q->where('id_kategori_berita', $request->id);
                    })->where('judul_berita' , 'like' , "%".trim($request->cari)."%")->where('approved' , '1')->orderBy('id_berita' , 'desc')->get();
                    
                 }
                 else{
                    if($request->status_update != "2"){
 
            		  $data = Berita::with('kategori')->where(function($q) use ($request) {
                        if($request->id != "") $q->where('id_kategori_berita', $request->id);
                    })->where('judul_berita' , 'like' , "%".trim($request->cari)."%")->where('sudah_update' , $request->status_update)->orderBy('id_berita' , 'desc')->get();
                    }
                    else{
                        $data = Berita::with('kategori')->where(function($q) use ($request) {
                            if($request->id != "") $q->where('id_kategori_berita', $request->id);
                        })->where('judul_berita' , 'like' , "%".trim($request->cari)."%")->orderBy('id_berita' , 'desc')->get();
                    }
                }
            }
    	}
    	else{

            if($session == "2"){
                if($request->status_update == "3"){
                    $data = Berita::with('kategori')->where(function($q) use ($request) {
                        if($request->id != "") $q->where('id_kategori_berita', $request->id);
                    })->where('approved' , '1')->orderBy('id_berita' , 'desc')->where('id_wartawan' , $id_user)->get();
                 }
                 else{

                    if($request->status_update != "2" && $request->status_update != ""){
                      $data = Berita::with('kategori')->where(function($q) use ($request) {
                        if($request->id != "") $q->where('id_kategori_berita', $request->id);
                    })->where('sudah_update' , $request->status_update)->where('id_wartawan' , $id_user)->orderBy('id_berita' , 'desc')->get();
                    }
                    else{
                      $data = Berita::with('kategori')->where(function($q) use ($request) {
                        if($request->id != "") $q->where('id_kategori_berita', $request->id);
                    })->where('id_wartawan' , $id_user)->orderBy('id_berita' , 'desc')->get();
                    }

                }

            }
            else{
                if($request->status_update == "3"){
                    $data = Berita::with('kategori')->where(function($q) use ($request) {
                        if($request->id != "") $q->where('id_kategori_berita', $request->id);
                    })->where('approved' , '1')->orderBy('id_berita' , 'desc')->get();
                 }
                 else{

                if($request->status_update != "2" && $request->status_update != ""){
                  $data = Berita::where(function($q) use ($request) {
                    if($request->id != "") $q->where('id_kategori_berita', $request->id);
                })->where('sudah_update' , $request->status_update)->orderBy('id_berita' , 'desc')->get();
                }
                else{
        		  $data = Berita::where(function($q) use ($request) {
                    if($request->id != "") $q->where('id_kategori_berita', $request->id);
                })->orderBy('id_berita' , 'desc')->get();
                }

                }
            }
    	}

    	$dt = array();
    	$no = 1; 
    	foreach($data as $row){
    		$rows = array();

    		$url_video = url('public/berita/video/'.$row->video);
    		$img_berita = url('storage/berita/foto/'.$row->foto);

            $huruf = "kmb";
            $kodeWartawan = $huruf. sprintf("%03s", $row->id_wartawan);

            $rows["kode_wartawan"] = $kodeWartawan;
    		$rows["id_berita"] = $row->id_berita;
            $rows["sudah_update"] = $row->sudah_update;
            $rows["approved"] = $row->approved;
    		$rows["judul_berita"] = $row->judul_berita;
            $rows["nama_kategori_berita"] = $row->kategori->nama_kategori_berita ?? 'Uncategorized';
    		$rows["tanggal"] = $row->hari." , ".$row->tanggal_berita;
    		$rows["slug"] = $row->slug;

    		$rows["isi_berita"] = "<div style='height:200px; overflow:auto'>".$row->isi_berita."</div>";
    		$rows["urlfoto"] = $img_berita;
    		$rows["aksi"] = "<a href='".$url_video."' style='cursor:pointer;'><i class='fa fa-pencil'></i></a> &nbsp; <a onclick='deletedataikan(".$row->id_berita.")' style='cursor:pointer;'><i class='fa fa-trash'></i></a>";
    		$rows["foto"] = $row->foto;
    		$rows["aktif"] = $row->aktif;

    		$dt[] = $rows;

    		$no++;
    	}

    	return response()->json($dt); 
    }

    public function post_ikan(Request $request){
    	 $list=new Ikan;
		 $list->nama=$request->input('textinput');
		 $list->harga=$request->input('hargainput');
		 $list->aktif="1";
		
		 $list->save();

		 echo "success";
		 //return redirect('view-kategori-barang');
    }


    public function edit_berita(Request $request , $index){
    	 $data = Berita::where('id_berita' , $index)->orderBy('id_berita' , 'desc')->firstOrfail();

		 
		 //return redirect('');
		 return view('backend.modul_berita.edit_berita' , compact('data'));
    }

    public function ambil_berita(Request $request , $index){



    	$data = Berita::where('id_berita' , $index)->orderBy('id_berita' , 'desc')->firstOrfail();

    	echo json_encode($data);

    }

    public function ambil_ibadah(Request $request , $index){

    	$data = Ibadah::where('id_ibadah' , $index)->orderBy('id_ibadah' , 'desc')->firstOrfail();

    	echo json_encode($data);

    }

    public function tambahberita(Request $request){
    	//echo $request->input('DSC');
    	//return;
    	$profile      = $request->file('uploadinput');
        
        $filename     = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789").time() . '.' . $profile->guessExtension();
        
        $profile->move(storage_path('app/public/berita/foto'), $filename);

        // $video      = $request->file('videoinput');
        
        // $videoname     = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789").time() . '.' . $video->guessExtension();
        
        // $video->move(public_path('berita/video'), $videoname);

	    $list          				= new Berita;
	    $list->judul_berita    		= $request->input('judul');
	    $list->slug        			= $request->input('judul');
	    $list->isi_berita   		= $request->input('DSC');
	    $list->id_kategori_berita   = $request->input('kategori');
	    $list->hari        			= $request->input('hari');
	    $list->tanggal_berita       = $request->input('tanggal')." ".$request->input('waktu');
	    $list->foto        			= $filename;
	    $list->video        		= "";
	    $list->aktif   				= "1";
	
	    $list->save();
		 //return view('admin_perikanan/berita');
		echo $request->input('kategori');
    }


    public function tambahwarta(Request $request){
    	//echo $request->input('DSC');
    	//return;
    	$profile      = $request->file('uploadinput');
        
        $filename     = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789").time() . '.' . $profile->guessExtension();
        
        $profile->move(public_path('warta'), $filename);

        $img = Image::make(public_path('warta/'.$filename))->resize(300, 200);


        $img->save(public_path('warta/thumbnail/thumb_'.$filename));

	    $list          				= new Warta;
	    $list->title    		    = $request->input('judul');
	    $list->keterangan        	= $request->input('DSC');
	    $list->foto   		        = $filename;
        $list->tanggal              = $request->input('tanggal');
	    $list->id_ibadah            = 0;
	    $list->aktif   				= "1";
	
	    $list->save();
		 //return view('admin_perikanan/berita');
		echo $request->input('judul');
    }


    public function tambahibadah(Request $request){
    	//echo $request->input('DSC');
    	//return;
    	// $profile      = $request->file('uploadinput');
        
        // $filename     = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789").time() . '.' . $profile->guessExtension();
        
        // $profile->move(public_path('berita/foto'), $filename);

        // $video      = $request->file('videoinput');
        
        // $videoname     = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789").time() . '.' . $video->guessExtension();
        
        // $video->move(public_path('berita/video'), $videoname);

        $filename = "";

        if($request->file('uploadinput') != null){

            $profile      = $request->file('uploadinput');
            
            $filename     = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789").time() . '.' . $profile->guessExtension();
            
            $profile->move(public_path('ibadah_pdf'), $filename);

        }

	    $list          				= new Ibadah;
	    $list->title    		    = $request->input('judul');
	    $list->id_jenis_file        = $request->input('kategori');
	    $list->keterangan   		= $request->input('DSC');
	    $list->link                 = $request->input('link');
	    $list->hari        			= $request->input('hari');
	    $list->tanggal_berita       = $request->input('tanggal')." ".$request->input('waktu');
	    $list->file        			= $filename;
	    $list->aktif   				= "1";
	
	    $list->save();
		 //return view('admin_perikanan/berita');
		echo $request->input('kategori');
    }

    public function update_ibadah(Request $request){

        if($request->file('uploadinput') != null){

            $profile      = $request->file('uploadinput');
            
            $filename     = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789").time() . '.' . $profile->guessExtension();
            
            $profile->move(public_path('ibadah_pdf'), $filename);

            Ibadah::where('id_ibadah', $request->input('t_idberita'))->update(array(
                'title' =>  $request->input('judul') , 'keterangan' =>  $request->input('DSC') , 'id_jenis_file' => $request->input('kategori'), 'link' => $request->input('link'),'tanggal_berita' => $request->input('tanggal'),'file' => $filename));


        }
        else{

            Ibadah::where('id_ibadah', $request->input('t_idberita'))->update(array(
                'title' =>  $request->input('judul') , 'keterangan' =>  $request->input('DSC') , 'id_jenis_file' => $request->input('kategori'), 'link' => $request->input('link'),'tanggal_berita' => $request->input('tanggal')));

            echo $request->input('kategori');

        }
        
            
    }

    public function hapus_ibadah(Request $request){

        Ibadah::where('id_ibadah' , $request->id)->delete();
    }

    public function updateberita(Request $request){
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
        
            $profile->move(public_path('berita/foto'), $filename);
    
    
    
            Berita::where('id_berita', $request->input('t_idberita'))->update(array(
                    'judul_berita' =>  $request->input('judul') , 'foto' => $filename , 'slug' =>  $request->input('judul') , 'isi_berita' => $request->input('DSC'), 'id_kategori_berita' => $request->input('kategori')));
    
    
        }
        else{

            $filename     = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789").time() . '.' . $profile->guessExtension();
        
            $profile->move(public_path('berita/foto'), $filename);
    
    
    
            Berita::where('id_berita', $request->input('t_idberita'))->update(array(
                    'judul_berita' =>  $request->input('judul') , 'slug' =>  $request->input('judul') , 'isi_berita' => $request->input('DSC'), 'id_kategori_berita' => $request->input('kategori')));
    
    
        }
        
       
		

        echo $request->input('kategori');

		//return redirect('view-kategori-barang');
		//return redirect('admin_perikanan/berita');
	}

    public function destroy(Request $request){
		$halaman="tb_admin";
		$admin_list=Ikan::where('id_ikan', '=' ,$request->input('id_ikan'))->delete();
		echo "success";
	}

    public function hapusberita(Request $request){
        Berita::where('id_berita', $request->input('id'))->delete();
        echo "success";
    }


}
