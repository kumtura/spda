<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiketWisata extends Model
{
    protected $table = 'tb_tiket_wisata';
    protected $primaryKey = 'id_tiket';
    
    protected $fillable = [
        'kode_tiket',
        'id_objek_wisata',
        'nama_pengunjung',
        'email',
        'no_telp',
        'jumlah_tiket',
        'total_harga',
        'tanggal_kunjungan',
        'metode_pembelian',
        'metode_pembayaran',
        'xendit_id',
        'bukti_transfer',
        'status_verifikasi',
        'catatan_verifikasi',
        'status_pembayaran',
        'status_tiket',
        'waktu_scan',
        'petugas_scan',
        'qr_code',
        'aktif'
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'waktu_scan' => 'datetime',
    ];

    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek_wisata', 'id_objek_wisata');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_scan', 'id');
    }
}
