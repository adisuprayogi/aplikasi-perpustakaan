# Rancangan Aplikasi Perpustakaan Kampus
## Sistem Informasi Perpustakaan Akademik Terintegrasi

---

## 1. Gambaran Umum

**Tujuan:** Membangun sistem informasi perpustakaan kampus yang terintegrasi untuk mengelola koleksi akademik (buku, jurnal, skripsi, tesis, desertasi), manajemen sirkulasi, dan layanan digital.

**Target Pengguna:** > 5000 pengguna (mahasiswa, dosen, staf)

**Platform:** Web Application

---

## 2. Arsitektur Sistem

### 2.1 Arsitektur Tingkat Tinggi (Laravel Stack)

```
┌─────────────────────────────────────────────────────────────┐
│                      Client Layer                           │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │
│  │ Mahasiswa│  │  Dosen   │  │  Staf    │  │  Admin   │   │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘   │
└─────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                   Application Layer (Laravel)               │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Blade Templates + Tailwind + Alpine.js              │  │
│  │  - Livewire (untuk interaktivitas dynamic)           │  │
│  │  - Inertia.js (opsional untuk SPA experience)        │  │
│  └──────────────────────────────────────────────────────┘  │
│                           │                                 │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Laravel Backend (MVC Architecture)                   │  │
│  │  - Controllers (Business Logic)                       │  │
│  │  - Models (Eloquent ORM)                             │  │
│  │  - Views (Blade Templates)                           │  │
│  │  - Middleware (Auth, Rate Limiting, CORS)            │  │
│  │  - Services (Domain Logic)                           │  │
│  │  - Repositories (Data Access Layer)                  │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                      Data Layer                             │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │
│  │ Database │  │ File     │  │  Search  │  │  Cache   │   │
│  │ (MySQL 8) │  Storage  │  │ Engine   │  │ (Redis)  │   │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘   │
└─────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                   External Services                         │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │
│  │ Queue    │  │  Email   │  │  OAI-PMH │  │  Import  │   │
│  │ (Redis)  │  │ (SMTP)   │  │  Server  │  │ (SLiMS)  │   │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘   │
└─────────────────────────────────────────────────────────────┘
```

### 2.2 Teknologi yang Dipilih

#### Frontend
| Komponen | Teknologi | Alasan |
|----------|-----------|--------|
| Framework | Laravel Blade | Server-side rendering, simple, familiar di Indonesia |
| Styling | Tailwind CSS | Utility-first, responsive, modern |
| Interactivity | Alpine.js | Lightweight, mudah dipelajari, cocok dengan Blade |
| Real-time | Livewire | Dynamic tanpa perlu API terpisah, Laravel native |
| Icons | Heroicons / Phosphor Icons | SVG icons, Tailwind compatible |
| Form Validation | Laravel Form Request + Vite | Server & client validation |

#### Backend
| Komponen | Teknologi | Alasan |
|----------|-----------|--------|
| Framework | Laravel 11+ | Ekosistem mature, dokumentasi lengkap, komunitas besar |
| Language | PHP 8.2+ | Performa tinggi, compatible dengan banyak hosting |
| ORM | Eloquent | Laravel native, mudah digunakan |
| Authentication | Laravel Sanctum / Breeze | API tokens, SPA auth, session-based |
| API | Laravel API Resources | Transform data untuk JSON response |
| Documentation |scribe/knuckleswtf/scribe | Auto-generate API docs dari Laravel |

#### Infrastructure
| Komponen | Teknologi | Alasan |
|----------|-----------|--------|
| Database | MySQL 8.0+ | Mature, reliable, standar industri, compatible dengan SLiMS |
| Search Engine | Meilisearch | Fast full-text search, easy integration |
| Cache | Redis | Session, rate limiting, queue |
| File Storage | Local Storage / S3-compatible | Digital files storage |
| Queue | Redis + Laravel Queues | Background jobs, email, notifications |
| Web Server | Nginx + PHP-FPM | High performance |
| Deployment | Forge / Vapor / VPS | Easy deployment |

#### Laravel Packages yang Akan Digunakan
| Package | Fungsi |
|---------|--------|
| Laravel Breeze | Authentication scaffold |
| Spatie Permission | Role-based access control |
| Laravel Livewire | Real-time UI tanpa API |
| Meilisearch PHP | Search integration |
| Laravel Excel | Import/Export data |
| Laravel Debugbar | Debugging (development) |
| Laravel Telescope | Debugging (production) |
| Spatie Activitylog | Audit trail |
| Laravel Sluggable | URL friendly slugs |
| Intervention Image | Image processing |
| Laravel Backup | Backup automation |

---

## 3. Modul dan Fitur Utama

### 3.1 Modul Manajemen Koleksi (Catalog Management)

#### 3.1.1 Katalog Buku
- **Data Buku:**
  - ISBN, Judul, Penulis, Penerbit
  - Tahun terbit, Edisi, Jumlah halaman
  - Klasifikasi (DDC/LCC), Subjek
  - Bahasa, Ringkasan/Abstrak
  - Cover image, Tags/Keywords

#### 3.1.2 Karya Ilmiah
- **Skripsi/Tesis/Disertasi:**
  - Penulis (mahasiswa), Pembimbing
  - Program studi, Fakultas
  - Tahun, Judul, Abstrak
  - File PDF (full text terbatas)

#### 3.1.3 Jurnal & Artikel
- **Jurnal:**
  - Nama jurnal, ISSN, Volume, Issue
  - DOI, Publisher, Scope
  - Indexing (DOAJ, SINTA, dll)

#### 3.1.4 Fitur Katalog
- ✅ CRUD koleksi
- ✅ Import/Export (MARC21, CSV)
- ✅ Barcode/QR Code generation
- ✅ Multiple copy management
- ✅ Cover image upload
- ✅ Digital file attachment

---

### 3.2 Modul Sirkulasi (Circulation)

#### 3.2.1 Peminjaman
- **Proses:**
  1. Scan kartu anggota → Validasi keanggotaan
  2. Scan barcode buku → Cek ketersediaan
  3. Input tanggal pinjam → Auto hitung tanggal kembali
  4. Konfirmasi → Cetak struk

- **Aturan:**
  - Batas maksimal pinjam per tipe anggota
  - Masa pinjam (mahasiswa: 7 hari, dosen: 14 hari)
  - Perpanjangan (max 2x jika tidak ada booking)
  - Buku reference tidak bisa dipinjam

#### 3.2.2 Pengembalian
- **Proses:**
  1. Scan barcode buku → Cek detail peminjaman
  2. Cek keterlambatan → Auto hitung denda
  3. Kondisi buku (rusak/hilang)
  4. Konfirmasi → Update status

#### 3.2.3 Denda & Sanksi
- **Perhitungan Denda:**
  - Terlambat: Rp 1.000/hari
  - Hilang: Harga buku + 20%
  - Rusak: Sesuai kerusakan

- **Sanksi:**
  - Block peminjaman jika denda > Rp 50.000
  - Blacklist untuk pelanggaran berat

#### 3.2.4 Reservasi
- Booking buku yang sedang dipinjam
- Notifikasi saat buku tersedia
- Batas waktu ambil (3 hari)

---

### 3.3 Modul Keanggotaan (Membership)

#### 3.3.1 Tipe Anggota
| Tipe | Masa Pinjam | Max Pinjam | Denda/Hari |
|------|-------------|------------|------------|
| Mahasiswa | 7 hari | 3 buku | Rp 1.000 |
| Dosen | 14 hari | 5 buku | Rp 1.000 |
| Staf | 7 hari | 3 buku | Rp 1.000 |
| Peminjaman Luar | 3 hari | 2 buku | Rp 2.000 |

#### 3.3.2 Data Anggota
- **Data Pribadi:**
  - NIK, Nama, Email, No. HP
  - Alamat, Fakultas/Prodi
  - Foto, Tanda tangan digital

- **Kartu Anggota:**
  - Nomor anggota (NIM/NIP untuk internal)
  - QR Code untuk scan
  - Masa berlaku

#### 3.3.3 Fitur Keanggotaan
- ✅ Registrasi & verifikasi
- ✅ Renewal keanggotaan
- ✅ Suspended/Banned
- ✅ Export kartu anggota (PDF)

---

### 3.4 Modul Digital Library

#### 3.4.1 E-Book & E-Journal
- Upload dan manage file digital
- Pembacaan online (PDF viewer)
- Download terbatas (drmr/watermark)
- Integrasi dengan repository

#### 3.4.2 Institutional Repository
- Koleksi karya ilmiah kampus
- Upload oleh mahasiswa/dosen
- Moderasi oleh admin
- DOI assignment

#### 3.4.3 Fitur Digital
- ✅ Full-text search
- ✅ Preview sebelum download
- ✅ Access control (IP based)
- ✅ Usage statistics

---

### 3.5 Modul Laporan & Statistik

#### 3.5.1 Laporan Sirkulasi
- Laporan peminjaman harian/bulanan/tahunan
- Laporan pengembalian
- Laporan denda terbayar
- Buku terpopuler
- Anggota paling aktif

#### 3.5.2 Laporan Koleksi
- Statistik koleksi per kategori
- Koleksi yang sering dipinjam
- Koleksi yang tidak pernah dipinjam
- Growth report (penambahan koleksi)

#### 3.5.3 Dashboard
- Chart statistik real-time
- Buku yang dipinjam hari ini
- Buku terlambat hari ini
- Jumlah anggota aktif
- Alert: buku hampir jatuh tempo

---

### 3.6 Modul Pengaturan (Settings)

#### 3.6.1 Sistem
- Konfigurasi perpustakaan (nama, alamat, logo)
- Jam operasional
- Aturan sirkulasi (max pinjam, lama pinjam, denda)
- Hari libur

#### 3.6.2 Hak Akses (Role-Based Access Control)
| Role | Hak Akses |
|------|-----------|
| Super Admin | Semua akses |
| Admin Sirkulasi | Sirkulasi, anggota |
| Admin Koleksi | Katalog, klasifikasi |
| Admin Laporan | Laporan, statistik |
| User | View, pinjam, reservasi |

---

### 3.7 Modul Multi-Branch (Perpustakaan Fakultas)

#### 3.7.1 Konsep Multi-Branch
Sistem ini mendukung struktur perpustakaan kampus dengan multiple cabang:
- **Perpustakaan Pusat (Central Library)**
- **Perpustakaan Fakultas** (FKIP, FT, FH, dll)
- **Perpustakaan Prodi/Jurusan** (Opsional)

#### 3.7.2 Struktur Organisasi
```
Perpustakaan Kampus
├── Perpustakaan Pusat (Central)
│   ├── Koleksi Umum
│   ├── Koleksi Reference
│   ├── Koleksi Digital
│   └── Ruang Baca
├── Perpustakaan Fakultas (FKIP)
│   ├── Koleksi Pendidikan
│   ├── Koleksi Pengajaran
│   └── Ruang Baca FKIP
├── Perpustakaan Fakultas (Teknik)
│   ├── Koleksi Teknik Sipil
│   ├── Koleksi Teknik Elektro
│   └── Ruang Baca Teknik
└── Perpustakaan Fakultas (Hukum)
    ├── Koleksi Hukum
    ├── Koleksi Legislasi
    └── Ruang Baca Hukum
```

#### 3.7.3 Fitur Multi-Branch
| Fitur | Deskripsi |
|-------|-----------|
| **Branch Management** | CRUD data cabang perpustakaan |
| **Transfer Koleksi** | Pindah buku antar cabang |
| **Stock Opname per Branch** | Stok opname per cabang independently |
| **Laporan per Branch** | Laporan terpisah per cabang |
| **Peminjaman Antar Cabang** | Anggota bisa pinjam dari cabang lain |
| **Pengembalian Antar Cabang** | Bisa kembali di cabang berbeda |
| **Centralized Catalog** | Katalog terpusat, filterable by branch |
| **Branch Administrator** | Admin lokal untuk setiap cabang |

#### 3.7.4 Hak Akses per Branch
| Role | Scope |
|------|-------|
| Super Admin | Semua branch |
| Branch Admin | Hanya branchnya sendiri |
| Staff | Hanya branchnya sendiri |
| User | Semua branch (untuk searching) |

#### 3.7.5 Alur Peminjaman Antar Cabang
```
Mahasiswa → Pinjam di FKIP → Kembali di Pusat
     │            │              │
     ▼            ▼              ▼
  Scan ID    Scan Buku     Scan Buku
  Validasi   Cek Stok      Update Status
             (FKIP)        (FKIP -> Pusat)
                           Create Transfer Record
```

#### 3.7.6 Intra-Library Loan (ILL)
- Buku dari cabang A bisa di-reserve oleh anggota cabang B
- Notifikasi saat buku available di cabang A
- Kurir internal untuk transfer antar cabang
- Tracking status transfer

---

## 4. Struktur Database (High-Level)

### 4.1 Core Tables

```sql
-- Branches (Multi-Branch Support)
branches (id, code, name, type, address, phone, email, is_active, created_at, updated_at)
-- type: 'central', 'faculty', 'study_program'

-- Users & Authentication
users (id, email, password_hash, role, branch_id, created_at, updated_at)
members (id, user_id, member_no, type, name, phone, address, photo, status, valid_until, branch_id)

-- Collections
collections (id, title, author, publisher, year, isbn, classification, abstract, cover_image)
collection_items (id, collection_id, barcode, call_number, location, status, condition, branch_id)
-- branch_id: lokasi fisik item saat ini

-- Circulation
loans (id, member_id, item_id, loan_branch_id, return_branch_id, loan_date, due_date, return_date, fine, status)
-- loan_branch_id: cabang tempat meminjam
-- return_branch_id: cabang tempat mengembalikan (bisa berbeda)
reservations (id, member_id, item_id, branch_id, reservation_date, expiry_date, status)
-- branch_id: cabang tempat booking

-- Inter-Branch Transfers
item_transfers (id, item_id, from_branch_id, to_branch_id, requested_by, requested_at, shipped_at, received_at, status)
-- status: 'pending', 'shipped', 'received', 'cancelled'

-- Digital Library
digital_files (id, collection_id, file_path, file_type, size, access_level)
in_repository (id, title, author, type, abstract, file_path, doi, status)

-- Classification & Subjects
classifications (id, code, name, description)
subjects (id, code, name)
collection_subjects (id, collection_id, subject_id)

-- Transactions & Fines
payments (id, member_id, loan_id, amount, payment_method, branch_id, status, created_at)

-- Settings & Logs
settings (id, key, value, description, branch_id)
-- branch_id: null = global setting, not null = branch setting
activity_logs (id, user_id, action, entity, details, ip_address, branch_id, created_at)
```

### 4.2 Laravel Migration Structure

```php
// Branches
Schema::create('branches', function (Blueprint $table) {
    $table->id();
    $table->string('code', 20)->unique();
    $table->string('name');
    $table->enum('type', ['central', 'faculty', 'study_program']);
    $table->string('address')->nullable();
    $table->string('phone')->nullable();
    $table->string('email')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

// Collection Items with Branch
Schema::create('collection_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('collection_id')->constrained();
    $table->string('barcode', 50)->unique();
    $table->string('call_number');
    $table->string('location');
    $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
    $table->enum('status', ['available', 'borrowed', 'reserved', 'lost', 'damaged', 'in_transfer']);
    $table->enum('condition', ['good', 'fair', 'poor']);
    $table->timestamps();
});

// Loans with Multi-Branch Support
Schema::create('loans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('member_id')->constrained();
    $table->foreignId('item_id')->constrained('collection_items');
    $table->foreignId('loan_branch_id')->constrained('branches');
    $table->foreignId('return_branch_id')->nullable()->constrained('branches');
    $table->date('loan_date');
    $table->date('due_date');
    $table->date('return_date')->nullable();
    $table->decimal('fine', 10, 2)->default(0);
    $table->enum('status', ['active', 'returned', 'overdue', 'lost']);
    $table->foreignId('processed_by')->constrained('users');
    $table->timestamps();
});

// Item Transfers between Branches
Schema::create('item_transfers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('item_id')->constrained('collection_items');
    $table->foreignId('from_branch_id')->constrained('branches');
    $table->foreignId('to_branch_id')->constrained('branches');
    $table->foreignId('requested_by')->constrained('users');
    $table->timestamp('requested_at');
    $table->timestamp('shipped_at')->nullable();
    $table->timestamp('received_at')->nullable();
    $table->foreignId('received_by')->nullable()->constrained('users');
    $table->enum('status', ['pending', 'shipped', 'received', 'cancelled']);
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

---

## 5. User Flow

### 5.1 Alur Peminjaman Buku
```
┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐
│ Anggota │───▶│ Login   │───▶│ Cari    │───▶│ Cek     │
│         │    │         │    │ Buku    │    │ Status  │
└─────────┘    └─────────┘    └─────────┘    └─────────┘
                                                  │
                                                  ▼
┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐
│ Notif   │◀───│ Reserv  │    │ Pinjam  │    │ Available│
│ Tersedia│    │ (Opsional)│   │ Offline │    │         │
└─────────┘    └─────────┘    └─────────┘    └─────────┘
```

### 5.2 Alur Pengembalian
```
┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐
│ Kembalik│───▶│ Scan    │───▶│ Hitung  │───▶│ Bayar   │
│ Buku    │    │ Barcode │    │ Denda   │    │ Denda   │
└─────────┘    └─────────┘    └─────────┘    └─────────┘
                                      │
                                      ▼
                               ┌─────────┐
                               │ Selesai │
                               └─────────┘
```

---

## 6. UI/UX Design Guidelines

### 6.1 Dashboard Layout
```
┌────────────────────────────────────────────────────────────┐
│  Logo    |  Perpustakaan Kampus    |  User  |  Logout    │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  ┌──────────┬──────────┬──────────┬──────────┬──────────┐ │
│  │  Total   │ Dipinjam │ Kembali  │ Telat    │  Denda   │ │
│  │   Buku   │          │          │          │  Pending │ │
│  │ 15,432   │   1,234  │   856    │    45    │ Rp 2.3jt │ │
│  └──────────┴──────────┴──────────┴──────────┴──────────┘ │
│                                                            │
│  ┌─────────────────────┬───────────────────────────────┐  │
│  │  Statistik          │  Buku Harus Dikembalikan      │  │
│  │  [Chart]            │  ┌───────────────────────────┐│  │
│  │                     │  │ Member  | Buku    | Tgl  ││  │
│  └─────────────────────┘  │ ...list of overdue...   ││  │
│                           └───────────────────────────┘│  │
│  ┌────────────────────────────────────────────────────┐ │
│  │  Aktivitas Terbaru                                  │ │
│  │  • Budi meminjam "Algoritma..."                    │ │
│  │  • Siti mengembalikan "Data Structure..."          │ │
│  └────────────────────────────────────────────────────┘ │
└────────────────────────────────────────────────────────────┘
```

### 6.2 Desain Principles
- **Clean & Modern:** Minimalis, banyak white space
- **Accessible:** WCAG 2.1 AA compliant, color contrast
- **Responsive:** Mobile-friendly, touch-friendly
- **Fast:** < 2s load time, optimistic UI
- **Intuitive:** Clear labels, familiar patterns

---

## 7. Keamanan

### 7.1 Authentication & Authorization
- JWT dengan refresh token rotation
- Password hashing (bcrypt/argon2)
- Rate limiting pada login
- Session management

### 7.2 Data Protection
- Input validation & sanitization
- SQL injection prevention (parameterized queries)
- XSS protection
- CSRF protection
- Encrypted sensitive data

### 7.3 Audit Trail
- Activity logging untuk semua transaksi
- IP address logging
- Failed login attempts

---

## 8. Integrasi yang Mungkin

| Sistem | Fungsi Integrasi |
|--------|------------------|
| Sistem Akademik | Sync data mahasiswa/dosen |
| Sistem Keuangan | Pembayaran denda |
| Email/SMS | Notification system |
| RFID | Self-check kiosks |
| Barcode Scanner | Sirkulasi offline |

---

## 9. Roadmap Pengembangan

### Phase 1: MVP (Minimum Viable Product)
- ✅ Manajemen Koleksi (CRUD)
- ✅ Manajemen Anggota
- ✅ Sirkulasi (Pinjam/Kembali)
- ✅ Laporan Dasar

### Phase 2: Enhanced Features
- ✅ Digital Library
- ✅ Advanced Search (Full-text)
- ✅ Reservasi System
- ✅ Notification System

### Phase 3: Advanced Features
- ✅ Mobile App
- ✅ Self-Service Kiosk
- ✅ Integration dengan Sistem Akademik
- ✅ Analytics & BI

### Phase 4: Innovation
- ✅ AI Recommendation
- ✅ OCR untuk buku
- ✅ Voice Search
- ✅ Chatbot Assistant

---

## 10. Checklist Sebelum Development

- [ ] Finalisasi fitur & scope
- [ ] Design system & UI kit
- [ ] Database schema detail
- [ ] API specification
- [ ] Setup development environment
- [ ] Setup CI/CD pipeline
- [ ] Define coding standards
- [ ] Testing strategy

---

## 11. Referensi: SLiMS (Senayan Library Management System)

### 11.1 Apa itu SLiMS?

**SLiMS** adalah sistem manajemen perpustakaan open source yang telah digunakan secara luas di Indonesia. Versi terbaru (SLiMS 8 Acacia) telah mendapatkan audit ISO 9126 dengan skor **728 (VERY GOOD)**.

### 11.2 Perbandingan Fitur: SLiMS vs Rancangan Aplikasi Kita

| Fitur | SLiMS | Rancangan Kita | Catatan |
|-------|-------|----------------|---------|
| **OPAC** | ✅ Simple & Advanced Search | ✅ Full-text Search dengan AI | Kita tambah semantic search |
| **Metadata Standards** | MODS XML, JSON-LD, OAI-PMH | ✅ Support standar yang sama | Penting untuk interoperabilitas |
| **Digital Files** | ✅ PDF, DOC, RTF, XLS, PPT, Video, Audio | ✅ Same + DRMR protection | Tambahan watermark/drm |
| **Barcode Support** | ✅ Barcode generator | ✅ Barcode + QR Code | QR lebih flexible |
| **Circulation** | ✅ Pinjam/Kembali/Reservasi | ✅ Same + Self-service | Tambahan kiosk mandiri |
| **Union Catalog** | ✅ Union Catalog Server | ✅ Multi-branch support | Untuk perpustakaan fakultas |
| **Federated Search** | ✅ Nayanes integration | ✅ Elasticsearch/Meilisearch | Modern search engine |
| **Stock Opname** | ✅ Stocktaking module | ✅ Same + Mobile scanner | Gunakan mobile app |
| **RSS Feed** | ✅ RSS untuk OPAC | ✅ RSS + Email/SMS notif | Multi-channel notifikasi |
| **Responsive UI** | ✅ Bootstrap-based | ✅ Modern Next.js + shadcn/ui | Tech stack lebih modern |
| **Search Indexing** | Sphinx, MongoDB | Meilisearch/Elasticsearch | Lebih powerful |
| **User Management** | User & Group management | ✅ RBAC + LDAP/SSO | Integration dengan SSO kampus |

### 11.3 Standar Metadata yang Diadopsi dari SLiMS

#### 11.3.1 MODS (Metadata Object Description Schema)
Format XML untuk bibliographic data:
```xml
<mods xmlns="http://www.loc.gov/mods/v3">
  <titleInfo>
    <title>Algoritma dan Pemrograman</title>
  </titleInfo>
  <name type="personal">
    <namePart>Rinaldi Munir</namePart>
    <role>
      <roleTerm type="text">author</roleTerm>
    </role>
  </name>
  <originInfo>
    <publisher>Informatika</publisher>
    <dateIssued>2022</dateIssued>
  </originInfo>
  <identifier type="isbn">978-623-02-3456-7</identifier>
</mods>
```

#### 11.3.2 JSON-LD dengan schema.org
Format JSON untuk SEO dan rich snippets:
```json
{
  "@context": "https://schema.org",
  "@type": "Book",
  "name": "Algoritma dan Pemrograman",
  "author": {
    "@type": "Person",
    "name": "Rinaldi Munir"
  },
  "isbn": "978-623-02-3456-7",
  "datePublished": "2022",
  "publisher": {
    "@type": "Organization",
    "name": "Informatika"
  }
}
```

#### 11.3.3 OAI-PMH (Open Archives Initiative Protocol)
Untuk metadata harvesting dan interoperabilitas antar perpustakaan:
```xml
<OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/">
  <GetRecord>
    <record>
      <header>
        <identifier>oai:library:12345</identifier>
        <datestamp>2024-01-15</datestamp>
      </header>
      <metadata>
        <oai_dc:dc xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/">
          <dc:title>Algoritma dan Pemrograman</dc:title>
          <dc:creator>Rinaldi Munir</dc:creator>
          <dc:date>2022</dc:date>
        </oai_dc:dc>
      </metadata>
    </record>
  </GetRecord>
</OAI-PMH>
```

### 11.4 Fitur SLiMS yang Akan Diadopsi

| Fitur SLiMS | Implementasi di Aplikasi Kita |
|-------------|------------------------------|
| Master Files management | Reference data: GMD, Collection Type, Publisher, Author, Location, Supplier |
| Holiday settings | Konfigurasi hari libur untuk perhitungan tanggal kembali |
| Loan Rules yang flexible | Aturan pinjam dinamis per tipe koleksi & tipe anggota |
| Barcode generator utility | Generate barcode/QR untuk buku & kartu anggota |
| Database backup utility | Automated backup dengan scheduling |
| Serial publication control | Manajemen jurnal/majalah berjangka |
| Quick return mode | Mode pengembalian cepat (scan only) |

### 11.5 Fitur Tambahan (Beyond SLiMS)

Fitur yang tidak ada di SLiMS tapi akan ditambahkan:

1. **AI-Powered Features**
   - Recommendation system berdasarkan riwayat peminjaman
   - Auto-tagging menggunakan NLP
   - Semantic search (cari berdasarkan topik, bukan keyword)

2. **Modern Authentication**
   - LDAP/Active Directory integration
   - SSO (Single Sign-On) dengan SAML
   - Multi-factor authentication

3. **Mobile-First Experience**
   - Progressive Web App (PWA)
   - Native mobile app (Android/iOS)
   - Push notifications

4. **Self-Service Kiosk**
   - Self-check in/out
   - Payment kiosk untuk denda
   - Information terminal

5. **Analytics Dashboard**
   - Real-time statistics
   - Heatmap penggunaan koleksi
   - Predictive analytics untuk pembelian koleksi

6. **Social Features**
   - Review & rating buku
   - Book discussion forum
   - Share to social media

### 11.6 Architecture SLiMS sebagai Referensi

```
SLiMS Architecture:
┌─────────────────────────────────────────────────────────────┐
│                       Web Browser                           │
└─────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                    SLiMS (PHP)                             │
│  ┌───────────┬───────────┬───────────┬───────────┬────────┐ │
│  │  OPAC     │  Circulation│  Catalog │  Members │  Admin │ │
│  └───────────┴───────────┴───────────┴───────────┴────────┘ │
└─────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                    MySQL Database                          │
│  ┌───────────┬───────────┬───────────┬───────────┬────────┐ │
│  │  biblio   │  item     │  member   │  loan    │  ...   │ │
│  └───────────┴───────────┴───────────┴───────────┴────────┘ │
└─────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│              External Search Engines                        │
│  ┌─────────────────────┬──────────────────────────────────┐ │
│  │  Sphinx Search      │  MongoDB (Optional)             │ │
│  └─────────────────────┴──────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

### 11.7 Keunggulan SLiMS yang Perlu Dipertahankan

| Aspek | Keunggulan | Cara Memaintain |
|-------|-----------|-----------------|
| **Open Source** | Gratis & dapat dikustomisasi | Tetap open source atau lisensi kampus |
| **Community** | Aktif & dukungan lokal | Build community sekitar aplikasi |
| **Standar Industri** | MARC21, ISO 2709, Z39.50 | Support standar yang sama |
| **Documentation** | Dokumentasi lengkap | Buat dokumentasi yang baik |
| **Local Language** | Interface Bahasa Indonesia | Default Bahasa Indonesia |

---

## 12. Pertanyaan Lanjutan

1. Apakah perlu integrasi dengan sistem akademik/keuangan kampus yang sudah ada?
2. Apakah ada standar tertentu untuk klasifikasi (DDC, LCC, atau lokal)?
3. Apakah perlu fitur multi-branch/perpustakaan fakultas?
4. Apakah ada preferensi teknologi tertentu dari tim IT kampus?
5. Berapa estimasi budget dan timeline yang diharapkan?
6. Apakah aplikasi akan open source seperti SLiMS atau proprietary?
7. Apakah perlu import data dari SLiMS/system lain yang sudah ada?

---

## 13. Keputusan Arsitektur Final

Berdasarkan preferensi teknologi dan referensi SLiMS, keputusan arsitektur final:

### 13.1 Teknologi Stack (Laravel + Tailwind + Alpine.js)

#### Backend (Laravel 11+)
| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| Framework | Laravel | 11+ |
| Language | PHP | 8.2+ |
| ORM | Eloquent | Native Laravel |
| Authentication | Laravel Breeze + Sanctum | Latest |
| Queue | Redis + Laravel Queues | Latest |
| Scheduler | Laravel Scheduler | Native |

#### Frontend
| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| Template Engine | Blade | Native Laravel |
| Styling | Tailwind CSS | 3.x |
| Interactivity | Alpine.js | 3.x |
| Real-time | Livewire | 3.x (opsional) |
| Build Tool | Vite | Native Laravel |

#### Database & Infrastructure
| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| Database | MySQL | 8.0+ |
| Search Engine | Meilisearch | 1.x |
| Cache & Queue | Redis | 7.x |
| Web Server | Nginx + PHP-FPM | Latest |
| Deployment | Laravel Forge / VPS | Latest |

### 13.2 Alasan Memilih Laravel Stack

| Aspek | Alasan |
|-------|--------|
| **Kemudahan Development** | Laravel memiliki dokumentasi lengkap, komunitas besar di Indonesia |
| **Hosting Friendly** | Banyak hosting murah yang support PHP, cocok untuk budget terbatas |
| **SLiMS Compatibility** | SLiMS juga PHP-based, memudahkan import data dan integrasi |
| **Developer Availability** | Banyak developer Laravel di Indonesia |
| **Time to Market** | Lebih cepat develop dengan Breeze, Livewire, dll |
| **Maintenance** | Mudah dicari developer untuk maintenance |

### 13.3 Database (MySQL 8.0+)
- **Primary:** MySQL 8.0+ (mature, reliable, SLiMS compatible)
- **Search:** Meilisearch (full-text search, typo-tolerant, fast)
- **Cache:** Redis (session, rate limiting, queue)

### 13.4 Backend API (Laravel)
- **REST API:** Laravel API Resources + Scribe (auto-documentation)
- **Authentication:** Laravel Breeze (session) + Sanctum (API tokens)
- **OAI-PMH Provider:** Custom Laravel package untuk metadata harvesting
- **JSON-LD Export:** Blade components + JSON serialization

### 13.5 Frontend (Blade + Tailwind + Alpine.js)
- **Admin Panel:** Blade templates + Tailwind CSS + Alpine.js
- **OPAC Public:** Blade templates + Tailwind CSS (PWA-ready)
- **Interactive Components:** Livewire untuk dynamic components (opsional)

### 13.6 Standards Compliance
- ✅ MARC21 import/export (kompatibel dengan SLiMS)
- ✅ MODS XML untuk metadata detail
- ✅ JSON-LD dengan schema.org
- ✅ OAI-PMH Dublin Core untuk harvesting
- ✅ Z39.50/SRU untuk federated search (opsional)

### 13.7 Multi-Branch Architecture
```
Database Schema:
┌─────────────────────────────────────────────────────────────┐
│  branches (id, code, name, type, ...)                      │
│  users (id, email, ..., branch_id)                         │
│  collection_items (id, ..., branch_id, status)             │
│  loans (id, ..., loan_branch_id, return_branch_id)         │
│  item_transfers (id, item_id, from_branch_id, to_branch_id)│
└─────────────────────────────────────────────────────────────┘

Access Control:
┌─────────────────────────────────────────────────────────────┐
│  Super Admin → Semua Branch                                │
│  Branch Admin → Hanya branchnya sendiri                     │
│  Staff → Hanya branchnya sendiri                            │
│  Member → Semua branch (untuk searching)                    │
└─────────────────────────────────────────────────────────────┘
```

---

*Dokumen ini bersifat fleksibel dan dapat disesuaikan dengan kebutuhan spesifik perpustakaan kampus Anda.*
