<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{   
    public $timestamps = false; 
    //
    protected $fillable = ['id_kategori_berita', 'nama_kategori_berita','aktif'];
    protected $table='tb_kategori_berita';

    /*public function adv_city()
    {
        return $this->hasMany('App\tb_adv_city', 'id_adv_city', 'id_adv_city');
    }*/
    
}
