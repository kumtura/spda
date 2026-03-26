<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriSlide extends Model
{   
    protected $fillable = ['nama_kategori', 'aktif'];
    protected $table = 'tb_kategori_slides';
    protected $primaryKey = 'id_kategori_slides';

    public static function get_datakategori($request)
    {
        return self::where("aktif", "1")->orderBy("id_kategori_slides", "desc")->get();
    }

    public static function get_datakategori_nama($request)
    {
        return self::where("aktif", "1")->orderBy("nama_kategori", "asc")->get();
    }
}
