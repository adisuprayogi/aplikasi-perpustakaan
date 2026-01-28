<?php

namespace App\Models;

use App\Services\FineCalculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Loan extends Model
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
        'loan_branch_id',
        'return_branch_id',
        'processed_by',
        'loan_date',
        'due_date',
        'return_date',
        'renewal_count',
        'fine',
        'paid_fine',
        'status',
        'notes',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'fine' => 'decimal:2',
        'paid_fine' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the member for this loan.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the item for this loan.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(CollectionItem::class);
    }

    /**
     * Get the branch where the loan was made.
     */
    public function loanBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the branch where the item was returned.
     */
    public function returnBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who processed this loan.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all payments for this loan.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if loan is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === 'active' && $this->due_date->isPast();
    }

    /**
     * Get the number of days overdue.
     */
    public function getDaysOverdueAttribute(): int
    {
        return app(FineCalculator::class)->calculateOverdueDays($this);
    }

    /**
     * Get calculated fine amount (not stored in database).
     */
    public function getCalculatedFineAttribute(): float
    {
        return app(FineCalculator::class)->calculateFine($this);
    }

    /**
     * Update the stored fine amount based on current calculation.
     */
    public function updateFine(): void
    {
        $this->fine = $this->calculated_fine;
        $this->saveQuietly();
    }

    /**
     * Get remaining fine (unpaid).
     */
    public function getRemainingFineAttribute(): float
    {
        return max(0, $this->fine - $this->paid_fine);
    }

    /**
     * Get returned_at as an alias for return_date.
     */
    public function getReturnedAtAttribute()
    {
        return $this->return_date;
    }

    /**
     * Check if loan can be renewed.
     */
    public function canBeRenewed(): bool
    {
        return $this->status === 'active'
            && !$this->isOverdue()
            && $this->renewal_count < 2
            && !$this->item->reservations()->where('status', 'pending')->exists();
    }

    /**
     * Scope to filter by status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get active loans.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get overdue loans.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
            ->where('due_date', '<', now());
    }

    /**
     * Scope to filter by member.
     */
    public function scopeForMember($query, int $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    /**
     * Scope to filter by branch.
     */
    public function scopeFromBranch($query, int $branchId)
    {
        return $query->where('loan_branch_id', $branchId);
    }

    /**
     * Scope to get loans due within X days.
     */
    public function scopeDueWithin($query, int $days)
    {
        return $query->whereBetween('due_date', [
            now(),
            now()->addDays($days),
        ]);
    }
}
