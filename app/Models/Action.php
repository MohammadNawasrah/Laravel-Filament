<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "slug",
        "pages_name"
    ];
    protected $casts = [
        "pages_name" => "array"
    ];
}
