<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiToken extends Model
{
    protected $table = 'tb_api_tokens';
    protected $primaryKey = 'id_api_token';

    protected $fillable = [
        'name', 'token', 'permissions', 'ip_whitelist',
        'rate_limit', 'expires_at', 'created_by', 'aktif',
    ];

    protected $casts = [
        'permissions' => 'array',
        'aktif' => 'boolean',
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    protected $hidden = ['token'];

    public static function generateToken(): string
    {
        return hash('sha256', Str::random(64));
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function isValid(): bool
    {
        if (!$this->aktif) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function hasPermission(string $permission): bool
    {
        $permissions = $this->permissions ?? [];

        if (in_array('*', $permissions)) {
            return true;
        }

        return in_array($permission, $permissions);
    }

    public function isIpAllowed(string $ip): bool
    {
        if (empty($this->ip_whitelist)) {
            return true;
        }

        $allowed = array_map('trim', explode(',', $this->ip_whitelist));

        return in_array($ip, $allowed);
    }

    public function recordUsage(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    public static function availablePermissions(): array
    {
        return [
            'read:punia'       => 'Baca Data Punia',
            'read:krama-tamiu' => 'Baca Data Krama Tamiu',
            'read:usaha'       => 'Baca Data Unit Usaha',
            'read:donasi'      => 'Baca Data Donasi',
            'read:tiket'       => 'Baca Data Tiket Wisata',
            'read:keuangan'    => 'Baca Data Keuangan',
        ];
    }
}
