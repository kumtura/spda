<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatBagiHasil extends Model
{
    protected $table = 'tb_riwayat_bagi_hasil';
    protected $primaryKey = 'id_riwayat';

    protected $fillable = [
        'jenis_punia',
        'id_pembayaran',
        'id_data_banjar',
        'nominal_total',
        'persen_desa',
        'persen_banjar',
        'nominal_desa',
        'nominal_banjar',
        'metode_pembayaran',
        'status_setor_desa',
        'status_setor_banjar',
        'override_status_setor_desa',
        'override_status_setor_banjar',
        'tanggal',
        'aktif',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'nominal_total' => 'decimal:2',
        'nominal_desa' => 'decimal:2',
        'nominal_banjar' => 'decimal:2',
        'persen_desa' => 'decimal:2',
        'persen_banjar' => 'decimal:2',
        'override_status_setor_desa' => 'boolean',
        'override_status_setor_banjar' => 'boolean',
    ];

    public function banjar()
    {
        return $this->belongsTo(Banjar::class, 'id_data_banjar', 'id_data_banjar');
    }
}
