<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriAgenda extends Model
{
    use HasFactory;

    protected $table = 'tb_kategori_agenda';
    protected $primaryKey = 'id_kategori_agenda';
    protected $fillable = [
        'nama_kategori',
        'keterangan',
        'aktif'
    ];

    public function agenda()
    {
        return $this->hasMany(Agenda::class, 'id_kategori_agenda');
    }
}
