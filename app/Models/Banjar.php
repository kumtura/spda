<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banjar extends Model
{   
    //public $timestamps = false; 
    //
    protected $primaryKey = 'id_data_banjar';
    protected $fillable = ['id_data_banjar', 'nama_banjar', 'alamat_banjar', 'gambar_banjar', 'id_user_kelian', 'alamat_kelian_adat', 'no_telp_kelian_adat', 'nama_kelian_dinas', 'alamat_kelian_dinas', 'no_telp_kelian_dinas', 'aktif'];
    protected $table='tb_data_banjar';

    public static function get_databanjar($request){

        $data = Banjar::where("aktif","1")->orderBy("id_data_banjar" , "desc")->get();

        return $data;

    }

    public static function post_data_banjar($request){

        $data = new Banjar;
        $data->nama_banjar   = $request->t_nama_banjar;
        $data->alamat_banjar = $request->t_alamat_banjar;
        $data->id_user_kelian = $request->t_kelian_adat ?: null;
        $data->alamat_kelian_adat = $request->t_alamat_kelian_adat ?: null;
        $data->no_telp_kelian_adat = $request->t_no_telp_kelian_adat ?: null;
        $data->nama_kelian_dinas = $request->t_nama_kelian_dinas ?: null;
        $data->alamat_kelian_dinas = $request->t_alamat_kelian_dinas ?: null;
        $data->no_telp_kelian_dinas = $request->t_no_telp_kelian_dinas ?: null;

        // Handle gambar banjar upload
        if ($request->hasFile('t_gambar_banjar')) {
            $file = $request->file('t_gambar_banjar');
            $filename = 'banjar_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('storage/banjar');
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, true);
            $file->move($uploadDir, $filename);
            $data->gambar_banjar = 'storage/banjar/' . $filename;
        }

        $data->save();

        return $data;

    }

    public static function post_hapus_banjar($request){

        $data = Banjar::where("id_data_banjar" , $request->id)->update(array("aktif" => "0"));

        return $data;

    }

    public static function post_editdata_banjar($request){

        $updateData = [
            "nama_banjar" => $request->t_nama_banjar, 
            "alamat_banjar" => $request->t_alamat_banjar,
            "id_user_kelian" => $request->t_kelian_adat ?: null,
            "alamat_kelian_adat" => $request->t_alamat_kelian_adat ?: null,
            "no_telp_kelian_adat" => $request->t_no_telp_kelian_adat ?: null,
            "nama_kelian_dinas" => $request->t_nama_kelian_dinas ?: null,
            "alamat_kelian_dinas" => $request->t_alamat_kelian_dinas ?: null,
            "no_telp_kelian_dinas" => $request->t_no_telp_kelian_dinas ?: null,
        ];

        // Handle gambar banjar upload
        if ($request->hasFile('t_gambar_banjar')) {
            $file = $request->file('t_gambar_banjar');
            $filename = 'banjar_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('storage/banjar');
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, true);
            $file->move($uploadDir, $filename);
            $updateData['gambar_banjar'] = 'storage/banjar/' . $filename;
        }

        $data = Banjar::where("id_data_banjar" , $request->t_id_banjar)->update($updateData);

        return $data;

    }


    public function userKelian()
    {
        return $this->belongsTo(User::class, 'id_user_kelian', 'id');
    }
    
}
