<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Banjar;
use Illuminate\Support\Str;

class ObjekWisata extends Model
{
    protected $table = 'tb_objek_wisata';
    protected $primaryKey = 'id_objek_wisata';
    
    protected $fillable = [
        'nama_objek',
        'slug',
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

    public static function generateSlug($name, $excludeId = null)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;
        $query = static::where('slug', $slug);
        if ($excludeId) {
            $query->where('id_objek_wisata', '!=', $excludeId);
        }
        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
            $query = static::where('slug', $slug);
            if ($excludeId) {
                $query->where('id_objek_wisata', '!=', $excludeId);
            }
        }
        return $slug;
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::generateSlug($model->nama_objek);
            }
        });
    }
}
