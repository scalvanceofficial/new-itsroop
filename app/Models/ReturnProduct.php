<?php

namespace App\Models;

use App\Models\Order;
use App\Models\BaseModel;
use App\Traits\Hashidable;
use App\Models\OrderProduct;
use App\Models\ReturnStatusLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnProduct extends BaseModel
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'order_id',
        'order_product_id',
        'transaction_id',
        'settlement_date',
        'product_received_remark',
        'return_number',
        'return_quantity',
        'status',
        'remark',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class, 'order_product_id');
    }

    public function statusLogs()
    {
        return $this->hasMany(ReturnStatusLog::class, 'return_product_id');
    }
}
