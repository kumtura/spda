<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banjar extends Model
{   
    //public $timestamps = false; 
    //
    protected $primaryKey = 'id_data_banjar';
    protected $fillable = ['id_data_banjar', 'nama_banjar', 'alamat_banjar', 'id_user_kelian', 'aktif'];
    protected $table='tb_data_banjar';

    public static function get_databanjar($request){

        $data = Banjar::where("aktif","1")->orderBy("id_data_banjar" , "desc")->get();

        return $data;

    }

    public static function post_data_banjar($request){

        $data = new Banjar;
        $data->nama_banjar   = $request->t_nama_banjar;
        $data->alamat_banjar = $request->t_alamat_banjar;
        $data->id_user_kelian = $request->t_kelian_adat ?: null;

        $data->save();

        return $data;

    }

    public static function post_hapus_banjar($request){

        $data = Banjar::where("id_data_banjar" , $request->id)->update(array("aktif" => "0"));

        return $data;

    }

    public static function post_editdata_banjar($request){

        $data = Banjar::where("id_data_banjar" , $request->t_id_banjar)->update(array(
            "nama_banjar" => $request->t_nama_banjar, 
            "alamat_banjar" => $request->t_alamat_banjar,
            "id_user_kelian" => $request->t_kelian_adat ?: null
        ));

        return $data;

    }


    public function userKelian()
    {
        return $this->belongsTo(User::class, 'id_user_kelian', 'id');
    }
    
}
