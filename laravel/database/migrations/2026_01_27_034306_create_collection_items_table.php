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
        Schema::create('collection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->string('barcode', 50)->unique()->comment('Barcode unik per item');
            $table->string('call_number', 100)->comment('Call number + copy number');
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete()->comment('Lokasi fisik item');
            $table->string('location')->nullable()->comment('Rak, lantai, ruangan');
            $table->enum('status', ['available', 'borrowed', 'reserved', 'lost', 'damaged', 'in_transfer'])->default('available');
            $table->enum('condition', ['good', 'fair', 'poor'])->default('good');
            $table->date('acquired_date')->nullable()->comment('Tanggal perolehan');
            $table->decimal('acquired_price', 10, 2)->nullable()->comment('Harga perolehan');
            $table->string('source')->nullable()->comment('Sumber (beli, hadiah, tukar)');
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('barcode');
            $table->index('collection_id');
            $table->index('branch_id');
            $table->index('status');
            $table->index(['branch_id', 'status']);
            $table->index(['collection_id', 'branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_items');
    }
};
