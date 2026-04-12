<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanBagiHasil extends Model
{
    protected $table = 'tb_pengaturan_bagi_hasil';
    protected $primaryKey = 'id_pengaturan';

    protected $fillable = [
        'jenis_punia',
        'id_data_banjar',
        'persen_desa',
        'persen_banjar',
        'berlaku_sejak',
        'keterangan',
        'aktif',
    ];

    protected $casts = [
        'berlaku_sejak' => 'date',
        'persen_desa' => 'decimal:2',
        'persen_banjar' => 'decimal:2',
    ];

    public function banjar()
    {
        return $this->belongsTo(Banjar::class, 'id_data_banjar', 'id_data_banjar');
    }

    /**
     * Get the active percentage setting for a given punia type and banjar.
     * Priority: specific banjar setting > global setting.
     */
    public static function getPersentase($jenisPunia, $idDataBanjar = null)
    {
        // 1. Check for specific banjar override
        if ($idDataBanjar) {
            $specific = self::where('jenis_punia', $jenisPunia)
                ->where('id_data_banjar', $idDataBanjar)
                ->where('aktif', 1)
                ->where('berlaku_sejak', '<=', now()->toDateString())
                ->orderBy('berlaku_sejak', 'desc')
                ->first();

            if ($specific) {
                return $specific;
            }
        }

        // 2. Fall back to global setting (id_data_banjar = NULL)
        $global = self::where('jenis_punia', $jenisPunia)
            ->whereNull('id_data_banjar')
            ->where('aktif', 1)
            ->where('berlaku_sejak', '<=', now()->toDateString())
            ->orderBy('berlaku_sejak', 'desc')
            ->first();

        return $global;
    }
}
