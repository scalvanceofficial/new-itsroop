<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends BaseModel
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'show_in_navbar',
        'status',
    ];
}
