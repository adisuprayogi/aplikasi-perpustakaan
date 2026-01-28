# Database Schema Detail
## Aplikasi Perpustakaan Kampus - Laravel Migrations

**Version:** 1.0
**Date:** 2026-01-27
**Database:** MySQL 8.0+
**Architecture:** Monolit

---

## 1. Entity Relationship Diagram (ERD)

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   users     │     │  branches   │     │collections  │
│─────────────│     │─────────────│     │─────────────│
│ id          │────<│ id          │     │ id          │
│ email       │     │ code        │     │ title       │
│ password    │     │ name        │     │ authors     │
│ role        │     │ type        │     │ isbn        │
│ branch_id   │─────│ is_active   │     │ publisher   │
└─────────────┘     └─────────────┘     │ year        │
                                        │ cover_image │
         │                               └──────┬──────┘
         │                                      │
         │                              ┌───────▼───────┐
         │                              │collection_    │
         │                              │   items       │
         │                              │───────────────│
         │                              │ id            │
         │                              │ collection_id │
         │                              │ barcode       │
         │                              │ branch_id     │───┐
         │                              │ status        │   │
         │                              └───────────────┘   │
         │                                      │           │
         │      ┌───────────────┐              │           │
         │      │   members     │              │           │
         │      │───────────────│              │           │
         └─────<│ id            │              │           │
                │ user_id       │              │           │
                │ member_no     │              │           │
                │ type          │              │           │
                │ branch_id     │───┐          │           │
                │ status        │   │          │           │
                └───────────────┘   │          │           │
                                    │          │           │
         ┌───────────────┐          │          │           │
         │    loans      │          │          │           │
         │───────────────│          │          │           │
         │ id            │          │          │           │
         │ member_id     │──────────┘          │           │
         │ item_id       │─────────────────────┘           │
         │ loan_branch_id│──────────────────────────────────┘
         │ return_branch_id│                          │
         │ due_date      │                          │
         │ return_date   │                          │
         │ fine          │                          │
         └───────────────┘                          │
                                                     │
         ┌───────────────┐                          │
         │ reservations  │                          │
         │───────────────│                          │
         │ id            │                          │
         │ member_id     │──────────┐               │
         │ item_id       │──────────┴───────────────┘
         │ branch_id     │───┐
         └───────────────┘   │
                             │
         ┌───────────────┐   │
         │item_transfers │   │
         │───────────────│   │
         │ id            │   │
         │ item_id       │───┘
         │ from_branch_id│───┐
         │ to_branch_id  │───┐
         │ status        │   │
         └───────────────┘   │
                             │
         ┌───────────────┐   │
         │   payments    │   │
         │───────────────│   │
         │ id            │   │
         │ member_id     │───┘
         │ loan_id       │───┐
         │ amount        │   │
         │ branch_id     │───┘
         └───────────────┘
```

---

## 2. Migrations

### Migration Order
```
1. 2024_01_01_000001_create_branches_table.php
2. 2024_01_01_000002_create_users_table.php
3. 2024_01_01_000003_create_members_table.php
4. 2024_01_01_000004_create_collections_table.php
5. 2024_01_01_000005_create_collection_items_table.php
6. 2024_01_01_000006_create_loans_table.php
7. 2024_01_01_000007_create_reservations_table.php
8. 2024_01_01_000008_create_item_transfers_table.php
9. 2024_01_01_000009_create_payments_table.php
10. 2024_01_01_000010_create_digital_files_table.php
11. 2024_01_01_000011_create_in_repository_table.php
12. 2024_01_01_000012_create_classifications_table.php
13. 2024_01_01_000013_create_subjects_table.php
14. 2024_01_01_000014_create_collection_subjects_table.php
15. 2024_01_01_000015_create_publishers_table.php
16. 2024_01_01_000016_create_authors_table.php
17. 2024_01_01_000017_create_gmds_table.php
18. 2024_01_01_000018_create_collection_types_table.php
19. 2024_01_01_000019_create_settings_table.php
20. 2024_01_01_000020_create_activity_logs_table.php
21. 2024_01_01_000021_create_holidays_table.php
22. 2024_01_01_000022_create_fines_table.php
```

---

### 2.1 Branches

```php
// database/migrations/2024_01_01_000001_create_branches_table.php

Schema::create('branches', function (Blueprint $table) {
    $table->id();
    $table->string('code', 20)->unique()->comment('Kode branch (PUSAT, FKIP, FT, dll)');
    $table->string('name')->comment('Nama branch/perpustakaan');
    $table->enum('type', ['central', 'faculty', 'study_program'])->default('faculty');
    $table->string('address')->nullable();
    $table->string('phone', 20)->nullable();
    $table->string('email')->nullable();
    $table->string('logo')->nullable()->comment('Path logo branch');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes();

    // Indexes
    $table->index('type');
    $table->index('is_active');
});
```

### 2.2 Users

```php
// database/migrations/2024_01_01_000002_create_users_table.php

Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
    $table->enum('role', ['super_admin', 'branch_admin', 'circulation_staff', 'catalog_staff', 'report_viewer', 'member'])->default('member');
    $table->string('phone', 20)->nullable();
    $table->text('avatar')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamp('last_login_at')->nullable();
    $table->string('last_login_ip', 45)->nullable();
    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes();

    // Indexes
    $table->index('role');
    $table->index('branch_id');
    $table->index('is_active');
    $table->index('email');
});
```

### 2.3 Members

```php
// database/migrations/2024_01_01_000003_create_members_table.php

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
```

### 2.4 Collections

```php
// database/migrations/2024_01_01_000004_create_collections_table.php

Schema::create('collections', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->json('authors')->nullable()->comment('Array of authors');
    $table->json('author_ids')->nullable()->comment('Array of author IDs from authors table');
    $table->string('isbn', 20)->nullable()->unique();
    $table->string('issn', 20)->nullable();
    $table->foreignId('publisher_id')->nullable()->constrained('publishers')->nullOnDelete();
    $table->year('year')->nullable();
    $table->string('edition')->nullable();
    $table->integer('pages')->nullable();
    $table->string('language', 10)->default('id');
    $table->foreignId('classification_id')->nullable()->constrained()->nullOnDelete()->comment('DDC/LCC');
    $table->foreignId('collection_type_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('gmd_id')->nullable()->constrained()->nullOnDelete();
    $table->string('call_number')->nullable()->comment('Classification + Author suffix');
    $table->text('abstract')->nullable();
    $table->text('description')->nullable();
    $table->string('cover_image')->nullable();
    $table->string('thumbnail')->nullable();
    $table->json('subjects')->nullable()->comment('Array of subject names');
    $table->integer('total_items')->default(0);
    $table->integer('available_items')->default(0);
    $table->integer('borrowed_items')->default(0);
    $table->decimal('price', 10, 2)->nullable()->comment('Harga buku untuk perhitungan denda hilang');
    $table->string('doi')->nullable()->comment('DOI untuk jurnal/artikel');
    $table->string('url')->nullable()->comment('URL untuk resource online');
    $table->json('metadata')->nullable()->comment('Additional metadata (MARC21, MODS, etc)');
    $table->timestamps();
    $table->softDeletes();

    // Indexes
    $table->index('title');
    $table->index('isbn');
    $table->index('year');
    $table->index('collection_type_id');
    $table->index('classification_id');
    $table->fullText(['title', 'abstract', 'description'], 'collections_fulltext');
});
```

### 2.5 Collection Items

```php
// database/migrations/2024_01_01_000005_create_collection_items_table.php

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
```

### 2.6 Loans

```php
// database/migrations/2024_01_01_000006_create_loans_table.php

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
```

### 2.7 Reservations

```php
// database/migrations/2024_01_01_000007_create_reservations_table.php

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
```

### 2.8 Item Transfers

```php
// database/migrations/2024_01_01_000008_create_item_transfers_table.php

Schema::create('item_transfers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('item_id')->constrained('collection_items')->cascadeOnDelete();
    $table->foreignId('from_branch_id')->constrained('branches');
    $table->foreignId('to_branch_id')->constrained('branches');
    $table->foreignId('requested_by')->constrained('users');
    $table->foreignId('shipped_by')->nullable()->constrained('users');
    $table->foreignId('received_by')->nullable()->constrained('users');
    $table->timestamp('requested_at');
    $table->timestamp('shipped_at')->nullable();
    $table->timestamp('received_at')->nullable();
    $table->enum('status', ['pending', 'shipped', 'received', 'cancelled'])->default('pending');
    $table->text('notes')->nullable();
    $table->json('metadata')->nullable();
    $table->timestamps();

    // Indexes
    $table->index('item_id');
    $table->index('from_branch_id');
    $table->index('to_branch_id');
    $table->index('status');
    $table->index(['status', 'requested_at']);
});
```

### 2.9 Payments

```php
// database/migrations/2024_01_01_000009_create_payments_table.php

Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->string('payment_no', 50)->unique()->comment('Nomor pembayaran');
    $table->foreignId('member_id')->constrained()->cascadeOnDelete();
    $table->foreignId('loan_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('branch_id')->constrained()->comment('Branch tempat pembayaran');
    $table->foreignId('received_by')->constrained('users');
    $table->decimal('amount', 10, 2);
    $table->enum('payment_method', ['cash', 'transfer', 'edd'])->default('cash');
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
```

### 2.10 Digital Files

```php
// database/migrations/2024_01_01_000010_create_digital_files_table.php

Schema::create('digital_files', function (Blueprint $table) {
    $table->id();
    $table->foreignId('collection_id')->nullable()->constrained()->nullOnDelete();
    $table->string('title');
    $table->string('file_path')->comment('Path file di storage');
    $table->string('file_name')->comment('Nama asli file');
    $table->string('file_type', 50)->comment('MIME type');
    $table->bigInteger('file_size')->comment('Ukuran dalam bytes');
    $table->enum('access_level', ['public', 'registered', 'campus_only'])->default('registered');
    $table->integer('download_count')->default(0);
    $table->integer('view_count')->default(0);
    $table->text('description')->nullable();
    $table->timestamps();
    $table->softDeletes();

    // Indexes
    $table->index('collection_id');
    $table->index('access_level');
});
```

### 2.11 Institutional Repository

```php
// database/migrations/2024_01_01_000011_create_in_repository_table.php

Schema::create('in_repository', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
    $table->string('author_name')->comment('Nama penulis (jika bukan user sistem)');
    $table->enum('type', ['thesis', 'dissertation', 'research_report', 'journal_article'])->default('thesis');
    $table->string('institution')->nullable();
    $table->string('department')->nullable()->comment('Fakultas/Prodi');
    $table->year('year');
    $table->string('advisor')->nullable()->comment('Pembimbing');
    $table->text('abstract')->nullable();
    $table->string('keywords')->nullable();
    $table->string('file_path');
    $table->string('file_type', 50);
    $table->bigInteger('file_size');
    $table->string('doi')->nullable();
    $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('submitted');
    $table->foreignId('approved_by')->nullable()->constrained('users');
    $table->timestamp('approved_at')->nullable();
    $table->text('rejection_reason')->nullable();
    $table->integer('download_count')->default(0);
    $table->integer('view_count')->default(0);
    $table->json('metadata')->nullable();
    $table->timestamps();
    $table->softDeletes();

    // Indexes
    $table->index('type');
    $table->index('status');
    $table->index('year');
    $table->index('department');
});
```

### 2.12 Classifications

```php
// database/migrations/2024_01_01_000012_create_classifications_table.php

Schema::create('classifications', function (Blueprint $table) {
    $table->id();
    $table->string('code', 50)->unique()->comment('Kode DDC/LCC');
    $table->string('name')->comment('Nama klasifikasi');
    $table->enum('type', ['ddc', 'lcc', 'local'])->default('ddc');
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    // Indexes
    $table->index('code');
    $table->index('type');
});
```

### 2.13 Subjects

```php
// database/migrations/2024_01_01_000013_create_subjects_table.php

Schema::create('subjects', function (Blueprint $table) {
    $table->id();
    $table->string('code', 50)->unique()->comment('Kode subjek');
    $table->string('name')->comment('Nama subjek/topik');
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    // Indexes
    $table->index('code');
    $table->index('name');
});
```

### 2.14 Collection Subjects (Pivot)

```php
// database/migrations/2024_01_01_000014_create_collection_subjects_table.php

Schema::create('collection_subjects', function (Blueprint $table) {
    $table->id();
    $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
    $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
    $table->timestamps();

    // Indexes
    $table->unique(['collection_id', 'subject_id']);
    $table->index('collection_id');
    $table->index('subject_id');
});
```

### 2.15 Publishers

```php
// database/migrations/2024_01_01_000015_create_publishers_table.php

Schema::create('publishers', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('city')->nullable();
    $table->string('country')->nullable();
    $table->string('website')->nullable();
    $table->text('address')->nullable();
    $table->timestamps();
    $table->softDeletes();

    // Indexes
    $table->index('name');
});
```

### 2.16 Authors

```php
// database/migrations/2024_01_01_000016_create_authors_table.php

Schema::create('authors', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->enum('type', ['personal', 'corporate', 'conference'])->default('personal');
    $table->string('birth_year')->nullable()->comment('Tahun lahir (untuk personal)');
    $table->string('death_year')->nullable()->comment('Tahun meninggal (untuk personal)');
    $table->text('biography')->nullable();
    $table->string('website')->nullable();
    $table->json('metadata')->nullable();
    $table->timestamps();
    $table->softDeletes();

    // Indexes
    $table->index('name');
    $table->index('type');
});
```

### 2.17 GMD (General Material Designation)

```php
// database/migrations/2024_01_01_000017_create_gmds_table.php

Schema::create('gmds', function (Blueprint $table) {
    $table->id();
    $table->string('code', 20)->unique();
    $table->string('name')->comment('Text, Sound, Video, Map, dll');
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    // Indexes
    $table->index('code');
});
```

### 2.18 Collection Types

```php
// database/migrations/2024_01_01_000018_create_collection_types_table.php

Schema::create('collection_types', function (Blueprint $table) {
    $table->id();
    $table->string('code', 20)->unique();
    $table->string('name')->comment('Book, Journal, Thesis, Reference, DVD, dll');
    $table->integer('loan_period')->nullable()->comment('Masa pinjam default (hari), null=tidak bisa dipinjam');
    $table->boolean('is_reference')->default(false)->comment('Jika true, tidak bisa dipinjam');
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    // Indexes
    $table->index('code');
});
```

### 2.19 Settings

```php
// database/migrations/2024_01_01_000019_create_settings_table.php

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
```

### 2.20 Activity Logs

```php
// database/migrations/2024_01_01_000020_create_activity_logs_table.php

Schema::create('activity_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->string('action', 50)->comment('login, logout, create, update, delete, loan, return, dll');
    $table->string('entity_type', 100)->nullable()->comment('Model yang di-aksi (App\\Models\\Loan)');
    $table->unsignedBigInteger('entity_id')->nullable();
    $table->text('description')->nullable();
    $table->json('old_values')->nullable()->comment('Nilai sebelum perubahan');
    $table->json('new_values')->nullable()->comment('Nilai setelah perubahan');
    $table->string('ip_address', 45)->nullable();
    $table->string('user_agent')->nullable();
    $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
    $table->timestamp('created_at');

    // Indexes
    $table->index('user_id');
    $table->index('action');
    $table->index(['entity_type', 'entity_id']);
    $table->index('created_at');
    $table->index(['user_id', 'created_at']);
});
```

### 2.21 Holidays

```php
// database/migrations/2024_01_01_000021_create_holidays_table.php

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
```

### 2.22 Fines

```php
// database/migrations/2024_01_01_000022_create_fines_table.php

Schema::create('fines', function (Blueprint $table) {
    $table->id();
    $table->foreignId('member_id')->constrained()->cascadeOnDelete();
    $table->foreignId('loan_id')->nullable()->constrained()->nullOnDelete();
    $table->decimal('amount', 10, 2);
    $table->decimal('paid_amount', 10, 2)->default(0);
    $table->enum('type', ['overdue', 'lost', 'damaged'])->default('overdue');
    $table->enum('status', ['pending', 'partial', 'paid'])->default('pending');
    $table->date('fine_date');
    $table->date('paid_date')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();

    // Indexes
    $table->index('member_id');
    $table->index('loan_id');
    $table->index('status');
    $table->index('fine_date');
});
```

---

## 3. Seeders

### 3.1 BranchSeeder

```php
// database/seeders/BranchSeeder.php

Branch::create([
    'code' => 'PUSAT',
    'name' => 'Perpustakaan Pusat',
    'type' => 'central',
    'address' => 'Jl. Pendidikan No. 1',
    'phone' => '021-123456',
    'email' => 'lib@kampus.ac.id',
]);

Branch::create([
    'code' => 'FKIP',
    'name' => 'Perpustakaan FKIP',
    'type' => 'faculty',
    'address' => 'Gedung FKIP Lt. 2',
    'phone' => '021-234567',
    'email' => 'lib-fkip@kampus.ac.id',
]);

Branch::create([
    'code' => 'FT',
    'name' => 'Perpustakaan Fakultas Teknik',
    'type' => 'faculty',
    'address' => 'Gedung Teknik Lt. 1',
    'phone' => '021-345678',
    'email' => 'lib-ft@kampus.ac.id',
]);
```

### 3.2 UserSeeder

```php
// database/seeders/UserSeeder.php

User::create([
    'name' => 'Super Admin',
    'email' => 'admin@kampus.ac.id',
    'password' => bcrypt('password'),
    'role' => 'super_admin',
    'branch_id' => 1,
    'is_active' => true,
]);

User::create([
    'name' => 'Staff Sirkulasi Pusat',
    'email' => 'circulation@kampus.ac.id',
    'password' => bcrypt('password'),
    'role' => 'circulation_staff',
    'branch_id' => 1,
    'is_active' => true,
]);

User::create([
    'name' => 'Staff Katalog Pusat',
    'email' => 'catalog@kampus.ac.id',
    'password' => bcrypt('password'),
    'role' => 'catalog_staff',
    'branch_id' => 1,
    'is_active' => true,
]);
```

### 3.3 CollectionTypeSeeder

```php
// database/seeders/CollectionTypeSeeder.php

CollectionType::create([
    'code' => 'BK',
    'name' => 'Buku Teks',
    'loan_period' => 7,
    'is_reference' => false,
]);

CollectionType::create([
    'code' => 'JN',
    'name' => 'Jurnal',
    'loan_period' => null,
    'is_reference' => false,
]);

CollectionType::create([
    'code' => 'REF',
    'name' => 'Reference',
    'loan_period' => null,
    'is_reference' => true,
]);
```

---

## 4. Important Indexes

### 4.1 Full-text Search
```php
// Collections table
$table->fullText(['title', 'abstract', 'description'], 'collections_fulltext');
```

### 4.2 Compound Indexes for Performance
```php
// Loans table
$table->index(['status', 'due_date'])->comment('Untuk query overdue');
$table->index(['member_id', 'status'])->comment('Untuk query active loans member');

// Collection Items table
$table->index(['branch_id', 'status'])->comment('Untuk query available items per branch');
```

---

## 5. Foreign Key Constraints

### Cascade Operations
| Table | On Delete | On Update |
|-------|-----------|-----------|
| users.branch_id | SET NULL | CASCADE |
| members.user_id | SET NULL | CASCADE |
| collection_items.collection_id | CASCADE | CASCADE |
| collection_items.branch_id | CASCADE | CASCADE |
| loans.member_id | CASCADE | CASCADE |
| loans.item_id | RESTRICT | CASCADE |
| loans.loan_branch_id | RESTRICT | CASCADE |

---

*End of Database Schema Detail*
