<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriTiket extends Model
{
    protected $table = 'tb_kategori_tiket';
    protected $primaryKey = 'id_kategori_tiket';
    
    protected $fillable = [
        'id_objek_wisata',
        'nama_kategori',
        'tipe_kategori',
        'market_type',
        'harga',
        'deskripsi',
        'urutan',
        'aktif'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'urutan' => 'integer',
        'aktif' => 'integer',
    ];

    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek_wisata', 'id_objek_wisata');
    }

    public function tiketDetail()
    {
        return $this->hasMany(TiketDetail::class, 'id_kategori_tiket', 'id_kategori_tiket');
    }
}
