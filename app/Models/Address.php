<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_first_name',
        'recipient_last_name',
        'address_line_1',
        'address_line_2',
        'city',
        'pincode',
        'type',
        'default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
