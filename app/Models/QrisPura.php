<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrisPura extends Model
{
    protected $table = 'tb_qris_pura';
    protected $primaryKey = 'id_qris_pura';
    protected $fillable = [
        'id_pura', 'qris_content', 'qris_image', 'nmid',
        'merchant_name', 'is_active'
    ];

    public function pura()
    {
        return $this->belongsTo(Pura::class, 'id_pura', 'id_pura');
    }
}
