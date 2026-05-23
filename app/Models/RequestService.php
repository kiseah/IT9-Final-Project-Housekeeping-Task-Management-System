<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestService extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'service_type',
    ];

    // ✅ Belongs to one request
    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}