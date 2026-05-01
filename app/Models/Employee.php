<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'mobile',
        'designation'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
