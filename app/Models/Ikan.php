<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ikan extends Model
{   
    public $timestamps = false; 
    //
    protected $fillable = ['id_ikan', 'nama', 'harga','foto','aktif'];
    protected $table='tb_ikan';

    /*public function adv_city()
    {
        return $this->hasMany('App\tb_adv_city', 'id_adv_city', 'id_adv_city');
    }*/
    
}
