<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warta extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id_warta_berita', 'title', 'hari', 'tanggal','keterangan','aktif'];
    protected $table='tb_warta_berita';

    /*public function adv_city()
    {
        return $this->hasMany('App\tb_adv_city', 'id_adv_city', 'id_adv_city');
    }*/
    
    public static function get_warta(){
        $ambil_warta = Warta::orderBy("id_warta_berita","desc")->get();

        $data = [];

        foreach($ambil_warta as $rows){
            $rows["formatted_tanggal"] = tgl_indo($rows["tanggal"]);
            $data[] = $rows;
        }

        return $data;
    }

    public static function get_warta_by_id($index){
        $ambil_warta = Warta::where('id_warta_berita' , $index)->get();

        return $ambil_warta;
    }

}
