<?php

namespace App\Models;

use App\Models\Product;
use App\Models\BaseModel;
use App\Traits\Hashidable;
use App\Models\PropertyValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends BaseModel
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'product_id',
        'property_value_id',
        'image'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function propertyValue()
    {
        return $this->belongsTo(PropertyValue::class);
    }
}
