<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryPura extends Model
{
    protected $table = 'tb_gallery_pura';
    protected $primaryKey = 'id_gallery_pura';
    protected $fillable = [
        'id_pura', 'gambar', 'caption', 'urutan', 'aktif'
    ];

    public function pura()
    {
        return $this->belongsTo(Pura::class, 'id_pura', 'id_pura');
    }
}
