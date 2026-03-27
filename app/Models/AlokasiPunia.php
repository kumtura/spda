<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlokasiPunia extends Model
{
    protected $table = 'tb_alokasi_punia';
    protected $primaryKey = 'id_alokasi_punia';
    
    protected $fillable = [
        'id_kategori_punia',
        'judul',
        'deskripsi',
        'nominal',
        'tanggal_alokasi',
        'aktif'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriPunia::class, 'id_kategori_punia', 'id_kategori_punia');
    }
}
