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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('type', 20)->default('string')->comment('string, integer, boolean, json, array');
            $table->string('group', 50)->default('general')->comment('general, loan, fine, email, dll');
            $table->string('description')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete()->comment('Null = global setting');
            $table->timestamps();

            // Indexes
            $table->index('key');
            $table->index('group');
            $table->index(['branch_id', 'group']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
