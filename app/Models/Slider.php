<?php

namespace App\Models;

use App\Models\Category;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slider extends BaseModel
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'category_id',
        'title',
        'subtitle',
        'image', //desktop image
        'mobile_image',
        'index',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
