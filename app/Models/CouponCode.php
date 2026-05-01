<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CouponCode extends BaseModel
{
    protected $fillable = [
        'coupon_code',
        'status',
        'start_date',
        'end_date',
        'percentage',
        'minimum_order_amount',
        'currency_code',
    ];
}
