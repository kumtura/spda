<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\User;
use App\Models\Banjar;
use App\Models\Karyawan;
use App\Models\Skill_TenagaKerja;
use App\Models\List_Skill_Tk;

use App\Models\Jadwal_Interview;

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

class KaryawanController extends BaseController
{

        public function index(Request $request){
            //$usaha = Usaha::get_dataUsaha($request);
            $karyawan = Karyawan::get_dataKaryawan($request,0);
            $banjar   = Banjar::get_databanjar($request);
            //$banjar = Banjar::get_databanjar($request);
            $tenaga_kerja = Skill_TenagaKerja::get_dataTenagaKerja($request);

            return view('admin.pages.data_tenagakerja.table' ,compact('karyawan','tenaga_kerja','banjar'));
        }

        public function upload_gambar_karyawan(Request $request , $index){
            $usaha = Karyawan::upload_gambar_karyawan($request , $index);
            
            echo $usaha;
        }

        public function indexInterview(Request $request){
            //$usaha = Usaha::get_dataUsaha($request);
            $karyawan = Karyawan::get_dataKaryawan_Interview($request,1);
            //$banjar = Banjar::get_databanjar($request);
            $tenaga_kerja = Skill_TenagaKerja::get_dataTenagaKerja($request);

            return view('admin.pages.data_tenagakerja.tableinterview' ,compact('karyawan','tenaga_kerja'));
        }

        public function approve_data_karyawan(Request $request){
            $karyawan = Jadwal_Interview::approve_data_karyawan($request);

            echo "success";
        }

        public function indexApprove(Request $request){
            //$usaha = Usaha::get_dataUsaha($request);
            $karyawan = Karyawan::get_dataKaryawan_Approved($request,1);
            //$banjar = Banjar::get_databanjar($request);
            $tenaga_kerja = Skill_TenagaKerja::get_dataTenagaKerja($request);

            return view('admin.pages.data_tenagakerja.tableDiterima' ,compact('karyawan','tenaga_kerja'));
        }

        public function index_skill(Request $request){
            $skill_kerja = Skill_TenagaKerja::get_dataTenagaKerja($request);

            return view('admin.pages.data_tenagakerja.skilltable' ,compact('skill_kerja'));
        }

        public function post_data_skill(Request $request){
            $karyawan = Skill_TenagaKerja::post_data_skill($request);

            return redirect("administrator/data_tenagakerja_skill");
        }

        public function post_data_skill_new(Request $request){
            $karyawan = Skill_TenagaKerja::post_data_skill_new($request);

            echo $karyawan;
        }

        public function post_data_edit_skill(Request $request){
            $karyawan = Skill_TenagaKerja::post_data_edit_skill($request);

            return redirect("administrator/data_tenagakerja_skill");
        }

        public function hapus_skill(Request $request,$index){
            $karyawan = Skill_TenagaKerja::hapus_skill($index);

            return redirect("administrator/data_tenagakerja_skill");
        }

        public function submit_post_add_tenagakerja(Request $request){
            $karyawan = Karyawan::submit_dataKaryawan($request);

            $skill    = List_Skill_Tk::post_data_list_tk($karyawan,$request);

            return redirect("administrator/data_tenagakerja");
        }


        public function update_post_add_tenagakerja(Request $request){
            $karyawan = Karyawan::update_post_add_tenagakerja($request);

            $skill    = List_Skill_Tk::update_data_list_tk($request->t_hidden_idtext,$request);

            return redirect("administrator/data_tenagakerja");
        }

        public function submit_hire_tenaga(Request $request){
            $karyawan = Jadwal_Interview::post_add_tenagakerja_hire($request);

            return redirect("administrator/data_tenagakerja_interview");
        }


        public function approve_data_tk(Request $request){
            $karyawan = Jadwal_Interview::approve_data_tk($request);

            return redirect("administrator/data_tenagakerja_approve");
        }

        public function detail_tenaga_kerja(Request $request,$index){
            $rows = Karyawan::get_detailKaryawan($request,$index);
            $tenaga_kerja = Skill_TenagaKerja::get_dataTenagaKerja($request);
            //$banjar = Banjar::get_databanjar($request);
            $skill_kerja = List_Skill_Tk::get_dataTenagaKerja_list($request,$index);

            return view('admin.pages.data_tenagakerja.detail' ,compact('rows','tenaga_kerja','skill_kerja'));
        }

}
?>