<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['slug', 'title', 'category', 'batch', 'excerpt', 'content_markdown', 'reading_time'];

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'teori'      => 'Teori Musik',
            'produksi'   => 'Produksi',
            'kolaborasi' => 'Kolaborasi',
            'rilis'      => 'Rilis & Branding',
            default      => ucfirst($this->category),
        };
    }

    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'teori'      => '#38A8CC',
            'produksi'   => '#a855f7',
            'kolaborasi' => '#f59e0b',
            'rilis'      => '#22c55e',
            default      => '#6b7280',
        };
    }
}
