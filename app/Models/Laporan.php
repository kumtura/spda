<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id_laporan', 'title', 'tanggal', 'aktif'];
    protected $table='tb_laporan';

    /*public function adv_city()
    {
        return $this->hasMany('App\tb_adv_city', 'id_adv_city', 'id_adv_city');
    }*/
    
}
