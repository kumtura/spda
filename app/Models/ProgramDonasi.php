<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramDonasi extends Model
{
    protected $table = 'tb_program_donasi';
    protected $primaryKey = 'id_program_donasi';
    
    protected $fillable = [
        'id_kategori_donasi',
        'nama_program',
        'deskripsi',
        'foto',
        'target_dana',
        'terkumpul',
        'tanggal_mulai',
        'aktif'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriDonasi::class, 'id_kategori_donasi', 'id_kategori_donasi');
    }
}
