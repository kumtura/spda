<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $table = 'tb_api_logs';
    protected $primaryKey = 'id_api_log';
    public $timestamps = false;

    protected $fillable = [
        'id_api_token', 'endpoint', 'method',
        'ip_address', 'response_code', 'response_time_ms',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function apiToken()
    {
        return $this->belongsTo(ApiToken::class, 'id_api_token', 'id_api_token');
    }
}
