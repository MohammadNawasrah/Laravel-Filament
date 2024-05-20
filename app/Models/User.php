<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'Admin';
    public const ROLE_EDITOR = 'Editor';
    public const ROLE_USER = 'User';

    public function canAccessPanel(Panel $panel):bool{
        return $this->type ==self::ROLE_ADMIN || $this->type ==self::ROLE_EDITOR ;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "type"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, "post_user")->withTimestamps();
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, "commentable");
    }

    public function isAdmin(){
        return $this->type===self::ROLE_ADMIN;
    }
    public function isEditor(){
        return $this->type===self::ROLE_EDITOR;
    }

    public static function permissions($pageName,$actionName){
        $userId = auth()->user()->id;
        $permissions = UserPermission::where('user_id', $userId)->get();
        // Group permissions by page_name
        $groupedPermissions = $permissions->groupBy('page_name');

        // Check if the page name exists in the grouped permissions
        if (!isset($groupedPermissions[strtolower($pageName)])) {
            return false;
        }

        // Get the actions for the specified page name
        $actions = $groupedPermissions[strtolower($pageName)]->pluck('actions')->flatten();

        // Check if the action exists in the actions list
        return $actions->contains($actionName);
    }
}
