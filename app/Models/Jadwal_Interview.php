<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\Karyawan;

class Jadwal_Interview extends Model
{   
    //public $timestamps = false; 
    //
    protected $primaryKey = 'id_jadwal_interview';
    protected $fillable = [
        'id_karyawan', 
        'id_usaha',
        'id_loker',
        'tanggal_interview', 
        'jam', 
        'status_interview',
        'status_diterima', 
        'tanggal_diterima', 
        'jabatan',
        'alasan_penolakan',
        'dokumen_lamaran',
        'aktif'
    ];
    protected $table='tb_jadwal_interview';
    
    public static function approve_data_tk($request){
        
        Jadwal_Interview::where("id_jadwal_interview" , $request->id)->update(
        array("status_diterima"                         => "1",
              "tanggal_diterima"                        => date("Y-m-d"),
        ));

        return "success";
        
    }


    public static function approve_data_karyawan($request){
        Jadwal_Interview::where("id_jadwal_interview" , $request->edit_hidden_textfield)->update(
        
        array("status_diterima"                         => "1",
              "jabatan"                                 => $request->edit_text_title_new,
                "tanggal_diterima"                        => date("Y-m-d"),
        ));

        return "success";
    }

    public static function get_datakaryawan_usaha($request,$index){
        
        $data = Jadwal_Interview::join("tb_tenaga_kerja" , "tb_tenaga_kerja.id_tenaga_kerja","tb_jadwal_interview.id_karyawan")->where("id_usaha" , $index)->orderBy("id_jadwal_interview" , "desc")->get();

        return $data;
        
    }
    

    public static function post_add_tenagakerja_hire($request){
        
        $data                                     = new Jadwal_Interview;
        $data->id_karyawan                        = $request->text_index_karyawan_pilihan;
        $data->id_usaha                           = $request->text_index_usaha_pilihan;
        $data->tanggal_interview                  = $request->text_tanggal_interview;
        $data->jam                                = $request->text_jam_interview;
        $data->status_diterima                    = 0;
        
        $data->aktif                              = "1";

        $data->save();

        Karyawan::where("id_tenaga_kerja" , $request->text_index_karyawan_pilihan)->update(array("status" => "1"));

        return DB::getPdo()->lastInsertId();
    }

    
}
