<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Property;
use App\Models\BaseModel;
use App\Traits\Hashidable;
use App\Models\PropertyValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductPropertyValue extends BaseModel
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'product_id',
        'property_id',
        'property_value_id',
        'is_primary',
        'color_name',
        'color_code',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function propertyValue()
    {
        return $this->belongsTo(PropertyValue::class, 'property_value_id');
    }
}
