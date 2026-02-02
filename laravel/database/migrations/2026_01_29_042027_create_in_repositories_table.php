<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('in_repositories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();

            // Document Info
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('abstract')->nullable();
            $table->year('year');
            $table->string('language', 10)->default('id');

            // Author Info
            $table->string('author_name');
            $table->string('author_nim')->nullable();
            $table->string('author_email')->nullable();
            $table->string('advisor_name')->nullable();
            $table->string('co_advisor_name')->nullable();

            // Document Type & Category
            $table->enum('document_type', ['undergraduate_thesis', 'masters_thesis', 'doctoral_dissertation', 'research_paper', 'journal_article', 'conference_paper', 'book_chapter', 'technical_report', 'other'])->default('undergraduate_thesis');
            $table->string('department')->nullable();
            $table->string('faculty')->nullable();
            $table->string('program_study')->nullable();

            // Subject & Classification
            $table->foreignId('classification_id')->nullable()->constrained()->nullOnDelete();
            $table->json('subjects')->nullable();

            // File
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type', 50);
            $table->unsignedBigInteger('file_size');

            // DOI
            $table->string('doi')->nullable()->unique();
            $table->string('doi_status')->default('pending'); // pending, assigned, failed

            // Submission Workflow
            $table->enum('status', ['pending_moderation', 'approved', 'rejected', 'published', 'archived'])->default('pending_moderation');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // Access Control
            $table->enum('access_level', ['public', 'registered', 'campus_only', 'restricted'])->default('campus_only');
            $table->boolean('is_downloadable')->default(true);
            $table->unsignedInteger('download_count')->default(0);
            $table->unsignedInteger('view_count')->default(0);

            // Metadata
            $table->string('keywords')->nullable();
            $table->string('citation')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status', 'published_at']);
            $table->index(['document_type', 'year']);
            $table->index(['access_level', 'status']);
            $table->index('doi_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('in_repositories');
    }
};
