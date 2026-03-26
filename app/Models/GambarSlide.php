<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class GambarSlide extends Model
{   
    protected $fillable = ['id_gambar_home', 'image_name', 'title', 'alt', 'deskripsi', 'image_name_mobile', 'is_slide', 'posisi_gambar', 'aktif'];
    protected $table = 'tb_gambar_home';
    protected $primaryKey = 'id_gambar_home';

    public static function get_dataslides($request)
    {
        return self::where("aktif", "1")->orderBy("id_gambar_home", "desc")->get();
    }

    public static function get_data_slides_by_position($request, $index)
    {
        return self::where("posisi_gambar", $index)->where("aktif", "1")->orderBy("id_gambar_home", "desc")->get();
    }

    public static function post_data_slides($request)
    {
        $storagePath = storage_path('app/public/GambarSlides');
        $thumbnailPath = $storagePath . "/thumbnail/";

        if (!File::isDirectory($storagePath)) {
            File::makeDirectory($storagePath, 0777, true, true);
        }
        if (!File::isDirectory($thumbnailPath)) {
            File::makeDirectory($thumbnailPath, 0777, true, true);
        }

        $slider_name = null;
        if ($request->hasFile('f_upload_gambar')) {
            $originalImage = $request->file('f_upload_gambar');
            $slider_name = time() . str_shuffle("abcdefghijklmnopqrstuvwxyz") . "." . $originalImage->getClientOriginalExtension();
            $img = Image::make($originalImage);
            $img->save($storagePath . "/" . $slider_name);
            $img->resize(150, 150);
            $img->save($thumbnailPath . $slider_name);
        }

        $slider_name_mobile = null;
        if ($request->hasFile('f_upload_gambar_mobile')) {
            $originalImage2 = $request->file('f_upload_gambar_mobile');
            $slider_name_mobile = "mobile_" . time() . str_shuffle("abcdefghijklmnopqrstuvwxyz") . "." . $originalImage2->getClientOriginalExtension();
            $img2 = Image::make($originalImage2);
            $img2->save($storagePath . "/" . $slider_name_mobile);
            $img2->resize(150, 150);
            $img2->save($thumbnailPath . $slider_name_mobile);
        }

        $data = new self;
        $data->alt = $request->text_desc_new;
        $data->title = $request->text_title_new;
        $data->deskripsi = $request->text_desc_new;
        $data->image_name = $slider_name;
        $data->url_path = $slider_name ? "/storage/GambarSlides/thumbnail/" . $slider_name : null;
        $data->image_name_mobile = $slider_name_mobile;
        $data->image_pathname_mobile = $slider_name_mobile ? "/storage/GambarSlides/" . $slider_name_mobile : null;
        $data->urutan = 1;
        $data->posisi_gambar = $request->urutan_id;
        $data->aktif = "1";
        $data->save();

        return $data;
    }

    public static function post_gambar_baru_edit($request)
    {
        $storagePath = storage_path('app/public/GambarSlides');
        $thumbnailPath = $storagePath . "/thumbnail/";

        if (!File::isDirectory($storagePath)) {
            File::makeDirectory($storagePath, 0777, true, true);
        }
        if (!File::isDirectory($thumbnailPath)) {
            File::makeDirectory($thumbnailPath, 0777, true, true);
        }

        $array_input = [
            "alt" => $request->edit_text_desc_new,
            "title" => $request->edit_text_title_new,
            "deskripsi" => $request->edit_text_desc_new,
        ];

        if ($request->hasFile('edit_f_upload_gambar')) {
            $originalImage = $request->file('edit_f_upload_gambar');
            $slider_name = time() . str_shuffle("abcdefghijklmnopqrstuvwxyz") . "." . $originalImage->getClientOriginalExtension();
            $img = Image::make($originalImage);
            $img->save($storagePath . "/" . $slider_name);
            $img->resize(150, 150);
            $img->save($thumbnailPath . $slider_name);

            $array_input["image_name"] = $slider_name;
            $array_input["url_path"] = "/storage/GambarSlides/thumbnail/" . $slider_name;
        }

        if ($request->hasFile('edit_f_upload_gambar_mobile')) {
            $originalImage2 = $request->file('edit_f_upload_gambar_mobile');
            $slider_name_mobile = "mobile_" . time() . str_shuffle("abcdefghijklmnopqrstuvwxyz") . "." . $originalImage2->getClientOriginalExtension();
            $img2 = Image::make($originalImage2);
            $img2->save($storagePath . "/" . $slider_name_mobile);
            $img2->resize(150, 150);
            $img2->save($thumbnailPath . $slider_name_mobile);

            $array_input["image_name_mobile"] = $slider_name_mobile;
            $array_input["image_pathname_mobile"] = "/storage/GambarSlides/" . $slider_name_mobile;
        }

        return self::where("id_gambar_home", $request->edit_hidden_textfield)->update($array_input);
    }

    public static function post_active_slides($request)
    {
        $current = self::where("id_gambar_home", $request->id)->first();
        $new_status = ($current && $current->is_slide == 1) ? 0 : 1;
        return self::where("id_gambar_home", $request->id)->update(["is_slide" => $new_status]);
    }

    public static function get_gambar_slide($request)
    {
        return self::where("id_gambar_home", $request->id)->firstOrFail();
    }
}
