<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentSettingFactory> */
    use HasFactory;

    protected $table = 'tb_payment_settings';

    protected $fillable = [
        'gateway_name',
        'api_key',
        'secret_key',
        'webhook_token',
        'is_active',
        'is_sandbox',
    ];
}
