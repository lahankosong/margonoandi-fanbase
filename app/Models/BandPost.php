<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BandPost extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'roles_needed', 'genres',
        'location', 'status', 'urgent',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'urgent'  => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rolesArray()
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->roles_needed))));
    }

    public function genresArray()
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->genres))));
    }
}
