<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends BaseModel
{
    protected $fillable = [
        'index',
        'status',
        'image',
        'name',
        'title',
        'purchase_item',
        'description',
    ];
}
