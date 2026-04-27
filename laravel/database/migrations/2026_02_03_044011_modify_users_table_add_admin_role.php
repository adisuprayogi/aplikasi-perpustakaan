<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'admin' to the role ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'branch_admin', 'circulation_staff', 'catalog_staff', 'report_viewer', 'member') DEFAULT 'member'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'admin' from the role ENUM
        // First, update any users with 'admin' role to 'super_admin'
        DB::statement("UPDATE users SET role = 'super_admin' WHERE role = 'admin'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'branch_admin', 'circulation_staff', 'catalog_staff', 'report_viewer', 'member') DEFAULT 'member'");
    }
};
