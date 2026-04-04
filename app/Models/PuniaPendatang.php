<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuniaPendatang extends Model
{
    protected $table = 'tb_punia_pendatang';
    protected $primaryKey = 'id_punia_pendatang';
    
    protected $fillable = [
        'id_pendatang',
        'id_acara_punia',
        'jenis_punia',
        'nama_acara',
        'periode_rutin',
        'bulan_tahun',
        'nominal',
        'status_pembayaran',
        'metode_pembayaran',
        'tanggal_bayar',
        'petugas_id',
        'keterangan',
        'catatan_hapus',
        'dihapus_oleh',
        'tanggal_hapus',
        'aktif'
    ];
    
    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'nominal' => 'decimal:2'
    ];
    
    public function pendatang()
    {
        return $this->belongsTo(Pendatang::class, 'id_pendatang', 'id_pendatang');
    }
    
    public function acaraPunia()
    {
        return $this->belongsTo(AcaraPunia::class, 'id_acara_punia', 'id_acara_punia');
    }
    
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id', 'id');
    }
}
