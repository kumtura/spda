<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loker extends Model
{
    protected $table = 'tb_loker';
    protected $primaryKey = 'id_loker';
    protected $fillable = ['id_usaha', 'judul', 'deskripsi', 'status'];
    
    // Relasi ke usaha
    public function usaha()
    {
        return $this->belongsTo(Usaha::class, 'id_usaha', 'id_usaha');
    }
}
