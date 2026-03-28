<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentChannel extends Model
{
    protected $table = 'tb_payment_channels';
    protected $primaryKey = 'id_payment_channel';
    protected $fillable = [
        'name',
        'code',
        'type',
        'icon_url',
        'is_active'
    ];
}
