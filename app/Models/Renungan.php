<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Renungan extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id_renungan', 'title', 'hari', 'tanggal','keterangan','aktif'];
    protected $table='tb_renungan';

    /*public function adv_city()
    {
        return $this->hasMany('App\tb_adv_city', 'id_adv_city', 'id_adv_city');
    }*/

    public static function get_renungan(){
        $ambil_renungan = Renungan::orderBy('id_renungan' , 'desc')->get();

        $data = [];

        foreach($ambil_renungan as $rows){
            $rows["formatted_tanggal"] = tgl_indo($rows["tanggal"]);
            $data[] = $rows;
        }

        return $data;
    }

    public static function get_renungan_by_id($index){
        $ambil_renungan = Renungan::where('id_renungan' , $index)->get();

        return $ambil_renungan;
    }
    
}
