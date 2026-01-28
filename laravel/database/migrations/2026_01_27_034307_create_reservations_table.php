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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('collection_items')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->comment('Branch tempat booking/ambil');
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('reservation_date');
            $table->timestamp('ready_date')->nullable()->comment('Tanggal buku siap diambil');
            $table->timestamp('expiry_date')->comment('Batas waktu pengambilan');
            $table->enum('status', ['pending', 'ready', 'fulfilled', 'cancelled', 'expired'])->default('pending');
            $table->integer('queue_position')->default(1)->comment('Urutan antrian');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('member_id');
            $table->index('item_id');
            $table->index('branch_id');
            $table->index('status');
            $table->index('expiry_date');
            $table->index(['item_id', 'status']);
            $table->index(['member_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
