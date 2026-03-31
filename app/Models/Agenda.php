<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'tb_agenda';
    protected $primaryKey = 'id_agenda';
    protected $fillable = [
        'id_kategori_agenda',
        'judul_agenda',
        'deskripsi_agenda',
        'tanggal_agenda',
        'waktu_agenda',
        'waktu_selesai_data',
        'status_selesai',
        'lokasi_agenda',
        'foto_agenda',
        'status_agenda',
        'aktif'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriAgenda::class, 'id_kategori_agenda');
    }
}
