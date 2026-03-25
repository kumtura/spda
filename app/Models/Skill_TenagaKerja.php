<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Skill_TenagaKerja extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id_skill_tenaga_kerja', 'nama_skill'];
    protected $table='tb_skill_tenaga_kerja';

    public static function get_dataTenagaKerja($request){
        $data = Skill_TenagaKerja::where("aktif","1")->orderBy("id_skill_tenaga_kerja" , "desc")->get();

        return $data;
    }
    
    public static function post_data_edit_skill($request){
        
        Skill_TenagaKerja::where("id_skill_tenaga_kerja" , $request->edit_hidden_textfield)->update(
        array("nama_skill"                         => $request->edit_text_title_new
        ));

        return "success";
        
    }

    public static function hapus_skill($index){
        
        Skill_TenagaKerja::where("id_skill_tenaga_kerja" , $index)->delete();

        return "success";
        
    }

    public static function post_data_skill($request){
        
        $data                                 = new Skill_TenagaKerja;
        $data->nama_skill                     = $request->t_nama_menu;
        
        $data->aktif                          = "1";

        $data->save();

        return DB::getPdo()->lastInsertId();
    }

    public static function post_data_skill_new($request){
        
        $data                                 = new Skill_TenagaKerja;
        $data->nama_skill                     = $request->val;
        
        $data->aktif                          = "1";

        $data->save();

        return DB::getPdo()->lastInsertId();
    }

    
}
