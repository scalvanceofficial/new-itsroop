<?php

namespace App\Models;

use App\Models\Product;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductPrice extends BaseModel
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'product_id',
        'property_values',
        'label',
        'actual_price',
        'discount_percentage',
        'discount_price',
        'selling_price',
        'stock',
        'model',
    ];

    protected $casts = [
        'property_values' => 'array',
    ];

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
