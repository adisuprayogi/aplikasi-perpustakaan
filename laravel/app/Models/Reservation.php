<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'member_id',
        'item_id',
        'branch_id',
        'processed_by',
        'reservation_date',
        'expiry_date',
        'notification_sent',
        'status',
        'notes',
        'ready_at',
        'fulfilled_at',
        'cancelled_at',
        'priority',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reservation_date' => 'date',
        'expiry_date' => 'date',
        'notification_sent' => 'boolean',
        'ready_at' => 'datetime',
        'fulfilled_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the member for this reservation.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the item for this reservation.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(CollectionItem::class);
    }

    /**
     * Get the branch for this reservation.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who processed this reservation.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if reservation is expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'pending' && $this->expiry_date->isPast();
    }

    /**
     * Check if reservation is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'pending' && !$this->isExpired();
    }
}
