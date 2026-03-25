<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class List_Skill_Tk extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id_list_skill_tenaga_kerja', 'id_skill'];
    protected $table='tb_list_skill_tenaga_kerja';

    public static function get_dataTenagaKerja($request){
        $data = List_Skill_Tk::where("aktif","1")->orderBy("id_list_skill_tenaga_kerja" , "desc")->get();

        return $data;
    }

    public static function get_dataTenagaKerja_list($request,$index){
        $data = List_Skill_Tk::join("tb_skill_tenaga_kerja","tb_skill_tenaga_kerja.id_skill_tenaga_kerja","tb_list_skill_tenaga_kerja.id_skill")->where("tb_list_skill_tenaga_kerja.id_karyawan",$index)->orderBy("tb_list_skill_tenaga_kerja.id_list_skill_tenaga_kerja" , "desc")->get();

        return $data;
    }
    

    public static function post_data_list_tk($index,$request){

        $tk_skill   = $request->input('chk_tenaga_kerja');
        
        foreach($tk_skill as $rows){

            $data                                 = new List_Skill_Tk;
            $data->id_skill                       = $rows;
            $data->id_karyawan                    = $index;
            
            $data->aktif                          = "1";

            $data->save();

        }

        return $data;
    }
    

    public static function update_data_list_tk($index,$request){

        List_Skill_Tk::where("id_karyawan" , $index)->delete();

        $tk_skill   = $request->input('chk_tenaga_kerja');
        
        foreach($tk_skill as $rows){

            $data                                 = new List_Skill_Tk;
            $data->id_skill                       = $rows;
            $data->id_karyawan                    = $index;
            
            $data->aktif                          = "1";

            $data->save();

        }

        return $data;
    }

    
}
