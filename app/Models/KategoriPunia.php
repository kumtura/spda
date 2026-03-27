<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPunia extends Model
{
    protected $table = 'tb_kategori_punia';
    protected $primaryKey = 'id_kategori_punia';
    
    protected $fillable = [
        'nama_kategori',
        'ikon',
        'deskripsi_singkat',
        'aktif'
    ];

    public function alokasi()
    {
        return $this->hasMany(AlokasiPunia::class, 'id_kategori_punia', 'id_kategori_punia');
    }
}
