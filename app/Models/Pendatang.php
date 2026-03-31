<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendatang extends Model
{
    protected $table = 'tb_pendatang';
    protected $primaryKey = 'id_pendatang';
    
    protected $fillable = [
        'nama',
        'nik',
        'asal',
        'no_hp',
        'alamat_tinggal',
        'punia_rutin_bulanan',
        'use_global_punia',
        'status',
        'aktif'
    ];
    
    public function puniaPendatang()
    {
        return $this->hasMany(PuniaPendatang::class, 'id_pendatang', 'id_pendatang');
    }
    
    public function puniaRutin()
    {
        return $this->hasMany(PuniaPendatang::class, 'id_pendatang', 'id_pendatang')
            ->where('jenis_punia', 'rutin');
    }
    
    public function puniaAcara()
    {
        return $this->hasMany(PuniaPendatang::class, 'id_pendatang', 'id_pendatang')
            ->where('jenis_punia', 'acara');
    }
}
