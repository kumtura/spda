<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Detail_Usaha;
use App\Models\Penanggung_Jawab;
use App\Models\Danapunia;

use App\Models\Usaha;
use DB;

class Usaha extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id_usaha', 'user_id', 'id_detail_usaha', 'id_penanggung_jawab','aktif'];
    protected $table='tb_usaha';

    public static function get_datamenu($request){

        $data = Menu::where("aktif","1")->orderBy("id_menu_member" , "desc")->get();

        return $data;

    }

    public static function post_data_usaha($request){
        $res_detail                      = Detail_Usaha::post_data_detail_usaha($request);
        
        $rows_detail                     = $res_detail;
        
        // Find the email from detail to create User
        $detail = Detail_Usaha::where('id_detail_usaha', $rows_detail)->first();
        $email = $detail->email_usaha ?: $request->text_username_new;

        // Create/Update User account
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $detail->nama_usaha,
                'email' => $email,
                'password' => bcrypt($request->text_password_new),
                'no_wa' => $detail->no_wa,
                'id_level' => 3, // Unit Usaha
            ]);
        } else {
            $user->update([
                'id_level' => 3,
                'password' => bcrypt($request->text_password_new)
            ]);
        }

        $res_tgg_jawab                   = Penanggung_Jawab::post_data_pngg_jawab($request);
        
        $data                            = new Usaha;
        $data->user_id                   = $user->id;
        $data->id_detail_usaha           = $rows_detail;
        $data->id_jenis_usaha            = $request->cmb_kategori_usaha;
        $data->id_penanggung_jawab       = $res_tgg_jawab;
        $data->username                  = $email;
        $data->password                  = bcrypt($request->text_password_new);
        
        $data->aktif                     = "1";

        $data->save();

        return DB::getPdo()->lastInsertId();
    }
    
    public static function update_data_usaha($request){
        $res_detail                      = Detail_Usaha::update_data_detail_usaha($request);
    
        $res_tgg_jawab                   = Penanggung_Jawab::update_data_pngg_jawab($request);
        
        $usaha = Usaha::where("id_usaha", $request->tb_hidden_usaha)->first();
        
        if($request->text_password_new == ""){
            Usaha::where("id_usaha" , $request->tb_hidden_usaha)->update(array("id_jenis_usaha" => $request->cmb_kategori_usaha));
        }
        else{
            Usaha::where("id_usaha" , $request->tb_hidden_usaha)->update(array("id_jenis_usaha" => $request->cmb_kategori_usaha , "password" => bcrypt($request->text_password_new)));
            
            // Sync password to User table
            if ($usaha && $usaha->user_id) {
                User::where('id', $usaha->user_id)->update(['password' => bcrypt($request->text_password_new)]);
            }
        }

    }

    public static function get_dataUsaha($request){

        $data = Usaha::join("tb_detail_usaha" , "tb_detail_usaha.id_detail_usaha" , "tb_usaha.id_detail_usaha")->join("tb_penanggung_jawab","tb_penanggung_jawab.id_penanggung_jawab",'tb_usaha.id_penanggung_jawab')->orderBy("id_usaha","desc")->get();

        return $data;

    }

    public static function post_search_usaha($request){

        $data = Usaha::join("tb_detail_usaha" , "tb_detail_usaha.id_detail_usaha" , "tb_usaha.id_detail_usaha")->join("tb_penanggung_jawab","tb_penanggung_jawab.id_penanggung_jawab",'tb_usaha.id_penanggung_jawab')->where("tb_detail_usaha.nama_usaha","like","%".$request->input('val')."%")->orderBy("id_usaha","desc")->paginate(10);

        return $data;

    }
    
    public static function get_detailUsaha($index){

        $data = Usaha::join("tb_detail_usaha" , "tb_detail_usaha.id_detail_usaha" , "tb_usaha.id_detail_usaha")->join("tb_penanggung_jawab","tb_penanggung_jawab.id_penanggung_jawab",'tb_usaha.id_penanggung_jawab')->join("tb_data_banjar" , "tb_data_banjar.id_data_banjar" , "tb_detail_usaha.id_banjar")->where("id_usaha",$index)->firstOrfail();

        return $data;

    }
    
    public static function post_pembayaran_baru($request,$method){

        //$slide = "0";


        $originalImage= $request->file('f_bukti_pembayaran');
        

        $menu_name = time().str_shuffle("abcdefghijklmnopqrstuvwxyz").".".$originalImage->getClientOriginalExtension();
    
        $thumbnailImage = \Intervention\Image\Facades\Image::make($originalImage);
        $thumbnailPath = public_path()."/bukti_pembayaran/thumbnail/";
        $originalPath = public_path()."/bukti_pembayaran/";
        $thumbnailImage->save($originalPath.$menu_name);
        $thumbnailImage->resize(150,150);
        $thumbnailImage->save($thumbnailPath.$menu_name); 

        $data                            = new Danapunia;
        $data->id_usaha                  = $request->t_hidden_usaha;
        $data->jumlah_dana               = $request->teks_input_pembayarans;
        $data->charge                    = 0;
        $data->tanggal_pembayaran        = $request->tanggal_bukti_pembayaran;
        $data->bulan                     = $request->t_bulan_pembayaran;
        $data->tahun                     = date("Y");
        $data->bukti_pembayaran          = $menu_name;
        $data->metode                    = ucwords($method);
        
        $data->aktif                     = "1";

        $data->save();
       
        return DB::getPdo()->lastInsertId();

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

    public static function approve_usaha($index){
        Usaha::where("id_usaha" , $index)->update(array("aktif_status" => "1"));

        return "success";
    }

    /*public function adv_city()
    {
        return $this->hasMany('App\tb_adv_city', 'id_adv_city', 'id_adv_city');
    }*/
    
}
