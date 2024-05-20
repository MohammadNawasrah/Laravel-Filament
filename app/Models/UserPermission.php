<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "actions",
        "page_name"
    ];
    protected $casts = [
        'actions' => 'array',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    // public static function getUniquePageNames(): array
    // {
    //     // Get all unique page names for each user_id
    //     $uniquePageNames = UserPermission::select('page_name')
    //         ->distinct()
    //         ->pluck('page_name')
    //         ->toArray();

    //     return $uniquePageNames;
    // }
}
