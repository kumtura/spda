<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategorial extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id_kategorial', 'judul', 'foto', 'tanggal','keterangan','aktif'];
    protected $table='tb_kategorial';

    /*public function adv_city()
    {
        return $this->hasMany('App\tb_adv_city', 'id_adv_city', 'id_adv_city');
    }*/

    public static function get_kategorial(){
        $ambil_kategorial = Kategorial::orderBy("id_kategorial","desc")->get();

        $data = [];

        foreach($ambil_kategorial as $rows){
            $rows["formatted_tanggal"] = tgl_indo($rows["tanggal"]);
            $data[] = $rows;
        }

        return $data;
    }

    public static function get_kategorial_by_id($index){
        $ambil_kategorial = Kategorial::where('id_kategorial' , $index)->get();

        return $ambil_kategorial;
    }
    
}
