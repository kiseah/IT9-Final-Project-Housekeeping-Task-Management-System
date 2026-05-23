<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;

// class User extends Authenticatable
// {
//     use HasFactory, Notifiable;

//     protected $fillable = [
//         'first_name',
//         'middle_name',
//         'last_name',
//         'email',
//         'password',
//         'role',
//         'room_id',
//     ];

//     protected $hidden = [
//         'password',
//         'remember_token',
//     ];

//     protected function casts(): array
//     {
//         return [
//             'email_verified_at' => 'datetime',
//             'password' => 'hashed',
//         ];
//     }

//     // ✅ Helper: Get full name
//     public function getFullNameAttribute(): string
//     {
//         $middle = $this->middle_name ? ' ' . $this->middle_name . ' ' : ' ';
//         return $this->first_name . $middle . $this->last_name;
//     }

//     // ✅ Role helpers
//     public function isAdmin(): bool
//     {
//         return $this->role === 'admin';
//     }

//     public function isHousekeeper(): bool
//     {
//         return $this->role === 'housekeeper';
//     }

//     public function isGuest(): bool
//     {
//         return $this->role === 'guest';
//     }

//     // Admin creates many users (not a DB relation, just for reference)

//     // A housekeeper has many tasks assigned
//     public function tasks()
//     {
//         return $this->hasMany(Task::class, 'housekeeper_id');
//     }

//     // A guest has many requests
//     public function requests()
//     {
//         return $this->hasMany(Request::class, 'guest_id');
//     }

//     // A guest belongs to a room
//     public function room()
//     {
//         return $this->belongsTo(Room::class);
//     }
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role',
        'room_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'deleted_at'        => 'datetime',
        ];
    }

    // ✅ Full name accessor
    public function getFullNameAttribute(): string
    {
        $middle = $this->middle_name ? ' ' . $this->middle_name . ' ' : ' ';
        return $this->first_name . $middle . $this->last_name;
    }

    // ✅ Role helpers
    public function isAdmin(): bool       { return $this->role === 'admin'; }
    public function isHousekeeper(): bool { return $this->role === 'housekeeper'; }
    public function isGuest(): bool       { return $this->role === 'guest'; }

    // ✅ Relationships
    public function tasks()
    {
        return $this->hasMany(Task::class, 'housekeeper_id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'guest_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}