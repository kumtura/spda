<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jemaah extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id', 'nama', 'username', 'password','email','aktif'];
    protected $table='tb_jemaah';
    
}
