<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\User;
use App\Models\Jemaah;

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

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function indexadmin(Request $request){
        return view('admin.pages.home');
    }

    public function indexuser(Request $request){
        $datas = User::where('id' , Session::get('idloginpt'))->orderBy('id' , 'desc')->firstOrfail();

        return view('front.pages.user_profile.update_form' , compact('datas'));
    }

	public function login_user(Request $request){

		$username 		= $request->username;
		$password 		= md5($request->password);

		$jml = Jemaah::where("username" , $username)->where("password",$password)->get();

		$data = [];

		$data["success"] = "success";
		$data["status"]  = "200";
		$data["userId"]  = count($jml);

		return response()->json($data);
		
	}

	public function update_user(Request $request){

		if($request->password == ""){
			Jemaah::where('email', $request->input('email'))->update(array(
				'nama' =>  $request->input('nama'),'alamat' =>  $request->input('alamat'),'phone' =>  $request->input('phone')));
		}
		else{
			Jemaah::where('email', $request->input('email'))->update(array(
				'nama' =>  $request->input('nama'),'alamat' =>  $request->input('alamat'),'password' => md5($request->password),'phone' =>  $request->input('phone')));
		}

		$data = [];

		$data["success"] = "success";
		$data["status"]  = "200";

		return response()->json($data); 
	}

	public function registrasi_user(Request $request){

		$post_data = new Jemaah;
		$post_data->nama 			= $request->nama;
		$post_data->username 		= $request->email;
		$post_data->password 		= md5($request->password);
		$post_data->email 			= $request->email;
		$post_data->alamat 			= $request->alamat;
		$post_data->phone 			= $request->phone;
		$post_data->aktif 			= "1";

		$post_data->save();

		$data = [];

		$data["success"] = "success";
		$data["status"]  = "200";

		return response()->json($data); 

	}

    public function updatepost_profile(Request $request){

        if($request->input('passwordinput') == ""){

            User::where('id', $request->input('iduserinput_edit'))->update(array(
                    'name' =>  $request->input('textinput'),'email' =>  $request->input('emailinput'),'no_wa' =>  $request->input('nowainput')));

        }
        else{

            User::where('id', $request->input('iduserinput_edit'))->update(array(
                    'name' =>  $request->input('textinput'), 'password' => Hash::make($request->input('passwordinput')) , 'email' =>  $request->input('emailinput'),'no_wa' =>  $request->input('nowainput')));

        }

       // $datas = User::where('id' , Session::get('idloginpt'))->orderBy('id' , 'desc')->firstOrfail();

        return redirect('administrator/userprofile');

    }

    public function ambil_listuser(Request $request){
    	$data = User::orderBy('id' , 'desc')->get();

    	$dt = array();
    	$no = 1; 
    	foreach($data as $row){
    		$rows = array();

    		$lvl = $row->id_level;

    		$level = "";
            $kode = "-";

    		if($lvl == 1){
    			$level = "Bendesa Adat";
    		}
    		else if($lvl == 2){
    			$level = "Kelian Adat";
    		}
            else if($lvl == 3){
                $level = "Unit Usaha";
            }
    		else{
    			$level = "Guest";
    		}

    		$rows["no"] = $no;
            $rows["kode"] = $kode;
    		$rows["name"] = $row->name;
    		$rows["email"] = $row->email;
    		$rows["no_wa"] = $row->no_wa;
    		$rows["level"] = $level;
    		$rows["aksi"] = "<a onclick='editdataModal(".$row->id.")' style='cursor:pointer;'><i class='fas fa-pencil-alt'></i> Edit </a> &nbsp; <a onclick='deletedata(".$row->id.")' style='cursor:pointer;'><i class='fa fa-trash'> Delete </i></a>";
    		$rows["aktif"] = $row->aktif;

    		$dt[] = $rows;

    		$no++;
    	}

    	return response()->json($dt); 
    }

    public function post_user(Request $request){
    	 $list=new User;

		 $list->name=$request->input('textinput');
		 $list->email=$request->input('emailinput');
		 $list->password=Hash::make($request->input('passwordinput'));
		 $list->no_wa=$request->input('nowainput');
		 $list->foto_ttd="-";
		 $list->id_level=$request->input('levelinput');
		
		 $list->save();

		 echo "success";
		 //return redirect('view-kategori-barang');
    }

    public function ambil_user(Request $request , $index){
    	 $data = User::where('id' , $index)->orderBy('id' , 'desc')->firstOrfail();

		 echo json_encode($data);
		 //return redirect('view-kategori-barang');
    }

    public function updateuser(Request $request){
	//$admin=tb_admin::findOrFail($id);
		$halaman="tb_customer";
		//echo $request->input('nama');
		//$idx = $request->session()->get('id');
		User::where('id', $request->input('iduserinput_edit'))->update(array(
	            'name' =>  $request->input('textinput_edit'),'email' =>  $request->input('emailinput_edit'),'id_level' =>  $request->input('levelinput_edit'),'no_wa' =>  $request->input('nowainput_edit')));

		//return redirect('view-kategori-barang');
		echo "success";
	}

    public function destroy(Request $request){
	$halaman="tb_admin";
	//$admin_list=tb_admin::findOrFail($id_admin);
	//$idx = $id;
	$admin_list=User::where('id', '=' ,$request->input('id'))->delete();
	//$admin_list->delete();
	//return redirect('admin/merk');
	echo "success";
}


}
