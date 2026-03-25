<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ibadah extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id_ibadah', 'title', 'id_jenis_file', 'keterangan','link','aktif'];
    protected $table='tb_ibadah';

    /*public function adv_city()
    {
        return $this->hasMany('App\tb_adv_city', 'id_adv_city', 'id_adv_city');
    }*/

    public static function get_ibadah(){
        $ambil_ibadah = Ibadah::orderBy("id_ibadah","desc")->get();

        $data = [];

        foreach($ambil_ibadah as $rows){
            $ex_tgl = explode(" ", $rows["tanggal_berita"]);

            $rows["formatted_tanggal"] = ucwords($rows["hari"])." , ".tgl_indo($ex_tgl[0]);
            $data[] = $rows;
        }

        return $data;
    }

    public static function get_ibadah_by_id($index){
        $ambil_ibadah = Ibadah::where('id_ibadah' , $index)->get();

        return $ambil_ibadah;
    }
    
}
