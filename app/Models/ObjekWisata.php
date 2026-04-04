<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Banjar;

class ObjekWisata extends Model
{
    protected $table = 'tb_objek_wisata';
    protected $primaryKey = 'id_objek_wisata';
    
    protected $fillable = [
        'nama_objek',
        'deskripsi',
        'detail_termasuk',
        'cara_penggunaan',
        'pembatalan',
        'syarat_ketentuan',
        'alamat',
        'id_data_banjar',
        'foto',
        'harga_tiket',
        'kapasitas_harian',
        'batas_tiket_harian',
        'jam_buka',
        'jam_tutup',
        'status',
        'aktif'
    ];

    public function banjar()
    {
        return $this->belongsTo(Banjar::class, 'id_data_banjar', 'id_data_banjar');
    }

    public function tiket()
    {
        return $this->hasMany(TiketWisata::class, 'id_objek_wisata', 'id_objek_wisata');
    }

    public function kategoriTiket()
    {
        return $this->hasMany(KategoriTiket::class, 'id_objek_wisata', 'id_objek_wisata')
                    ->where('aktif', 1)
                    ->orderBy('urutan');
    }
}
