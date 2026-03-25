<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\User;
use App\Models\Kategori;

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

class KategoriController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function ambil_listkategori(Request $request){
    	$data = Kategori::where('aktif' , '1')->orderBy('id_kategori_berita' , 'desc')->get();

    	$dt = array();
    	$no = 1; 
    	foreach($data as $row){
    		$rows = array();

    		//$lvl = $row->id_level;

    		$level = "";


    		$rows["no"] = $no;
    		$rows["id_kategori_berita"] = $row->id_kategori_berita;
    		$rows["name"] = $row->nama_kategori_berita;
    		$rows["aksi"] = "<a onclick='editdataModal(".$row->id_kategori_berita.")' style='cursor:pointer;'><i class='fas fa-pencil-alt'></i> Edit </a> &nbsp; <a onclick='deletedata(".$row->id_kategori_berita.")' style='cursor:pointer;'><i class='fa fa-trash'> Delete </i></a>";
    		$rows["aktif"] = $row->aktif;

    		$dt[] = $rows;

    		$no++;
    	}

    	return response()->json($dt); 
    }


    public function ambil_listkategori_awal(Request $request){
    	$data = Kategori::where('aktif' , '1')->orderBy('id_kategori_berita' , 'asc')->get();

    	$dt = array();
    	$no = 1; 
    	foreach($data as $row){
    		$rows = array();

    		//$lvl = $row->id_level;

    		$level = "";


    		$rows["no"] = $no;
    		$rows["id_kategori_berita"] = $row->id_kategori_berita;
    		$rows["name"] = $row->nama_kategori_berita;
    		$rows["aksi"] = "<a onclick='editdataModal(".$row->id_kategori_berita.")' style='cursor:pointer;'><i class='fas fa-pencil-alt'></i> Edit </a> &nbsp; <a onclick='deletedata(".$row->id_kategori_berita.")' style='cursor:pointer;'><i class='fa fa-trash'> Delete </i></a>";
    		$rows["aktif"] = $row->aktif;

    		$dt[] = $rows;

    		$no++;
    	}

    	return response()->json($dt); 
    }



    public function post_kategori(Request $request){
    	 $list=new Kategori;

		 $list->nama_kategori_berita=$request->input('textinput');
		 $list->aktif="1";
		
		 $list->save();

		 echo "success";
		 //return redirect('view-kategori-barang');
    }

    public function ambil_kategori(Request $request , $index){
    	 $data = Kategori::where('id_kategori_berita' , $index)->orderBy('id_kategori_berita' , 'desc')->firstOrfail();

		 echo json_encode($data);
		 //return redirect('view-kategori-barang');
    }

    public function updatekategori(Request $request){
	//$admin=tb_admin::findOrFail($id);
		$halaman="tb_customer";
		//echo $request->input('nama');
		//$idx = $request->session()->get('id');
		Kategori::where('id_kategori_berita', $request->input('iduserinput_edit'))->update(array(
	            'nama_kategori_berita' =>  $request->input('textinput_edit')));

		//return redirect('view-kategori-barang');
		echo "success";
	}

    public function hapuskategori(Request $request){
	$halaman="tb_admin";
	//$admin_list=tb_admin::findOrFail($id_admin);
	//$idx = $id;
	//$admin_list=Kategori::where('id', '=' ,$request->input('id'))->delete();
	Kategori::where('id_kategori_berita', $request->input('id'))->update(array(
	            'aktif' =>  "0"));
	//$admin_list->delete();
	//return redirect('admin/merk');
	echo "success";
}


}
