<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Collection extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'authors',
        'author_ids',
        'isbn',
        'issn',
        'publisher_id',
        'year',
        'edition',
        'pages',
        'language',
        'classification_id',
        'collection_type_id',
        'gmd_id',
        'call_number',
        'abstract',
        'description',
        'cover_image',
        'thumbnail',
        'subjects',
        'total_items',
        'available_items',
        'borrowed_items',
        'price',
        'doi',
        'url',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'authors' => 'array',
        'author_ids' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the publisher for the collection.
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    /**
     * Get the classification for the collection.
     */
    public function classification(): BelongsTo
    {
        return $this->belongsTo(Classification::class);
    }

    /**
     * Get the collection type for the collection.
     */
    public function collectionType(): BelongsTo
    {
        return $this->belongsTo(CollectionType::class);
    }

    /**
     * Get the GMD for the collection.
     */
    public function gmd(): BelongsTo
    {
        return $this->belongsTo(Gmd::class);
    }

    /**
     * Get all items for the collection.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CollectionItem::class);
    }

    /**
     * Get all available items for the collection.
     */
    public function availableItems(): HasMany
    {
        return $this->hasMany(CollectionItem::class)->where('status', 'available');
    }

    /**
     * Get all loans for the collection through items.
     */
    public function loans(): HasManyThrough
    {
        return $this->hasManyThrough(
            Loan::class,
            CollectionItem::class,
            'collection_id', // Foreign key on collection_items table
            'item_id',       // Foreign key on loans table
            'id',            // Local key on collections table
            'id'             // Local key on collection_items table
        );
    }

    /**
     * Get all reservations for the collection through items.
     */
    public function reservations(): HasManyThrough
    {
        return $this->hasManyThrough(
            Reservation::class,
            CollectionItem::class,
            'collection_id', // Foreign key on collection_items table
            'item_id',       // Foreign key on reservations table
            'id',            // Local key on collections table
            'id'             // Local key on collection_items table
        );
    }

    /**
     * Get all subjects for the collection.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'collection_subjects');
    }

    /**
     * Get all digital files for the collection.
     */
    public function digitalFiles()
    {
        return $this->hasMany(DigitalFile::class);
    }

    /**
     * Get the subjects attribute - prioritize relationship over JSON column.
     */
    public function getSubjectsAttribute($value)
    {
        // If relationship is loaded, return that
        if ($this->relationLoaded('subjects')) {
            return $this->getRelationValue('subjects');
        }
        // Otherwise return the raw JSON string
        return $value;
    }

    /**
     * Check if collection has available items.
     */
    public function hasAvailableItems(): bool
    {
        return $this->available_items > 0;
    }

    /**
     * Scope to search by title or authors.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('authors', 'like', "%{$search}%")
                ->orWhere('isbn', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to filter by availability.
     */
    public function scopeAvailable($query)
    {
        return $query->where('available_items', '>', 0);
    }

    /**
     * Scope to filter by collection type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('collection_type_id', $type);
    }

    /**
     * Get the cover image URL.
     */
    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_image) {
            if (str_starts_with($this->cover_image, 'http')) {
                return $this->cover_image;
            }
            return Storage::url($this->cover_image);
        }

        // Generate placeholder cover based on title
        return 'https://via.placeholder.com/300x400/6366f1/ffffff?text=' . urlencode($this->title);
    }

    /**
     * Get the thumbnail URL.
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            if (str_starts_with($this->thumbnail, 'http')) {
                return $this->thumbnail;
            }
            return Storage::url($this->thumbnail);
        }

        return $this->cover_url;
    }

    /**
     * Check if collection has a cover image.
     */
    public function hasCover(): bool
    {
        return !empty($this->cover_image) && !str_starts_with($this->cover_image, 'http');
    }

    /**
     * Upload cover image.
     */
    public function uploadCover($file): string
    {
        $path = $file->store('covers', 'public');
        $this->update(['cover_image' => $path]);

        // Generate thumbnail
        $this->generateThumbnail($file);

        return $path;
    }

    /**
     * Generate thumbnail from cover image.
     */
    protected function generateThumbnail($file): void
    {
        // For now, we'll use the same file
        // In production, you'd use image intervention to resize
        $path = $file->store('thumbnails', 'public');
        $this->update(['thumbnail' => $path]);
    }

    /**
     * Delete cover image.
     */
    public function deleteCover(): void
    {
        if ($this->cover_image && Storage::exists($this->cover_image)) {
            Storage::delete($this->cover_image);
        }
        if ($this->thumbnail && Storage::exists($this->thumbnail)) {
            Storage::delete($this->thumbnail);
        }

        $this->update([
            'cover_image' => null,
            'thumbnail' => null,
        ]);
    }
}
