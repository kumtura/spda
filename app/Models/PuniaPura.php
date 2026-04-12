<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuniaPura extends Model
{
    protected $table = 'tb_punia_pura';
    protected $primaryKey = 'id_punia_pura';
    protected $fillable = [
        'id_pura', 'nama_donatur', 'email', 'no_wa', 'is_anonymous',
        'nominal', 'metode_pembayaran', 'xendit_id', 'payment_data',
        'status_pembayaran', 'tanggal_pembayaran', 'keterangan',
        'bukti_transfer', 'status_verifikasi', 'catatan_verifikasi',
        'charge', 'aktif'
    ];

    public function pura()
    {
        return $this->belongsTo(Pura::class, 'id_pura', 'id_pura');
    }
}
