<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pura extends Model
{
    protected $table = 'tb_pura';
    protected $primaryKey = 'id_pura';
    protected $fillable = [
        'nama_pura', 'lokasi', 'latitude', 'longitude',
        'nama_ketua_pura', 'no_telp_ketua', 'id_data_banjar',
        'nama_pemangku', 'wuku_odalan', 'odalan_terdekat',
        'gambar_pura', 'deskripsi', 'aktif'
    ];

    public function banjar()
    {
        return $this->belongsTo(Banjar::class, 'id_data_banjar', 'id_data_banjar');
    }

    public function gallery()
    {
        return $this->hasMany(GalleryPura::class, 'id_pura', 'id_pura')
                    ->where('aktif', '1')
                    ->orderBy('urutan');
    }

    public function punia()
    {
        return $this->hasMany(PuniaPura::class, 'id_pura', 'id_pura');
    }

    public function qris()
    {
        return $this->hasOne(QrisPura::class, 'id_pura', 'id_pura')
                    ->where('is_active', '1');
    }

    public function puniaCompleted()
    {
        return $this->hasMany(PuniaPura::class, 'id_pura', 'id_pura')
                    ->where('status_pembayaran', 'completed')
                    ->where('aktif', '1');
    }
}
