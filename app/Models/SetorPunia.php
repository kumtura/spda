<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetorPunia extends Model
{
    protected $table = 'tb_setor_punia';
    protected $primaryKey = 'id_setor_punia';

    protected $fillable = [
        'id_keuangan',
        'jenis_setor',
        'jenis_alur',
        'sumber_punia',
        'id_data_banjar',
        'nominal',
        'tanggal_setor',
        'keterangan',
        'penerima',
        'nama_penyerah',
        'jabatan_penyerah',
        'nama_penerima_ttd',
        'jabatan_penerima',
        'tanda_tangan',
        'id_data_banjar_tujuan',
        'nama_bank',
        'no_rekening',
        'bukti',
        'status',
        'catatan_verifikasi',
        'id_user',
        'verified_by',
        'verified_at',
        'aktif',
    ];

    protected $casts = [
        'tanggal_setor' => 'date',
        'verified_at' => 'datetime',
        'nominal' => 'decimal:2',
    ];

    public function keuangan()
    {
        return $this->belongsTo(Keuangan::class, 'id_keuangan', 'id_keuangan');
    }

    public function banjar()
    {
        return $this->belongsTo(Banjar::class, 'id_data_banjar', 'id_data_banjar');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by', 'id');
    }

    public function banjarTujuan()
    {
        return $this->belongsTo(Banjar::class, 'id_data_banjar_tujuan', 'id_data_banjar');
    }
}
