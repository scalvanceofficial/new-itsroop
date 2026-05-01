<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends BaseModel
{
    protected $fillable = [
        'index',
        'status',
        'thumbnail_image',
        'main_image',
        'title',
        'slug',
        'description_1',
        'description_2',
    ];
}
