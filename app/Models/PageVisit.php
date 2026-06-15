<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    public $timestamps  = false;
    protected $fillable = ['page', 'session_id', 'ip', 'user_id', 'created_at'];
    protected $casts    = ['created_at' => 'datetime'];
}
