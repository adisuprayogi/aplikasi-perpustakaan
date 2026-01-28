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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('member_no', 50)->unique()->comment('Nomor anggota (NIM/NIP generated)');
            $table->enum('type', ['student', 'lecturer', 'staff', 'external'])->default('student');
            $table->string('id_number', 50)->nullable()->comment('NIK/NIM/NIP asli');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete()->comment('Branch utama');
            $table->string('photo')->nullable();
            $table->enum('status', ['active', 'suspended', 'expired', 'blacklisted'])->default('active');
            $table->date('valid_from')->default(now());
            $table->date('valid_until')->nullable();
            $table->decimal('total_fines', 10, 2)->default(0)->comment('Total unpaid fines');
            $table->integer('total_loans')->default(0)->comment('Total lifetime loans');
            $table->json('metadata')->nullable()->comment('Additional data (fakultas, prodi, dll)');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('member_no');
            $table->index('type');
            $table->index('status');
            $table->index('branch_id');
            $table->index('valid_until');
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
