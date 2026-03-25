<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GambarSlides extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id_berita', 'isi_berita', 'judul_berita', 'video','foto','aktif'];
    protected $table='tb_berita';

    /*public function adv_city()
    {
        return $this->hasMany('App\tb_adv_city', 'id_adv_city', 'id_adv_city');
    }*/

    public static function get_gambar_slides(){
        //$ambil_berita = Berita::orderBy("id_berita","desc")->get();
        $originalImage= $request->file('foto_ijazah');
        $ijasah_name = time().str_shuffle("abcdefghijklmnopqrstuvwxyz").".".$originalImage->getClientOriginalExtension();
        

        $thumbnailImage = \Intervention\Image\Facades\Image::make($originalImage);
        $thumbnailPath = public_path()."/GambarSlides/thumbnail/";
        $originalPath = public_path()."/GambarSlides/";
        $thumbnailImage->save($originalPath.$ijasah_name);
        $thumbnailImage->resize(150,150);
        $thumbnailImage->save($thumbnailPath.$ijasah_name); 


        $originalImage2= $request->file('foto_profle');
        $profile_name = "thumbnail_".time().str_shuffle("abcdefghijklmnopqrstuvwxyz").".".$originalImage2->getClientOriginalExtension();
        
        $thumbnailImage2 = \Intervention\Image\Facades\Image::make($originalImage2);
        $thumbnailPath2 = public_path()."/GambarSlides/thumbnail/";
        $originalPath2 = public_path()."/GambarSlides/";
        $thumbnailImage2->save($originalPath2.$profile_name);
        $thumbnailImage2->resize(150,150);
        $thumbnailImage2->save($thumbnailPath2.$profile_name);

        //return $data;
    }

    public function get_berita_by_id($index){
        $ambil_berita = Berita::where('id_berita' , $index)->get();

        return $ambil_berita;
    }
    
}
