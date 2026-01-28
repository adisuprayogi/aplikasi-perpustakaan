<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publisher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'city',
        'country',
        'website',
        'email',
    ];

    /**
     * Get all collections for this publisher.
     */
    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }
}
