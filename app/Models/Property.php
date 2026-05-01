<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Traits\Hashidable;
use App\Models\PropertyValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends BaseModel
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'name',
        'label',
        'slug',
        'is_color',
        'status',
        'index',
        'type',
        'sex',
    ];

    public function propertyValues()
    {
        return $this->hasMany(PropertyValue::class);
    }
}
