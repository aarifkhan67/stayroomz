<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id', 'user_id', 'guest_name', 'ip_address', 'rating', 'comment'
    ];

    public function room() {
        return $this->belongsTo(Room::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
} 