<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanRule extends Model
{
    protected $fillable = [
        'member_type',
        'collection_type_id',
        'loan_period',
        'max_loans',
        'fine_per_day',
        'is_renewable',
        'renew_limit',
        'is_fine_by_calendar',
        'is_active',
    ];

    protected $casts = [
        'is_renewable' => 'boolean',
        'is_fine_by_calendar' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the collection type for the loan rule.
     */
    public function collectionType(): BelongsTo
    {
        return $this->belongsTo(CollectionType::class);
    }

    /**
     * Get applicable rule for a member and collection type.
     */
    public static function getApplicableRule(string $memberType, ?int $collectionTypeId = null): ?self
    {
        $query = static::where('member_type', $memberType)
            ->where('is_active', true)
            ->orderBy('collection_type_id', 'desc');

        if ($collectionTypeId) {
            // Try to find specific rule for this collection type
            $rule = $query->where('collection_type_id', $collectionTypeId)->first();
            if ($rule) {
                return $rule;
            }
        }

        // Fall back to default rule (null collection_type_id)
        return $query->whereNull('collection_type_id')->first();
    }

    /**
     * Get member type label.
     */
    public function getMemberTypeLabelAttribute(): string
    {
        return match($this->member_type) {
            'student' => 'Mahasiswa',
            'lecturer' => 'Dosen',
            'staff' => 'Staf',
            'external' => 'Eksternal',
            default => ucfirst($this->member_type),
        };
    }
}
