<?php

namespace App\Models;

use App\Traits\Hashidable;
use App\Models\ReturnProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnStatusLog extends BaseModel
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'return_product_id',
        'transaction_id',
        'settlement_date',
        'product_received_remark',
        'status',
    ];

    public function statusLogs()
    {
        return $this->belongsTo(ReturnProduct::class, 'return_product_id');
    }
}
