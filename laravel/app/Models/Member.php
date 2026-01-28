<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Member extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'member_no',
        'type',
        'id_number',
        'name',
        'email',
        'phone',
        'address',
        'branch_id',
        'photo',
        'status',
        'valid_from',
        'valid_until',
        'total_fines',
        'total_loans',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
        'total_fines' => 'decimal:2',
        'total_loans' => 'integer',
        'metadata' => 'array',
    ];

    /**
     * Get the user associated with the member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the branch for the member.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get all loans for the member.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get all active loans for the member.
     */
    public function activeLoans(): HasMany
    {
        return $this->hasMany(Loan::class)->where('status', 'active');
    }

    /**
     * Get all overdue loans for the member.
     */
    public function overdueLoans(): HasMany
    {
        return $this->hasMany(Loan::class)->where('status', 'overdue');
    }

    /**
     * Get all reservations for the member.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get all payments for the member.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all fines for the member.
     */
    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    /**
     * Check if member is currently eligible for borrowing.
     */
    public function isEligibleForBorrowing(): bool
    {
        return $this->status === 'active'
            && $this->valid_until?->isFuture()
            && $this->total_fines < 50000; // Suspend threshold
    }

    /**
     * Check if member has overdue loans.
     */
    public function hasOverdueLoans(): bool
    {
        return $this->activeLoans()
            ->where('due_date', '<', now())
            ->exists();
    }

    /**
     * Get current loans count for the member.
     */
    public function getCurrentLoansCount(): int
    {
        return $this->activeLoans()->count();
    }

    /**
     * Scope to filter by active status.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to filter by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by branch.
     */
    public function scopeFromBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId);
    }
}
