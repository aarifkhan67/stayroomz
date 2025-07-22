<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'renter_id',
        'landlord_id',
        'room_id',
        'payment_id',
        'amount',
        'payment_type',
        'payment_method',
        'status',
        'description',
        'transaction_id',
        'qr_code',
        'payment_proof',
        'paid_at',
        'due_date',
        'payment_details',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'due_date' => 'datetime',
            'payment_details' => 'array',
        ];
    }

    /**
     * Get the renter (user who is paying).
     */
    public function renter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    /**
     * Get the landlord (user who is receiving payment).
     */
    public function landlord(): BelongsTo
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    /**
     * Get the room associated with this payment.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Generate a unique payment ID.
     */
    public static function generatePaymentId(): string
    {
        return 'PAY' . date('Ymd') . Str::random(8);
    }

    /**
     * Check if payment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return '₹' . number_format($this->amount, 0);
    }

    /**
     * Get payment type display name.
     */
    public function getPaymentTypeDisplayAttribute(): string
    {
        return ucfirst($this->payment_type);
    }

    /**
     * Get payment method display name.
     */
    public function getPaymentMethodDisplayAttribute(): string
    {
        $methods = [
            'upi' => 'UPI',
            'bank_transfer' => 'Bank Transfer',
            'cash' => 'Cash',
            'cheque' => 'Cheque',
            'online' => 'Online Payment',
        ];

        return $methods[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $classes = [
            'pending' => 'bg-warning',
            'completed' => 'bg-success',
            'failed' => 'bg-danger',
            'cancelled' => 'bg-secondary',
        ];

        return $classes[$this->status] ?? 'bg-secondary';
    }

    /**
     * Scope for completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for payments by renter.
     */
    public function scopeByRenter($query, $renterId)
    {
        return $query->where('renter_id', $renterId);
    }

    /**
     * Scope for payments by landlord.
     */
    public function scopeByLandlord($query, $landlordId)
    {
        return $query->where('landlord_id', $landlordId);
    }
} 