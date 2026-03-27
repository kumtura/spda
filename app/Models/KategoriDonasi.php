<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriDonasi extends Model
{
    protected $table = 'tb_kategori_donasi';
    protected $primaryKey = 'id_kategori_donasi';
    
    protected $fillable = [
        'nama_kategori',
        'ikon',
        'deskripsi_singkat',
        'aktif'
    ];

    public function programs()
    {
        return $this->hasMany(ProgramDonasi::class, 'id_kategori_donasi', 'id_kategori_donasi');
    }
}
