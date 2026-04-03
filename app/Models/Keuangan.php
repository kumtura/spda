<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $table = 'tb_keuangan';
    protected $primaryKey = 'id_keuangan';

    protected $fillable = [
        'jenis',
        'nominal',
        'keterangan',
        'kategori',
        'metode_pembayaran',
        'penerima',
        'no_rekening',
        'nama_bank',
        'bukti',
        'id_user',
        'tanggal',
        'aktif',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
