<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'location',
        'address',
        'price',
        'deposit',
        'availability',
        'available_from',
        'occupant_type',
        'amenities',
        'owner_name',
        'phone',
        'email',
        'preferred_contact',
        'image',
        'additional_images',
        'views',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amenities' => 'array',
            'additional_images' => 'array',
            'available_from' => 'date',
            'price' => 'decimal:2',
            'deposit' => 'decimal:2',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the room.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include available rooms.
     */
    public function scopeAvailable($query)
    {
        return $query->where('availability', 'available');
    }

    /**
     * Scope a query to only include featured rooms.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '₹' . number_format($this->price, 0);
    }

    /**
     * Get the formatted deposit.
     */
    public function getFormattedDepositAttribute(): string
    {
        return $this->deposit ? '₹' . number_format($this->deposit, 0) : 'Not specified';
    }

    /**
     * Check if room is available.
     */
    public function isAvailable(): bool
    {
        return $this->availability === 'available';
    }

    /**
     * Check if room is rented.
     */
    public function isRented(): bool
    {
        return $this->availability === 'rented';
    }

    /**
     * Check if room is coming soon.
     */
    public function isComingSoon(): bool
    {
        return $this->availability === 'coming-soon';
    }

    /**
     * Increment the view count.
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Get the availability badge class.
     */
    public function getAvailabilityBadgeClass(): string
    {
        return match($this->availability) {
            'available' => 'bg-success',
            'rented' => 'bg-danger',
            'coming-soon' => 'bg-warning',
            default => 'bg-secondary',
        };
    }

    /**
     * Get the availability text.
     */
    public function getAvailabilityText(): string
    {
        return match($this->availability) {
            'available' => 'Available',
            'rented' => 'Rented',
            'coming-soon' => 'Coming Soon',
            default => 'Unknown',
        };
    }

    public function reviews() {
        return $this->hasMany(RoomReview::class);
    }
    public function averageRating() {
        return $this->reviews()->avg('rating');
    }

    /**
     * Get the users who have favorited this room
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    /**
     * Get the favorites for this room
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get the favorites count for this room
     */
    public function getFavoritesCount(): int
    {
        return $this->favorites()->count();
    }

    /**
     * Check if a specific user has favorited this room
     */
    public function isFavoritedBy($userId): bool
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }
}
