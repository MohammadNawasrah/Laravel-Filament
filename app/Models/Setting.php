<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'lable',
        'value',
        'type',
        'order_data'
    ];
}
