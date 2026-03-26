<?php

namespace App\Models\Gambar\Slides;

use Illuminate\Database\Eloquent\Model;

class Slides extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id_gambar_home', 'image_name', 'title','aktif'];
    protected $table='tb_gambar_home';

    public static function get_dataslides($request){

        $data = Slides::where("aktif","1")->orderBy("id_gambar_home" , "desc")->get();

        return $data;

    }

    public static function get_data_slides_by_position($request,$index){

        $data = Slides::where("posisi_gambar",$request->posisi)->where("aktif","1")->orderBy("id_gambar_home" , "desc")->get();

        return $data;

    }

    public static function post_data_slides($request){
        $originalImage= $request->file('f_upload_gambar');
        $slider_name = time().str_shuffle("abcdefghijklmnopqrstuvwxyz").".".$originalImage->getClientOriginalExtension();
        
        $thumbnailImage = \Intervention\Image\Facades\Image::make($originalImage);
        $thumbnailPath = public_path()."/GambarSlides/thumbnail/";
        $originalPath = public_path()."/GambarSlides/";
        $thumbnailImage->save($originalPath.$slider_name);
        $thumbnailImage->resize(150,150);
        $thumbnailImage->save($thumbnailPath.$slider_name); 


        $originalImage2= $request->file('f_upload_gambar_mobile');
        $slider_name_mobile = "thumbnail_".time().str_shuffle("abcdefghijklmnopqrstuvwxyz").".".$originalImage2->getClientOriginalExtension();
        
        $thumbnailImage2 = \Intervention\Image\Facades\Image::make($originalImage2);
        $thumbnailPath2 = public_path()."/GambarSlides/thumbnail/";
        $originalPath2 = public_path()."/GambarSlides/";
        $thumbnailImage2->save($originalPath2.$slider_name_mobile);
        $thumbnailImage2->resize(150,150);
        $thumbnailImage2->save($thumbnailPath2.$slider_name_mobile);


        $data                                         = new Slides;
        $data->alt                                    = $request->text_desc_new;
        $data->title                                  = $request->text_title_new;
        $data->deskripsi                              = $request->text_desc_new;
        $data->image_name                             = $slider_name;
        $data->url_path                               = "/GambarSlides/thumbnail/".$slider_name;
        $data->image_name_mobile                      = $slider_name_mobile;
        $data->image_pathname_mobile                  = "/GambarSlides/".$slider_name_mobile;
        $data->urutan                                 = 1;
        $data->posisi_gambar                          = $request->urutan_id;
        $data->aktif                                  = "1";


        $data->save();

        return $data;

    }

    public static function post_gambar_baru_edit($request){
        $originalImage= $request->file('edit_f_upload_gambar');
        $slider_name = time().str_shuffle("abcdefghijklmnopqrstuvwxyz").".".$originalImage->getClientOriginalExtension();
        
        $thumbnailImage = \Intervention\Image\Facades\Image::make($originalImage);
        $thumbnailPath = public_path()."/GambarSlides/thumbnail/";
        $originalPath = public_path()."/GambarSlides/";
        $thumbnailImage->save($originalPath.$slider_name);
        $thumbnailImage->resize(150,150);
        $thumbnailImage->save($thumbnailPath.$slider_name); 


        $originalImage2= $request->file('edit_f_upload_gambar_mobile');
        $slider_name_mobile = "thumbnail_".time().str_shuffle("abcdefghijklmnopqrstuvwxyz").".".$originalImage2->getClientOriginalExtension();
        
        $thumbnailImage2 = \Intervention\Image\Facades\Image::make($originalImage2);
        $thumbnailPath2 = public_path()."/GambarSlides/thumbnail/";
        $originalPath2 = public_path()."/GambarSlides/";
        $thumbnailImage2->save($originalPath2.$slider_name_mobile);
        $thumbnailImage2->resize(150,150);
        $thumbnailImage2->save($thumbnailPath2.$slider_name_mobile);

        $array_input = array();

        $array_input["alt"]                     = $request->edit_text_desc_new;
        $array_input["title"]                   = $request->edit_text_title_new;
        $array_input["deskripsi"]               = $request->edit_text_desc_new;

        if($originalImage != ""){
            $array_input["image_name"]              = $slider_name;
            $array_input["url_path"]                = "/GambarSlides/thumbnail/".$slider_name;
        }

        if($originalImage2 != ""){
            $array_input["image_name_mobile"]       = $slider_name_mobile;
            $array_input["image_pathname_mobile"]   = "/GambarSlides"."/".$slider_name_mobile;
        }

        $data = Slides::where("id_gambar_home" , $request->edit_hidden_textfield)->update($array_input);

        return $data;

    }

    
    public static function post_active_slides($request){

        $array_input = array();

        $array_input["is_slide"] = "1";

        $data = Slides::where("id_gambar_home" , $request->id)->update($array_input);

        return $data;

    }

    public static function get_gambar_slide($request){
        $data = Slides::where("id_gambar_home" , $request->id)->firstOrfail();

        return $data;
    }

    public static function post_hapus_banjar($request){

        $data = Banjar::where("id_data_banjar" , $request->id)->update(array("aktif" => "0"));

        return $data;

    }

    public static function post_editdata_banjar($request){

        $data = Banjar::where("id_data_banjar" , $request->t_id_banjar)->update(array("nama_banjar" => $request->t_nama_banjar , "alamat_banjar" => $request->t_alamat_banjar));

        return $data;

    }


    /*public function adv_city()
    {
        return $this->hasMany('App\tb_adv_city', 'id_adv_city', 'id_adv_city');
    }*/
    
}
