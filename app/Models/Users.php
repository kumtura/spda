<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id', 'name', 'email', 'password','foto','aktif'];
    protected $table='tb_jemaah';

    public static function get_users($index){
        $ambil_email = Users::where('username',$index)->orderBy("id","desc")->get();
        

        return $ambil_email;
    }

    public function get_berita_by_id($index){
        $ambil_berita = Berita::where('id_berita' , $index)->get();

        return $ambil_berita;
    }
    
}
