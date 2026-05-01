<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends BaseModel
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'video',
        'url',
        'index',
        'status'
    ];
}
