<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'last_seen',
        'is_online',
    ];

    protected $casts = [
        'last_seen' => 'datetime',
        'is_online' => 'boolean',
    ];

    public function isOnline(): bool
    {
        try {
            $seen = $this->attributes['last_seen'] ?? null;
            if (!$seen) return false;
            return strtotime($seen) > (time() - 120);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function lastSeenLabel(): string
    {
        try {
            if ($this->isOnline()) return 'Online';
            $seen = $this->attributes['last_seen'] ?? null;
            if (!$seen) return 'Offline';
            return 'Aktif ' . date('H:i', strtotime($seen));
        } catch (\Throwable $e) {
            return 'Offline';
        }
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];
}