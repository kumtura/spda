<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiketDetail extends Model
{
    protected $table = 'tb_tiket_detail';
    protected $primaryKey = 'id_tiket_detail';
    
    protected $fillable = [
        'id_tiket',
        'id_kategori_tiket',
        'jumlah',
        'harga_satuan',
        'subtotal'
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function tiket()
    {
        return $this->belongsTo(TiketWisata::class, 'id_tiket', 'id_tiket');
    }

    public function kategoriTiket()
    {
        return $this->belongsTo(KategoriTiket::class, 'id_kategori_tiket', 'id_kategori_tiket');
    }
}
