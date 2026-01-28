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
        Schema::create('collection_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name')->comment('Buku, Jurnal, Skripsi, Referensi, DVD, dll');
            $table->string('name_en')->nullable()->comment('English name');
            $table->integer('loan_period')->nullable()->comment('Masa pinjam default (hari), null=tidak bisa dipinjam');
            $table->integer('max_renewals')->default(0)->comment('Maksimal perpanjangan');
            $table->boolean('is_reference')->default(false)->comment('Jika true, tidak bisa dipinjam');
            $table->boolean('is_loanable')->default(true)->comment('Jika true, bisa dipinjam');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_types');
    }
};
