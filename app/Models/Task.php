<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'housekeeper_id',
        'room_id',
        'request_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'is_locked',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'due_date'  => 'date',
    ];

    // ✅ Auto-lock when status is set to completed or cancelled
    protected static function booted(): void
    {
        static::saving(function (Task $task) {
            if (in_array($task->status, ['completed', 'cancelled'])) {
                $task->is_locked = true;
            }
        });
    }

    // ✅ Relationships
    public function housekeeper()
    {
        return $this->belongsTo(User::class, 'housekeeper_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    // ✅ Scopes for filtering
    public function scopeActive($query)
    {
        return $query->where('is_locked', false);
    }

    public function scopeHistory($query)
    {
        return $query->where('is_locked', true);
    }
}