<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'type',
        'address',
        'phone',
        'email',
        'logo',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all users for this branch.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all members for this branch.
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * Get all collection items for this branch.
     */
    public function collectionItems(): HasMany
    {
        return $this->hasMany(CollectionItem::class);
    }

    /**
     * Get all loans where this branch is the loan branch.
     */
    public function loansAsLoanBranch(): HasMany
    {
        return $this->hasMany(Loan::class, 'loan_branch_id');
    }

    /**
     * Get all loans where this branch is the return branch.
     */
    public function loansAsReturnBranch(): HasMany
    {
        return $this->hasMany(Loan::class, 'return_branch_id');
    }

    /**
     * Get all reservations for this branch.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get all transfers where this branch is the source.
     */
    public function transfersFrom(): HasMany
    {
        return $this->hasMany(ItemTransfer::class, 'from_branch_id');
    }

    /**
     * Get all transfers where this branch is the destination.
     */
    public function transfersTo(): HasMany
    {
        return $this->hasMany(ItemTransfer::class, 'to_branch_id');
    }

    /**
     * Get all payments for this branch.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all settings for this branch.
     */
    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    /**
     * Get all holidays for this branch.
     */
    public function holidays(): HasMany
    {
        return $this->hasMany(Holiday::class);
    }

    /**
     * Scope to filter by active status.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
