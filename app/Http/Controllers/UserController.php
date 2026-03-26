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

        return view('admin.pages.user_profile.update_form' , compact('datas'));
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
        $level = Session::get('level');
        $user = Auth::guard('admin')->user();
        $id_banjar = $user ? $user->id_banjar : null;

        $query = User::leftJoin('tb_data_banjar', 'tb_data_banjar.id_data_banjar', '=', 'users.id_banjar')
                    ->select('users.*', 'tb_data_banjar.nama_banjar');

        // Filter based on role
        if ($level == 2) {
            // Kelian Adat only sees Unit Usaha in their Banjar
            $query->where('users.id_banjar', $id_banjar)
                  ->where('users.id_level', 3);
        }
        // Level 1 & 4 see everything.

        $data = $query->orderBy('users.id' , 'desc')->get();

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
                $level = " Unit Usaha";
            }
            else if($lvl == 4){
                $level = "Admin Sistem";
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
            $rows["id_level"] = $lvl;
            $rows["id_banjar"] = $row->id_banjar;
            $rows["nama_banjar"] = $row->nama_banjar ?? '-';
    		$rows["aksi"] = ""; 
            $rows["id"] = $row->id;
    		$rows["aktif"] = $row->aktif;

    		$dt[] = $rows;

    		$no++;
    	}

    	return response()->json($dt); 
    }

    public function post_user(Request $request){
         $curr_level = Session::get('level');
         $user = Auth::guard('admin')->user();
         $curr_banjar = $user ? $user->id_banjar : null;

    	 $list=new User;

		 $list->name=$request->input('textinput_edit');
		 $list->email=$request->input('emailinput_edit');
		 $list->password=Hash::make($request->input('passwordinput_edit'));
		 $list->no_wa=$request->input('nowainput_edit');
		 $list->foto_ttd="-";
         $list->aktif="1";

         if ($curr_level == 1 || $curr_level == 4) {
             // Bendesa/Admin can set Level and Banjar (usually for creating Kelian)
             $list->id_level=$request->input('levelinput_edit');
             $list->id_banjar=$request->input('banjarinput_edit');
         } else if ($curr_level == 2) {
             // Kelian can only create Unit Usaha (Level 3) for their own Banjar
             $list->id_level=3;
             $list->id_banjar=$curr_banjar;
         } else {
             return "error: unauthorized";
         }
		
		 $list->save();

		 echo "success";
    }

    public function ambil_user(Request $request , $index){
    	 $data = User::where('id' , $index)->orderBy('id' , 'desc')->firstOrfail();

		 echo json_encode($data);
		 //return redirect('view-kategori-barang');
    }

    public function updateuser(Request $request){
		User::where('id', $request->input('iduserinput_edit'))->update(array(
	            'name' =>  $request->input('textinput_edit'),
                'email' =>  $request->input('emailinput_edit'),
                'id_level' =>  $request->input('levelinput_edit'),
                'id_banjar' =>  $request->input('banjarinput_edit'),
                'no_wa' =>  $request->input('nowainput_edit')
        ));

		echo "success";
	}

    public function destroy(Request $request){
        $userId = $request->input('id');
        
        // Cascade delete: remove loker associated with usaha owned by this user
        $usahaIds = \DB::table('tb_usaha')
            ->join('tb_penanggung_jawab', 'tb_penanggung_jawab.id_penanggung_jawab', '=', 'tb_usaha.id_penanggung_jawab')
            ->where('tb_penanggung_jawab.id_user', $userId)
            ->pluck('tb_usaha.id_usaha');
        
        if ($usahaIds->count() > 0) {
            \App\Models\Loker::whereIn('id_usaha', $usahaIds)->delete();
        }
        
        // Delete the user itself
        User::where('id', '=', $userId)->delete();
        
        echo "success";
    }


}
