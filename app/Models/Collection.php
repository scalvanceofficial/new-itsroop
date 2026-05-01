<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collection extends BaseModel
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'product_ids',
        'name',
        'slug',
        'index',
        'status',
    ];

    protected $casts = [
        'product_ids' => 'array',
    ];
}
