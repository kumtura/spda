<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{   
    public $timestamps = false; 
    //
    protected $fillable = ['id_jenis_ikan', 'nama_jenis', 'id_nelayan','aktif'];
    protected $table='tb_jenis_ikan';

    /*public function adv_city()
    {
        return $this->hasMany('App\tb_adv_city', 'id_adv_city', 'id_adv_city');
    }*/
    
}
