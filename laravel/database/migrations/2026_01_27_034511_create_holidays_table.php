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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->boolean('is_recurring')->default(false)->comment('Pengulang setiap tahun');
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete()->comment('Null = all branches');
            $table->text('description')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('date');
            $table->index('branch_id');
            $table->index(['date', 'branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
