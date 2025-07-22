<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the rooms for the user.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a room owner
     */
    public function isRoomOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Check if user is a renter
     */
    public function isRenter(): bool
    {
        return $this->role === 'renter';
    }

    /**
     * Check if user can list rooms (owner or admin)
     */
    public function canListRooms(): bool
    {
        return in_array($this->role, ['owner', 'admin']);
    }

    /**
     * Check if user can favorite rooms (renter or owner)
     */
    public function canFavoriteRooms(): bool
    {
        return in_array($this->role, ['renter', 'owner', 'admin']);
    }

    /**
     * Get user's role display name
     */
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'owner' => 'Room Owner',
            'renter' => 'Renter',
            default => 'User'
        };
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get user's room count
     */
    public function getRoomCount(): int
    {
        return $this->rooms()->count();
    }

    /**
     * Get user's available rooms count
     */
    public function getAvailableRoomCount(): int
    {
        return $this->rooms()->where('availability', 'available')->count();
    }

    public function roomReviews() {
        return $this->hasMany(RoomReview::class);
    }

    /**
     * Get the user's favorites
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get the rooms that the user has favorited
     */
    public function favoritedRooms()
    {
        return $this->belongsToMany(Room::class, 'favorites');
    }

    /**
     * Check if user has favorited a specific room
     */
    public function hasFavorited($roomId)
    {
        return $this->favorites()->where('room_id', $roomId)->exists();
    }

    /**
     * Get user's favorites count
     */
    public function getFavoritesCount(): int
    {
        return $this->favorites()->count();
    }

    /**
     * Get payments made by this user (as renter)
     */
    public function paymentsAsRenter(): HasMany
    {
        return $this->hasMany(Payment::class, 'renter_id');
    }

    /**
     * Get payments received by this user (as landlord)
     */
    public function paymentsAsLandlord(): HasMany
    {
        return $this->hasMany(Payment::class, 'landlord_id');
    }

    /**
     * Get all payments for this user (both as renter and landlord)
     */
    public function payments()
    {
        return Payment::where('renter_id', $this->id)
            ->orWhere('landlord_id', $this->id);
    }
}
