<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GigPost extends Model
{
    protected $fillable = [
        'user_id', 'title', 'type', 'description',
        'location', 'date_event', 'requirements', 'status',
    ];

    protected $casts = [
        'user_id'    => 'integer',
        'date_event' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function types(): array
    {
        return [
            'audisi_band'    => '🎸 Audisi Band',
            'open_mic'       => '🎤 Open Mic',
            'manggung'       => '🎪 Manggung / Show',
            'session_player' => '🎵 Session Player',
            'rekaman'        => '🎙 Sesi Rekaman',
            'workshop'       => '📚 Workshop Musik',
            'jingle'         => '🎯 Jingle / Komersial',
            'lainnya'        => '📢 Lainnya',
        ];
    }

    public static function typeLabel(string $type): string
    {
        return static::types()[$type] ?? '📢 ' . $type;
    }
}
