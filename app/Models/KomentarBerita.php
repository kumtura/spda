<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomentarBerita extends Model
{
    protected $table = 'tb_komentar_berita';
    protected $primaryKey = 'id_komentar_berita';
    
    protected $fillable = [
        'id_berita',
        'nama',
        'komentar'
    ];

    public function berita()
    {
        return $this->belongsTo(Berita::class, 'id_berita', 'id_berita');
    }
}
