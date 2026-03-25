<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Kategori_Usaha extends Model
{   
    //public $timestamps = false; 
    //
    protected $fillable = ['id_kategori_usaha', 'nama_kategori_usaha','aktif'];
    protected $table='tb_kategori_usaha';

    public static function get_kategoriusaha(){
        $data = Kategori_Usaha::where("aktif","1")->orderBy("nama_kategori_usaha" , "asc")->get();

        return $data;
    }
    
    public static function get_totalPunia($request){
        $data = Danapunia::select(DB::raw("SUM(jumlah_dana) as paidsum"))->join("tb_usaha" , "tb_usaha.id_usaha" , "tb_dana_punia.id_usaha")->join("tb_detail_usaha" , "tb_detail_usaha.id_detail_usaha" , "tb_usaha.id_detail_usaha")->where("tb_dana_punia.aktif","1")->orderBy("tb_dana_punia.id_usaha" , "desc")->get();

        return $data[0]->paidsum;
    }
    
    public static function get_totalPunia_inRange($awal,$akhir){
        $data = Danapunia::select(DB::raw("SUM(jumlah_dana) as paidsum"))->where("tb_dana_punia.aktif","1")->where("tb_dana_punia.tanggal_pembayaran",">=",$awal)->where("tb_dana_punia.tanggal_pembayaran","<=",$akhir)->orderBy("tb_dana_punia.id_usaha" , "desc")->get();

        return $data[0]->paidsum;
    }
    
    public static function get_detailPunia($index){
        $data = Danapunia::join("tb_usaha" , "tb_usaha.id_usaha" , "tb_dana_punia.id_usaha")->join("tb_detail_usaha" , "tb_detail_usaha.id_detail_usaha" , "tb_usaha.id_detail_usaha")->where("tb_dana_punia.aktif","1")->where("tb_dana_punia.id_dana_punia" , $index)->orderBy("tb_dana_punia.id_usaha" , "desc")->get();

        return $data;
    }
    
    public static function get_detailPunia_only($index){
        $data = Danapunia::where("tb_dana_punia.id_dana_punia" , $index)->orderBy("tb_dana_punia.id_usaha" , "desc")->get();

        return $data;
    }

    public static function post_data_detail_pngg($request){
        
        $data                                 = new Penanggung_Jawab;
        $data->status_penanggung_jawab        = $request->t_status_pngg;
        $data->nama                           = $request->t_nama_pngg;
        $data->alamat                         = $request->t_alamat_pngg;
        $data->no_telp                        = $request->t_notelp_pngg;
        $data->alamat_usaha                   = $request->t_alamatusaha_pngg;
        $data->no_wa                          = $request->t_nowa_pngg;
        
        $data->aktif                          = "1";

        $data->save();

        return DB::getPdo()->lastInsertId();
    }

    
}
