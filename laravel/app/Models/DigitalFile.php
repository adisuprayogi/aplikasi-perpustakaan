<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'collection_id',
        'title',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'mime_type',
        'access_level',
        'download_count',
        'view_count',
        'uploaded_by',
        'published_at',
        'description',
        'version',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'file_size' => 'integer',
        'download_count' => 'integer',
        'view_count' => 'integer',
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the collection that owns the digital file.
     */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    /**
     * Get the user who uploaded the digital file.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Increment download count.
     */
    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }

    /**
     * Increment view count.
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Get file size in human readable format.
     */
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if file is accessible by user.
     */
    public function isAccessibleBy(?User $user): bool
    {
        return match ($this->access_level) {
            'public' => true,
            'registered' => $user !== null,
            'campus_only' => $user !== null && $user->member !== null,
            default => false,
        };
    }

    /**
     * Scope to filter by active files.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by access level.
     */
    public function scopeWithAccessLevel($query, string $level)
    {
        return $query->where('access_level', $level);
    }

    /**
     * Scope to filter by collection.
     */
    public function scopeForCollection($query, int $collectionId)
    {
        return $query->where('collection_id', $collectionId);
    }

    /**
     * Scope to search by title or description.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to get published files.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Get the file extension.
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * Check if file is a PDF.
     */
    public function isPdf(): bool
    {
        return strtolower($this->extension) === 'pdf' || str_starts_with($this->mime_type ?? '', 'application/pdf');
    }

    /**
     * Check if file is an image.
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    /**
     * Get the full file path.
     */
    public function getFullPathAttribute(): string
    {
        return storage_path('app/' . $this->file_path);
    }

    /**
     * Check if file exists.
     */
    public function fileExists(): bool
    {
        return file_exists($this->full_path);
    }
}
