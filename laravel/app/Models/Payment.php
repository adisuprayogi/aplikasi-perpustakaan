<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payment_no',
        'member_id',
        'loan_id',
        'branch_id',
        'received_by',
        'amount',
        'payment_method',
        'payment_reference',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the member for this payment.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the loan for this payment.
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Get the branch for this payment.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who received this payment.
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Alias for receivedBy - used in views.
     */
    public function processedBy(): BelongsTo
    {
        return $this->receivedBy();
    }

    /**
     * Get payment date (alias for created_at).
     */
    public function getPaymentDateAttribute()
    {
        return $this->created_at;
    }

    /**
     * Get reference number (alias for payment_reference).
     */
    public function getReferenceNumberAttribute()
    {
        return $this->payment_reference;
    }
}
