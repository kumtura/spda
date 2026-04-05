<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiCounter extends Model
{
    protected $table = 'tb_absensi_counter';
    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_user',
        'id_objek_wisata',
        'waktu_masuk',
        'waktu_keluar',
        'foto_masuk',
        'foto_keluar',
        'lokasi_masuk',
        'lokasi_keluar',
        'catatan',
    ];

    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function objekWisata()
    {
        return $this->belongsTo(ObjekWisata::class, 'id_objek_wisata', 'id_objek_wisata');
    }

    /**
     * Check if this shift is still active (belum clock-out)
     */
    public function isActive(): bool
    {
        return is_null($this->waktu_keluar);
    }

    /**
     * Get shift duration in minutes
     */
    public function getDurasiMenitAttribute(): ?int
    {
        if (!$this->waktu_keluar) {
            return null;
        }
        return $this->waktu_masuk->diffInMinutes($this->waktu_keluar);
    }
}
