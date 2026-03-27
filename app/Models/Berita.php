<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id_berita', 'isi_berita', 'judul_berita', 'video','foto','aktif'];
    protected $table='tb_berita';

    /*public function adv_city()
    {
        return $this->hasMany('App\tb_adv_city', 'id_adv_city', 'id_adv_city');
    }*/

    public static function get_berita(){
        $ambil_berita = Berita::orderBy("id_berita","desc")->get();

        $data = [];
        

        foreach($ambil_berita as $rows){
            $tgl_b = explode(" ", $rows["tanggal_berita"]);
            $rows["formatted_tanggal"] = tgl_indo($tgl_b[0]);
            $data[] = $rows;
        }

        return $data;
    }

    public function get_berita_by_id($index){
        $ambil_berita = Berita::where('id_berita' , $index)->get();

        return $ambil_berita;
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori_Berita::class, 'id_kategori_berita', 'id_kategori_berita');
    }
    
}
