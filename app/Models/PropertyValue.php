<?php

namespace App\Models;

use App\Models\Property;
use App\Models\BaseModel;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyValue extends BaseModel
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'property_id',
        'name',
        'color',
        'index',
        'status'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
