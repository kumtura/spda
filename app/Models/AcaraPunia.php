<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcaraPunia extends Model
{
    protected $table = 'tb_acara_punia';
    protected $primaryKey = 'id_acara_punia';
    
    protected $fillable = [
        'nama_acara',
        'deskripsi',
        'nominal',
        'tanggal_acara',
        'batas_pembayaran',
        'status',
        'created_by',
        'aktif'
    ];
    
    protected $casts = [
        'tanggal_acara' => 'date',
        'batas_pembayaran' => 'date',
        'nominal' => 'decimal:2'
    ];
    
    public function puniaPendatang()
    {
        return $this->hasMany(PuniaPendatang::class, 'id_acara_punia', 'id_acara_punia');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
