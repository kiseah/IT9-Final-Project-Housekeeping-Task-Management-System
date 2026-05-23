<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'room_id',
        'description',
        'status',
    ];

    // ✅ Remove request_type — services are now in request_services table

    // ✅ A request belongs to a guest
    public function guest()
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    // ✅ A request belongs to a room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // ✅ A request has many services
    public function services()
    {
        return $this->hasMany(RequestService::class);
    }

    // ✅ A request may have one task
    public function task()
    {
        return $this->hasOne(Task::class);
    }

    // ✅ Helper: get services as comma-separated string
    public function getServicesListAttribute(): string
    {
        return $this->services->pluck('service_type')->join(', ');
    }
}