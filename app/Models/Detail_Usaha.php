<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Detail_Usaha extends Model
{   
    //public $timestamps = false; 
    //
    protected $primaryKey = 'id_detail_usaha';
    protected $fillable = [
        'nama_usaha', 
        'email_usaha', 
        'logo', 
        'id_banjar', 
        'no_telp', 
        'minimal_bayar', 
        'no_wa', 
        'alamat_banjar', 
        'facebook_url', 
        'twitter_url', 
        'website_url', 
        'google_maps', 
        'jumlah_tk_total',
        'jumlah_tk_bali',
        'jumlah_tk_lokal',
        'tanggal_daftar', 
        'aktif'
    ];
    protected $table='tb_detail_usaha';

    public static function get_dataUsaha($request){
        $data = Detail_Usaha::where("aktif","1")->orderBy("id_detail_usaha" , "desc")->get();

        return $data;
    }
    
    
    
    public static function update_data_detail_usaha($request){
        
        Detail_Usaha::where("id_detail_usaha",$request->tb_hidden_detail_usaha)->update(
        array("nama_usaha"      => $request->text_title_new,
              "email_usaha"     => $request->text_email_usaha_new,
              "id_banjar"       => $request->text_desc_new,
              "no_telp"         => $request->text_telpkantor_new,
              "minimal_bayar"   => $request->text_minimal_pembayaran,
              "no_wa"           => $request->text_notelp_was,
              "alamat_banjar"   => $request->t_alamat_usaha,
              "facebook_url"    => $request->cmb_social_facebook,
              "twitter_url"     => $request->cmb_social_twitter,
              "website_url"     => $request->cmb_social_website,
              "google_maps"     => $request->text_google_maps
        ));
        
        
    }
    
    public static function update_logo_usaha($request,$index){
        $originalImage= $request->file('logo_usaha') ?: $request->file('file');
        $menu_name = time().str_shuffle("abcdefghijklmnopqrstuvwxyz").".".$originalImage->getClientOriginalExtension();
        
        // Create directories if they don't exist (using storage path)
        $thumbnailPath = public_path("storage/usaha/icon/thumbnail");
        $originalPath = public_path("storage/usaha/icon");
        
        if (!file_exists($originalPath)) {
            mkdir($originalPath, 0755, true);
        }
        if (!file_exists($thumbnailPath)) {
            mkdir($thumbnailPath, 0755, true);
        }

        $thumbnailImage = \Intervention\Image\Facades\Image::make($originalImage);
        $thumbnailImage->save($originalPath."/".$menu_name);
        $thumbnailImage->resize(150,150);
        $thumbnailImage->save($thumbnailPath."/".$menu_name); 
        
        Detail_Usaha::where("id_detail_usaha",$index)->update(
            array("logo"      => $menu_name
        ));
        
        return url("storage/usaha/icon/".$menu_name);
        
    }

public static function post_data_detail_usaha($request){
    $originalImage= $request->file('f_upload_gambar_mobile');
    $menu_name = time().str_shuffle("abcdefghijklmnopqrstuvwxyz").".".$originalImage->getClientOriginalExtension();
    
    // Create directories if they don't exist (using storage path)
    $thumbnailPath = public_path("storage/usaha/icon/thumbnail");
    $originalPath = public_path("storage/usaha/icon");
    
    if (!file_exists($originalPath)) {
        mkdir($originalPath, 0755, true);
    }
    if (!file_exists($thumbnailPath)) {
        mkdir($thumbnailPath, 0755, true);
    }

    $thumbnailImage = \Intervention\Image\Facades\Image::make($originalImage);
    $thumbnailImage->save($originalPath."/".$menu_name);
    $thumbnailImage->resize(150,150);
    $thumbnailImage->save($thumbnailPath."/".$menu_name); 

    // $slide = "0";

    // if($request->chk_is_slide != ""){
    //     $slide = "1";
    // }

    $data                                 = new Detail_Usaha;
    $data->nama_usaha                     = $request->text_title_new;
    $data->email_usaha                    = $request->text_email_usaha_new;
    $data->logo                           = $menu_name;
    $data->id_banjar                      = $request->text_desc_new;
    $data->no_telp                        = $request->text_telpkantor_new;
    $data->minimal_bayar                  = $request->text_minimal_pembayaran;
    $data->no_wa                          = $request->text_notelp_was;
    $data->alamat_banjar                  = $request->t_alamat_usaha;
    $data->facebook_url                   = $request->cmb_social_facebook;
    $data->twitter_url                    = $request->cmb_social_twitter;
    $data->website_url                    = $request->cmb_social_website;
    $data->google_maps                    = $request->text_google_maps;
    $data->tanggal_daftar                 = date("Y-m-d");
    $data->aktif                          = "1";

    $data->save();

    return DB::getPdo()->lastInsertId();
}

    // public static function post_hapus_banjar($request){

    //     $data = Banjar::where("id_data_banjar" , $request->id)->update(array("aktif" => "0"));

    //     return $data;

    // }

    public static function post_editdata_menu($request){

        $slide = "0";

        if($request->chk_is_slide != ""){
            $slide = "1";
        }

        $originalImage= $request->file('f_upload_menu');
        

        if($originalImage != ""){
            $menu_name = time().str_shuffle("abcdefghijklmnopqrstuvwxyz").".".$originalImage->getClientOriginalExtension();
        
            $thumbnailImage = \Intervention\Image\Facades\Image::make($originalImage);
            $thumbnailPath = public_path()."/menu/icon/thumbnail/";
            $originalPath = public_path()."/menu/icon/";
            $thumbnailImage->save($originalPath.$menu_name);
            $thumbnailImage->resize(150,150);
            $thumbnailImage->save($thumbnailPath.$menu_name); 

            $data = Menu::where("id_menu_member" , $request->t_id_menu)->update(array("menu" => $request->t_nama_menu , "url" => $request->t_url_menu, "urutan" => $request->t_urutan_menu, "is_slide" => $slide, "foto" => $menu_name, "url_foto" => "/menu/icon/".$menu_name));

        }
        else{
            $data = Menu::where("id_menu_member" , $request->t_id_menu)->update(array("menu" => $request->t_nama_menu , "url" => $request->t_url_menu, "urutan" => $request->t_urutan_menu, "is_slide" => $slide));
        }


       
        return $data;

    }


    /*public function adv_city()
    {
        return $this->hasMany('App\tb_adv_city', 'id_adv_city', 'id_adv_city');
    }*/
    
}
