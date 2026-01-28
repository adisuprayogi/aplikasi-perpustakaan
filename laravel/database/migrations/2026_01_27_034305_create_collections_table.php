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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->json('authors')->nullable()->comment('Array of authors');
            $table->json('author_ids')->nullable()->comment('Array of author IDs from authors table');
            $table->string('isbn', 20)->nullable()->unique();
            $table->string('issn', 20)->nullable();
            $table->foreignId('publisher_id')->nullable()->constrained('publishers')->nullOnDelete();
            $table->year('year')->nullable();
            $table->string('edition')->nullable();
            $table->integer('pages')->nullable();
            $table->string('language', 10)->default('id');
            $table->foreignId('classification_id')->nullable()->constrained()->nullOnDelete()->comment('DDC/LCC');
            $table->foreignId('collection_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('gmd_id')->nullable()->constrained()->nullOnDelete();
            $table->string('call_number')->nullable()->comment('Classification + Author suffix');
            $table->text('abstract')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('subjects')->nullable()->comment('Array of subject names');
            $table->integer('total_items')->default(0);
            $table->integer('available_items')->default(0);
            $table->integer('borrowed_items')->default(0);
            $table->decimal('price', 10, 2)->nullable()->comment('Harga buku untuk perhitungan denda hilang');
            $table->string('doi')->nullable()->comment('DOI untuk jurnal/artikel');
            $table->string('url')->nullable()->comment('URL untuk resource online');
            $table->json('metadata')->nullable()->comment('Additional metadata (MARC21, MODS, etc)');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('title');
            $table->index('isbn');
            $table->index('year');
            $table->index('collection_type_id');
            $table->index('classification_id');
            $table->fullText(['title', 'abstract', 'description'], 'collections_fulltext');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
