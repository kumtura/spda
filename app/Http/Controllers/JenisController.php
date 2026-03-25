<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\Ikan;
use App\Models\Jenis;

use DB;
use File;
use Carbon;
use View;
use Blade;

use App\Helper\Helper;

use App\Mail\EmailSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

class JenisController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function ambil_listjenis(Request $request){
    	$data = Jenis::orderBy('id_jenis_ikan' , 'desc')->get();

    	$dt = array();
    	$no = 1; 
    	foreach($data as $row){
    		$rows = array();

    		$rows["id_jenis_ikan"] = $no;
    		$rows["nama_jenis"] = $row->nama_jenis;
    		$rows["id_nelayan"] = $row->id_nelayan;
    		$rows["aksi"] = "<a onclick='editdataModal(".$row->id_jenis_ikan.")' style='cursor:pointer;'><i class='fa fa-pencil'></i></a> &nbsp; <a onclick='deletedataikan(".$row->id_jenis_ikan.")' style='cursor:pointer;'><i class='fa fa-trash'></i></a>";
    		$rows["aktif"] = $row->aktif;

    		$dt[] = $rows;

    		$no++;
    	}

    	return response()->json($dt); 
    }

    public function post_jenisikan(Request $request){
    	 $list=new Jenis;
		 $list->nama_jenis=$request->input('textinput');
		 $list->id_nelayan="1";
		 $list->aktif="1";
		
		 $list->save();

		 echo "success";
		 //return redirect('view-kategori-barang');
    }

    public function ambil_jenisikan(Request $request , $index){
    	 $data = Jenis::where('id_jenis_ikan' , $index)->orderBy('id_jenis_ikan' , 'desc')->firstOrfail();

		 echo json_encode($data);
		 //return redirect('view-kategori-barang');
    }

    public function update_jenisikan(Request $request){
	//$admin=tb_admin::findOrFail($id);
		$halaman="tb_customer";
		//echo $request->input('nama');
		//$idx = $request->session()->get('id');
		Jenis::where('id_jenis_ikan', $request->input('idikaninput'))->update(array(
	            'nama_jenis' =>  $request->input('textinput_edit')));

		//return redirect('view-kategori-barang');
		echo "success";
	}

    public function destroy(Request $request){
	$halaman="tb_admin";
	//$admin_list=tb_admin::findOrFail($id_admin);
	//$idx = $id;
	$admin_list=Ikan::where('id_ikan', '=' ,$request->input('id_ikan'))->delete();
	//$admin_list->delete();
	//return redirect('admin/merk');
	echo "success";
}


}
