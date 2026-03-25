<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Penanggung_Jawab extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id_penanggung_jawab', 'nama', 'alamat','aktif'];
    protected $table='tb_penanggung_jawab';

    public static function get_dataUsaha($request){
        $data = Detail_Usaha::where("aktif","1")->orderBy("id_detail_usaha" , "desc")->get();

        return $data;
    }
    
    public static function update_data_pngg_jawab($request){
        
        Penanggung_Jawab::where("id_penanggung_jawab" , $request->tb_hidden_pngg_usaha)->update(
        array("status_penanggung_jawab"      => $request->text_statuspngg_new,
              "nama"                         => $request->text_namapngg_new,
              "alamat"                       => $request->text_alamat_pngg_new,
              "email"                        => $request->text_email_pngg_new,
              "alamat_usaha"                 => $request->text_alamat_pngg_new,
              "no_wa_pngg"                   => $request->text_notelp_pngg_new
        ));
        
    }

    public static function post_data_pngg_jawab($request){
        
        $data                                 = new Penanggung_Jawab;
        $data->status_penanggung_jawab        = $request->text_statuspngg_new;
        $data->nama                           = $request->text_namapngg_new;
        $data->alamat                         = $request->text_alamat_pngg_new;
        $data->email                          = $request->text_email_pngg_new;
        $data->alamat_usaha                   = $request->text_alamat_pngg_new;
        $data->no_wa_pngg                     = $request->text_notelp_pngg_new;
        
        $data->aktif                          = "1";

        $data->save();

        return DB::getPdo()->lastInsertId();
    }

    
}
