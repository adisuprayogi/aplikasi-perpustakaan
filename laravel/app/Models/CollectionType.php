<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'name_en',
        'loan_period',
        'max_renewals',
        'is_reference',
        'is_loanable',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'loan_period' => 'integer',
        'max_renewals' => 'integer',
        'is_reference' => 'boolean',
        'is_loanable' => 'boolean',
    ];

    /**
     * Get all collections for this type.
     */
    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }
}
