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
        'id_data_banjar',
        'punia_rutin_bulanan',
        'use_global_punia',
        'status',
        'aktif'
    ];
    
    public function banjar()
    {
        return $this->belongsTo(Banjar::class, 'id_data_banjar', 'id_data_banjar');
    }
    
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
