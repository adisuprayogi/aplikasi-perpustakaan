# MANUAL BOOK
## Aplikasi Perpustakaan Digital

---

---

## Daftar Isi

1. [Pendahuluan](#pendahuluan)
2. [User Roles & Login](#user-roles--login)
3. [Panduan Per Role](#panduan-per-role)
   - [Super Admin](#super-admin)
   - [Admin](#admin)
   - [Branch Admin](#branch-admin)
   - [Circulation Staff](#circulation-staff)
   - [Catalog Staff](#catalog-staff)
   - [Report Viewer](#report-viewer)
4. [Proses Bisnis](#proses-bisnis)

---

---

## Pendahuluan

Aplikasi Perpustakaan Digital adalah sistem manajemen perpustakaan terintegrasi dengan 7 role berbeda.

---

---

## User Roles & Login

### Role yang Tersedia

| Role | Jumlah Permissions | Deskripsi |
|------|-------------------|-----------|
| **super_admin** | 77 | Full akses ke seluruh sistem |
| **admin** | 77 | Full akses sama seperti super_admin |
| **branch_admin** | 39 | Admin cabang - kelola cabang, anggota, sirkulasi |
| **circulation_staff** | 13 | Staff sirkulasi - peminjaman & pengembalian |
| **catalog_staff** | 19 | Staff katalog - kelola koleksi & katalog |
| **report_viewer** | 6 | Viewer laporan - hanya melihat laporan |
| **member** | 6 | Anggota perpustakaan |

### Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@kampus.ac.id | super123 |
| Admin | admin@library.test | password123 |
| Branch Admin | pusat@kampus.ac.id | branch123 |
| Circulation Staff | lib-fkip@kampus.ac.id | circulation123 |
| Catalog Staff | catalog@library.test | catalog123 |
| Report Viewer | report@library.test | report123 |

---

---

## Panduan Per Role

---

### Super Admin

**Role ID:** 1 (super_admin)
**Permissions:** 77 (Full Access)

![Super Admin Dashboard](screenshots/roles/role-super-admin-dashboard.png)

**Deskripsi:** Administrator tertinggi dengan akses penuh ke seluruh sistem.

**Menu yang Dapat Diakses:**

| Menu | Akses |
|------|-------|
| Dashboard | ✅ |
| Sirkulasi (Peminjaman, Reservasi) | ✅ |
| Branch | ✅ |
| Anggota | ✅ |
| Koleksi | ✅ |
| Perpustakaan Digital | ✅ |
| Repository | ✅ |
| Aturan Peminjaman | ✅ |
| Manajemen User | ✅ |
| Laporan (semua) | ✅ |
| Transfer Antar Branch | ✅ |
| Pengaturan | ✅ |

**Tanggung Jawab:**
- Manajemen user dan role
- Konfigurasi sistem
- Manajemen cabang
- Monitor semua aktivitas
- Export laporan global

---

### Admin

**Role ID:** 7 (admin)
**Permissions:** 77 (Full Access)

![Admin Dashboard](screenshots/roles/role-admin-dashboard.png)

**Deskripsi:** Administrator dengan akses penuh sama seperti Super Admin.

**Menu yang Dapat Diakses:** Sama seperti Super Admin

---

### Branch Admin

**Role ID:** 2 (branch_admin)
**Permissions:** 39

![Branch Admin Dashboard](screenshots/roles/role-branch-admin-dashboard.png)

**Deskripsi:** Administrator yang mengelola satu cabang perpustakaan.

**Menu yang Dapat Diakses:**

| Menu | Akses | Keterangan |
|------|-------|-----------|
| Dashboard | ✅ | |
| Sirkulasi (Peminjaman, Reservasi) | ✅ | |
| Anggota | ✅ | Register, edit, renew, suspend |
| Koleksi | ✅ | View only (lihat data) |
| Perpustakaan Digital | ✅ | Kelola file digital |
| Repository | ✅ | Kelola repository |
| Laporan | ✅ | Semua laporan |
| Transfer Antar Branch | ✅ | |
| Manajemen User | ✅ | Create, edit user (limited) |
| Pengaturan | ✅ | View settings |
| Branch | ❌ | Tidak ada akses |

**Tanggung Jawab:**
- Manajemen anggota cabang
- Monitor sirkulasi cabang
- Laporan cabang
- Manajemen file digital dan repository

---

### Circulation Staff

**Role ID:** 3 (circulation_staff)
**Permissions:** 13

![Circulation Staff Dashboard](screenshots/roles/role-circulation-staff-dashboard.png)

**Deskripsi:** Staff yang menangani proses sirkulasi di front desk.

**Menu yang Dapat Diakses:**

| Menu | Akses | Keterangan |
|------|-------|-----------|
| Dashboard | ✅ | |
| Sirkulasi (Peminjaman) | ✅ | Create, return, renew |
| Sirkulasi (Reservasi) | ✅ | View, create |
| Anggota | ✅ | View only |
| Koleksi | ✅ | View only |
| Laporan | ❌ | Tidak ada akses |

![Circulation Staff - Loans](screenshots/roles/role-circulation-staff-loans.png)

![Circulation Staff - Members](screenshots/roles/role-circulation-staff-members.png)

**Proses Bisnis Utama:**

#### 1. Peminjaman (Checkout)
```
1. Masuk ke menu Sirkulasi → Peminjaman Baru
2. Pilih anggota (search berdasarkan nama/ID)
3. Scan/input barcode koleksi yang dipinjam
4. Sistem hitung tanggal jatuh tempo otomatis
5. Klik "Simpan" untuk proses peminjaman
```

#### 2. Pengembalian (Check-in)
```
1. Buka daftar peminjaman
2. Cari peminjaman yang akan dikembalikan
3. Klik "Kembalikan"
4. Cek kondisi buku (baik/rusak/hilang)
5. Sistem hitung denda (jika terlambat)
6. Proses pembayaran denda (jika ada)
7. Selesaikan pengembalian
```

#### 3. Perpanjangan
```
1. Buka daftar peminjaman
2. Cari peminjaman aktif
3. Klik "Perpanjang"
4. Sistem hitung tanggal baru
5. Syarat: max 2x perpanjangan, tidak ada reservasi
```

---

### Catalog Staff

**Role ID:** 4 (catalog_staff)
**Permissions:** 19

![Catalog Staff Dashboard](screenshots/roles/role-catalog-staff-dashboard.png)

**Deskripsi:** Staff yang mengelola katalog dan koleksi perpustakaan.

**Menu yang Dapat Diakses:**

| Menu | Akses | Keterangan |
|------|-------|-----------|
| Dashboard | ✅ | |
| Koleksi | ✅ | Create, edit, import, export |
| Perpustakaan Digital | ✅ | Kelola file digital |
| Repository | ✅ | Kelola repository |
| Aturan Peminjaman | ❌ | Tidak ada akses |
| Sirkulasi | ❌ | Tidak ada akses |
| Laporan | ❌ | Tidak ada akses |

![Catalog Staff - Collections](screenshots/roles/role-catalog-staff-collections.png)

**Proses Bisnis Utama:**

#### 1. Entry Koleksi Baru
```
1. Masuk ke menu Koleksi
2. Klik "Tambah Koleksi"
3. Isi data bibliografis:
   - Judul, Penulis, ISBN/ISSN
   - Penerbit, Tahun terbit, Bahasa
   - GMD (Tipe media)
4. Isi klasifikasi:
   - Nomor DDC/LC
   - Subject/topik
5. Isi detail koleksi:
   - Tipe koleksi, Kategori
   - Lokasi rak, Jumlah copy
6. Klik "Simpan"
7. Sistem generate barcode otomatis
```

#### 2. Generate Barcode & Label
```
1. Buka detail koleksi
2. Klik "Generate Barcode"
3. Pilih jenis label:
   - Barcode label (untuk buku)
   - Spine label (untuk punggung buku)
   - QR Code (untuk mobile)
4. Print label
5. Tempel di buku
```

#### 3. Import/Export Koleksi
```
Import:
1. Koleksi → Import
2. Upload file CSV/Excel
3. Mapping kolom
4. Preview data
5. Import data

Export:
1. Koleksi → Export
2. Pilih filter data
3. Download CSV/Excel
```

---

### Report Viewer

**Role ID:** 5 (report_viewer)
**Permissions:** 6

![Report Viewer Dashboard](screenshots/roles/role-report-viewer-dashboard.png)

**Deskripsi:** User yang hanya bisa melihat laporan dan statistik.

**Menu yang Dapat Diakses:**

| Menu | Akses | Keterangan |
|------|-------|-----------|
| Dashboard | ✅ | Statistik dasar |
| Laporan | ✅ | Semua laporan (read-only) |
| Lainnya | ❌ | Menu lain tidak ada akses |

![Report Viewer - Reports](screenshots/roles/role-report-viewer-reports.png)

**Laporan yang Tersedia:**

| Laporan | Deskripsi |
|---------|-----------|
| Dashboard Laporan | Ringkasan semua statistik |
| Laporan Peminjaman | Data peminjaman per periode |
| Laporan Keterlambatan | Daftar peminjaman terlambat |
| Laporan Denda | Data denda dan pembayaran |
| Laporan Koleksi | Statistik koleksi |
| Laporan Anggota | Data anggota aktif |
| Perbandingan Cabang | Multi-cabang comparison |

**Fitur:**
- Filter rentang tanggal
- Export ke CSV/Excel
- View charts dan grafik
- Print laporan

---

---

## Proses Bisnis

### Alur Kerja Lengkap

```
┌─────────────────┐
│ CATALOG STAFF   │
│ (Entry Koleksi) │
└────────┬────────┘
         │
         ▼
    ┌─────────────┐
    │  KOLEKSI   │
    │   SHELF    │
    └─────────────┘
         │
         ▼
┌─────────────────────┐
│ CIRCULATION STAFF   │
│  (Proses Peminjaman) │
└──────────┬──────────┘
           │
           ▼
      ┌─────────┐
      │ MEMBER  │
      │ (Pinjam) │
      └─────────┘
```

### Matrix Akses Menu

| Menu | Super Admin | Admin | Branch Admin | Circulation Staff | Catalog Staff | Report Viewer |
|------|------------|-------|--------------|-------------------|---------------|----------------|
| Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Sirkulasi → Peminjaman | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ |
| Sirkulasi → Peminjaman Baru | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ |
| Sirkulasi → Reservasi | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ |
| Branch | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Anggota | ✅ | ✅ | ✅ | ✅ (view only) | ❌ | ❌ |
| Koleksi | ✅ | ✅ | ✅ (view only) | ✅ (view only) | ✅ | ❌ |
| Perpustakaan Digital | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |
| Repository | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |
| Aturan Peminjaman | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Manajemen User | ✅ | ✅ | ✅ (limited) | ❌ | ❌ | ❌ |
| Laporan | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ |
| Transfer Antar Branch | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Pengaturan | ✅ | ✅ | ✅ (view only) | ❌ | ❌ | ❌ |

---

*Dokumentasi ini berdasarkan testing langsung aplikasi dengan 7 role berbeda*

*Update: 2 Februari 2026*
