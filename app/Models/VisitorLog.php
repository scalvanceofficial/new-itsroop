<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'user_agent',
        'url',
        'referrer',
        'visit_count',
        'last_visited_at',
    ];
}
