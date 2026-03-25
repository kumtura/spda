<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id_menu_member', 'menu', 'url','aktif'];
    protected $table='tb_menu_member';

    public static function get_datamenu($request){

        $data = Menu::where("aktif","1")->orderBy("id_menu_member" , "desc")->get();

        return $data;

    }

    public static function post_data_menu($request){
        $originalImage= $request->file('f_upload_menu');
        $menu_name = time().str_shuffle("abcdefghijklmnopqrstuvwxyz").".".$originalImage->getClientOriginalExtension();
        

        $thumbnailImage = \Intervention\Image\Facades\Image::make($originalImage);
        $thumbnailPath = public_path()."/menu/icon/thumbnail/";
        $originalPath = public_path()."/menu/icon/";
        $thumbnailImage->save($originalPath.$menu_name);
        $thumbnailImage->resize(150,150);
        $thumbnailImage->save($thumbnailPath.$menu_name); 

        $slide = "0";

        if($request->chk_is_slide != ""){
            $slide = "1";
        }

        $data                   = new Menu;
        $data->menu             = $request->t_nama_menu;
        $data->url              = $request->t_url_menu;
        $data->urutan           = $request->t_urutan_menu;
        $data->is_slide         = $slide;
        $data->foto             = $menu_name;
        $data->url_foto         = "/menu/icon/".$menu_name;
        $data->aktif            = "1";

        $data->save();

        return $data;
    }

    public static function post_hapus_banjar($request){

        $data = Banjar::where("id_data_banjar" , $request->id)->update(array("aktif" => "0"));

        return $data;

    }

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
