<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use App\Traits\Hashidable;
use App\Models\OrderProduct;
use App\Models\ReturnProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'status',
        'user_id',
        'address_id',
        'order_number',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'shiprocket_order_id',
        'shiprocket_shipment_id',
        'shiprocket_status',
        'cancellation_reason',
        'shipping_charges',
        'gst',
        'total_amount',
        'payment_status',
        'payment_method',
        'length',
        'breadth',
        'height',
        'weight',
        'shiprocket_order_create_response',
        'tracking_number',
        'courier_name',
        'tracking_url',
        'estimated_delivery_date',
        'shiprocket_tracking_response',
        'coupon_code_id',
        'currency_code',
        'exchange_rate',
    ];

    protected $casts = [
        'shiprocket_order_create_response' => 'array',
        'shiprocket_tracking_response' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    public function coupon_code()
    {
        return $this->belongsTo(CouponCode::class);
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function trackingHistories()
    {
        return $this->hasMany(OrderTrackingHistory::class);
    }

    public function returnProducts()
    {
        return $this->hasMany(ReturnProduct::class);
    }
}
