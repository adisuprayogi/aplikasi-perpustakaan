<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'collection_id',
        'barcode',
        'call_number',
        'branch_id',
        'location',
        'status',
        'condition',
        'acquired_date',
        'acquired_price',
        'source',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'acquired_date' => 'date',
        'acquired_price' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the collection for this item.
     */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    /**
     * Get the branch for this item.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get all loans for this item.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get the active loan for this item.
     */
    public function activeLoan(): HasMany
    {
        return $this->hasOne(Loan::class)->where('status', 'active');
    }

    /**
     * Get all reservations for this item.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get all transfers for this item.
     */
    public function transfers(): HasMany
    {
        return $this->hasMany(ItemTransfer::class);
    }

    /**
     * Check if item is available for loan.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Check if item is borrowed.
     */
    public function isBorrowed(): bool
    {
        return $this->status === 'borrowed';
    }

    /**
     * Check if item is reserved.
     */
    public function isReserved(): bool
    {
        return $this->status === 'reserved';
    }

    /**
     * Scope to filter by status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by branch.
     */
    public function scopeFromBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope to get available items.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
