<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InRepository extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'member_id',
        'branch_id',
        'approved_by',
        'rejected_by',
        'title',
        'slug',
        'abstract',
        'year',
        'language',
        'author_name',
        'author_nim',
        'author_email',
        'advisor_name',
        'co_advisor_name',
        'document_type',
        'department',
        'faculty',
        'program_study',
        'classification_id',
        'subjects',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'doi',
        'doi_status',
        'status',
        'submitted_at',
        'approved_at',
        'rejected_at',
        'published_at',
        'rejection_reason',
        'access_level',
        'is_downloadable',
        'download_count',
        'view_count',
        'keywords',
        'citation',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'subjects' => 'array',
        'metadata' => 'array',
        'is_downloadable' => 'boolean',
        'download_count' => 'integer',
        'view_count' => 'integer',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'published_at' => 'datetime',
        'year' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'document_type_label',
        'status_label',
        'access_level_label',
        'file_size_human',
    ];

    /**
     * Get the member that owns the repository.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the branch that owns the repository.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who approved the repository.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who rejected the repository.
     */
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the classification for the repository.
     */
    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    /**
     * Scope a query to only include published repositories.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include pending moderation.
     */
    public function scopePendingModeration($query)
    {
        return $query->where('status', 'pending_moderation');
    }

    /**
     * Scope a query to only include approved but not published.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to filter by access level.
     */
    public function scopeAccessibleBy($query, $user = null)
    {
        if (!$user) {
            return $query->where('access_level', 'public');
        }

        if ($user->hasRole('super_admin') || $user->hasRole('branch_admin') || $user->hasRole('report_viewer')) {
            return $query;
        }

        return $query->whereIn('access_level', ['public', 'registered', 'campus_only']);
    }

    /**
     * Scope a query to filter by document type.
     */
    public function scopeByDocumentType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    /**
     * Scope a query to filter by year.
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope a query to search by title or author.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('author_name', 'like', "%{$search}%")
                ->orWhere('abstract', 'like', "%{$search}%")
                ->orWhere('keywords', 'like', "%{$search}%");
        });
    }

    /**
     * Get the document type label.
     */
    public function getDocumentTypeLabelAttribute()
    {
        return match($this->document_type) {
            'undergraduate_thesis' => 'Skripsi',
            'masters_thesis' => 'Tesis',
            'doctoral_dissertation' => 'Disertasi',
            'research_paper' => 'Research Paper',
            'journal_article' => 'Artikel Jurnal',
            'conference_paper' => 'Conference Paper',
            'book_chapter' => 'Bab Buku',
            'technical_report' => 'Laporan Teknis',
            'other' => 'Lainnya',
            default => 'Unknown',
        };
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending_moderation' => 'Menunggu Moderasi',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'published' => 'Terbit',
            'archived' => 'Arsip',
            default => 'Unknown',
        };
    }

    /**
     * Get the access level label.
     */
    public function getAccessLevelLabelAttribute()
    {
        return match($this->access_level) {
            'public' => 'Publik',
            'registered' => 'Terdaftar',
            'campus_only' => 'Kampus Saja',
            'restricted' => 'Terbatas',
            default => 'Unknown',
        };
    }

    /**
     * Get the file size in human readable format.
     */
    public function getFileSizeHumanAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < 3) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Increment the download count.
     */
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    /**
     * Increment the view count.
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Check if the repository is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check if the repository is pending moderation.
     */
    public function isPendingModeration(): bool
    {
        return $this->status === 'pending_moderation';
    }

    /**
     * Check if the repository is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the repository is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if DOI is assigned.
     */
    public function hasDoi(): bool
    {
        return !empty($this->doi) && $this->doi_status === 'assigned';
    }

    /**
     * Get citation text.
     */
    public function getCitation(): string
    {
        if ($this->citation) {
            return $this->citation;
        }

        // Generate default citation format
        $citation = "{$this->author_name} ({$this->year}). ";
        $citation .= "{$this->title}. ";

        if ($this->faculty) {
            $citation .= "{$this->faculty}";
        }

        if ($this->department) {
            $citation .= ", {$this->department}";
        }

        return $citation;
    }
}
