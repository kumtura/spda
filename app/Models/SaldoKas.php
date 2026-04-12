<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaldoKas extends Model
{
    protected $table = 'tb_saldo_kas';
    protected $primaryKey = 'id_saldo_kas';

    protected $fillable = [
        'id_data_banjar',
        'saldo_cash',
        'saldo_online',
        'total_masuk',
        'total_keluar',
    ];

    protected $casts = [
        'saldo_cash' => 'decimal:2',
        'saldo_online' => 'decimal:2',
        'total_masuk' => 'decimal:2',
        'total_keluar' => 'decimal:2',
    ];

    public function banjar()
    {
        return $this->belongsTo(Banjar::class, 'id_data_banjar', 'id_data_banjar');
    }

    /**
     * Get or create saldo record for a banjar (or Desa if null).
     */
    public static function getOrCreate($idDataBanjar = null)
    {
        return self::firstOrCreate(
            ['id_data_banjar' => $idDataBanjar],
            ['saldo_cash' => 0, 'saldo_online' => 0, 'total_masuk' => 0, 'total_keluar' => 0]
        );
    }

    /**
     * Get total saldo (cash + online).
     */
    public function getTotalSaldoAttribute()
    {
        return $this->saldo_cash + $this->saldo_online;
    }
}
