<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\User;
use App\Models\Jemaah;
use App\Models\TicketCounterAssignment;
use App\Models\ObjekWisata;

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
        
        // If unit usaha, get business details
        $usaha = null;
        $banjar = null;
        if(Session::get('level') == 3) {
            $usaha = \App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
                ->leftJoin('tb_kategori_usaha', 'tb_kategori_usaha.id_kategori_usaha', 'tb_usaha.id_jenis_usaha')
                ->where('tb_usaha.username', $datas->email)
                ->select('tb_usaha.*', 'tb_detail_usaha.*', 'tb_kategori_usaha.nama_kategori_usaha')
                ->first();
            
            $banjar = \App\Models\Banjar::where('aktif', '1')->get();
        }

        return view('admin.pages.user_profile.update_form' , compact('datas', 'usaha', 'banjar'));
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
        
        // Update User table
        if($request->input('passwordinput') == ""){
            User::where('id', $request->input('iduserinput_edit'))->update(array(
                    'name' =>  $request->input('textinput'),'email' =>  $request->input('emailinput'),'no_wa' =>  $request->input('nowainput')));
        }
        else{
            User::where('id', $request->input('iduserinput_edit'))->update(array(
                    'name' =>  $request->input('textinput'), 'password' => Hash::make($request->input('passwordinput')) , 'email' =>  $request->input('emailinput'),'no_wa' =>  $request->input('nowainput')));
        }
        
        // If unit usaha, update business details
        if(Session::get('level') == 3 && $request->has('id_detail_usaha')) {
            $updateData = [
                'nama_usaha' => $request->input('nama_usaha'),
                'email_usaha' => $request->input('email_usaha'),
                'id_banjar' => $request->input('id_banjar'),
                'no_telp' => $request->input('no_telp'),
                'no_wa' => $request->input('no_wa_usaha'),
                'alamat_banjar' => $request->input('alamat_banjar'),
                'facebook_url' => $request->input('facebook_url'),
                'twitter_url' => $request->input('twitter_url'),
                'website_url' => $request->input('website_url'),
                'google_maps' => $request->input('google_maps'),
            ];
            
            \App\Models\Detail_Usaha::where('id_detail_usaha', $request->input('id_detail_usaha'))->update($updateData);
            
            // Update logo if uploaded
            if($request->hasFile('logo_usaha')) {
                \App\Models\Detail_Usaha::update_logo_usaha($request, $request->input('id_detail_usaha'));
            }
        }

        return redirect('administrator/userprofile')->with('success', 'Profil berhasil diperbarui');
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
            else if($lvl == 5){
                $level = "Ticket Counter";
            }
            else if($lvl == 6){
                $level = "Admin Punia";
            }
            else if($lvl == 7){
                $level = "Penagih Iuran";
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
             // Bendesa/Admin can set Level and Banjar
             $list->id_level=$request->input('levelinput_edit');
             $list->id_banjar=$request->input('banjarinput_edit');
             // Admin Pura (level 6) - assign pura
             if ($request->input('levelinput_edit') == 6) {
                 $list->id_pura = $request->input('purainput_edit');
             }
         } else if ($curr_level == 2) {
             // Kelian can only create Unit Usaha (Level 3) for their own Banjar
             $list->id_level=3;
             $list->id_banjar=$curr_banjar;
         } else {
             return "error: unauthorized";
         }
		
		 $list->save();

         // Handle ticket counter assignments (Level 5)
         if ($list->id_level == 5 && $request->has('objek_wisata')) {
             foreach ($request->input('objek_wisata', []) as $objekId) {
                 TicketCounterAssignment::create([
                     'id_user' => $list->id,
                     'id_objek_wisata' => $objekId,
                     'aktif' => '1',
                 ]);
             }
         }

		 echo "success";
    }

    public function ambil_user(Request $request , $index){
    	 $data = User::where('id' , $index)->orderBy('id' , 'desc')->firstOrfail();
         
         // Include assigned objek wisata IDs for ticket counter
         $arr = $data->toArray();
         $arr['assigned_objek_ids'] = TicketCounterAssignment::where('id_user', $index)
             ->where('aktif', '1')
             ->pluck('id_objek_wisata')
             ->map(fn($v) => (int)$v)
             ->toArray();
         $arr['id_pura'] = $data->id_pura;

		 echo json_encode($arr);
    }

    public function updateuser(Request $request){
		$userId = $request->input('iduserinput_edit');
		User::where('id', $userId)->update(array(
	            'name' =>  $request->input('textinput_edit'),
                'email' =>  $request->input('emailinput_edit'),
                'id_level' =>  $request->input('levelinput_edit'),
                'id_banjar' =>  $request->input('banjarinput_edit'),
                'no_wa' =>  $request->input('nowainput_edit'),
                'id_pura' =>  $request->input('levelinput_edit') == 6 ? $request->input('purainput_edit') : null
        ));

        // Handle ticket counter assignments (Level 5)
        $level = $request->input('levelinput_edit');
        if ($level == 5) {
            // Deactivate old assignments
            TicketCounterAssignment::where('id_user', $userId)->update(['aktif' => '0']);
            // Create new assignments
            foreach ($request->input('objek_wisata', []) as $objekId) {
                TicketCounterAssignment::updateOrCreate(
                    ['id_user' => $userId, 'id_objek_wisata' => $objekId],
                    ['aktif' => '1']
                );
            }
        } else {
            // If no longer ticket counter, deactivate assignments
            TicketCounterAssignment::where('id_user', $userId)->update(['aktif' => '0']);
        }

		echo "success";
	}

    public function destroy(Request $request){
        $userId = $request->input('id');

        if (!$userId) {
            return response('error', 400);
        }

        try {
            // Deactivate ticket counter assignments
            TicketCounterAssignment::where('id_user', $userId)->update(['aktif' => '0']);

            // Cascade delete loker via usaha.user_id (kolom yang benar)
            $usahaIds = \DB::table('tb_usaha')
                ->where('user_id', $userId)
                ->pluck('id_usaha');

            if ($usahaIds->count() > 0) {
                \App\Models\Loker::whereIn('id_usaha', $usahaIds)->delete();
            }

            // Delete the user itself
            User::where('id', '=', $userId)->delete();

            return response('success', 200);
        } catch (\Exception $e) {
            \Log::error('Delete user error: ' . $e->getMessage());
            return response('error: ' . $e->getMessage(), 500);
        }
    }

    public function getObjekWisataByBanjar($id_banjar)
    {
        $data = ObjekWisata::where('id_data_banjar', $id_banjar)
            ->where('aktif', '1')
            ->select('id_objek_wisata', 'nama_objek', 'status')
            ->orderBy('nama_objek')
            ->get();

        return response()->json($data);
    }

}
