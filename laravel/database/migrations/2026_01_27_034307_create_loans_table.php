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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('collection_items')->restrictOnDelete();
            $table->foreignId('loan_branch_id')->constrained('branches')->comment('Branch tempat meminjam');
            $table->foreignId('return_branch_id')->nullable()->constrained('branches')->comment('Branch tempat mengembalikan');
            $table->foreignId('processed_by')->constrained('users')->comment('Staff yang memproses');
            $table->date('loan_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->integer('renewal_count')->default(0)->comment('Jumlah perpanjangan');
            $table->decimal('fine', 10, 2)->default(0)->comment('Denda keterlambatan');
            $table->decimal('paid_fine', 10, 2)->default(0)->comment('Denda yang sudah dibayar');
            $table->enum('status', ['active', 'returned', 'overdue', 'lost'])->default('active');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('member_id');
            $table->index('item_id');
            $table->index('loan_branch_id');
            $table->index('return_branch_id');
            $table->index('status');
            $table->index('due_date');
            $table->index('return_date');
            $table->index(['status', 'due_date'])->comment('Untuk query overdue');
            $table->index(['member_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
