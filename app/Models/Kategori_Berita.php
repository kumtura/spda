<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Kategori_Berita extends Model
{   
    //public $timestamps = false; 
    //
    public $timestamps = false; 
    //
    protected $primaryKey = 'id_kategori_berita';
    protected $fillable = ['id_kategori_berita', 'nama_kategori_berita','aktif'];
    protected $table='tb_kategori_berita';

    public static function get_kategoriberita(){
        $data = Kategori_Berita::where("aktif","1")->orderBy("id_kategori_berita" , "desc")->get();

        return $data;
    }

    public static function post_kategori_berita($request){
        
        $data                                 = new Kategori_Berita;
        $data->nama_kategori_berita           = $request->emailinput;
        
        $data->aktif                          = "1";

        $data->save();

        return DB::getPdo()->lastInsertId();
    }

    public static function post_user_kategori_berita($request){
        Kategori_Berita::where("id_kategori_berita" , $request->iduserinput_edit)->update(array("nama_kategori_berita" => $request->emailinput_edit ));
    }

    public static function hapus_kategori_berita($request){
        Kategori_Berita::where("id_kategori_berita" , $request->id)->update(array("aktif" => "0"));
    }

}
