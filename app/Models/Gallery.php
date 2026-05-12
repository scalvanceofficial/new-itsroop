<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'images',
        'links',
    ];

    protected $casts = [
        'images' => 'array',
        'links'  => 'array',
    ];
}
