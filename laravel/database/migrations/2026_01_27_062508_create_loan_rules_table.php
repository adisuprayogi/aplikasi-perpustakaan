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
        Schema::create('loan_rules', function (Blueprint $table) {
            $table->id();
            $table->string('member_type'); // student, lecturer, staff, external
            $table->foreignId('collection_type_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('loan_period')->default(7)->comment('Lama pinjam dalam hari');
            $table->unsignedInteger('max_loans')->default(3)->comment('Maksimal item yang bisa dipinjam');
            $table->decimal('fine_per_day', 10, 2)->default(0.00)->comment('Denda per hari');
            $table->boolean('is_renewable')->default(true)->comment('Bisa diperpanjang');
            $table->unsignedInteger('renew_limit')->default(2)->comment('Batas perpanjangan');
            $table->boolean('is_fine_by_calendar')->default(true)->comment('Hitung denda termasuk hari libur');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['member_type', 'collection_type_id']);
            $table->index('member_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_rules');
    }
};
