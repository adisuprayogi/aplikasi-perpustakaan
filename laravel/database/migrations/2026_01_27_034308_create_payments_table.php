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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_no', 50)->unique()->comment('Nomor pembayaran');
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('loan_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->constrained()->comment('Branch tempat pembayaran');
            $table->foreignId('received_by')->constrained('users');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'transfer', 'edc'])->default('cash');
            $table->string('payment_reference')->nullable()->comment('No. referensi transfer/EDC');
            $table->enum('status', ['paid', 'refunded'])->default('paid');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('member_id');
            $table->index('loan_id');
            $table->index('branch_id');
            $table->index('status');
            $table->index('payment_no');
            $table->index(['member_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
