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
        'tinggal_dari',
        'tinggal_sampai',
        'tinggal_belum_yakin',
        'id_data_banjar',
        'punia_rutin_bulanan',
        'use_global_punia',
        'status',
        'aktif'
    ];

    protected $casts = [
        'tinggal_dari' => 'date',
        'tinggal_sampai' => 'date',
        'tinggal_belum_yakin' => 'boolean',
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

    public static function getGlobalPuniaNominal(): float
    {
        $settingsPath = storage_path('app/settings.json');
        if (!file_exists($settingsPath)) {
            return 0;
        }

        $settings = json_decode(file_get_contents($settingsPath), true);

        return (float) ($settings['punia_pendatang_global'] ?? 0);
    }

    public function getEffectivePuniaNominalAttribute(): float
    {
        if ($this->use_global_punia) {
            return self::getGlobalPuniaNominal();
        }

        return (float) ($this->punia_rutin_bulanan ?? 0);
    }
}
