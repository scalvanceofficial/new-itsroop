<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Product;
use App\Models\ReturnProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'property_values',
        'property_value_names',
        'quantity',
        'price',
        'gst',
        'total_amount',
    ];

    protected $casts = [
        'property_values' => 'array'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function returnedProduct()
    {
        return $this->hasOne(ReturnProduct::class, 'order_product_id');
    }
}
