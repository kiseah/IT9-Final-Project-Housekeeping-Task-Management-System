<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'room_type',
        'status',
        'notes',
    ];

    // A room can have many tasks
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // A room can have many requests
    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}