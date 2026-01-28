# Functional Requirements Specification (FRS)
## Aplikasi Perpustakaan Kampus - Monolit Architecture

**Version:** 1.0
**Date:** 2026-01-27
**Status:** Draft

---

## 1. Introduction

### 1.1 Purpose
Dokumen ini mendefinisikan kebutuhan fungsional aplikasi Sistem Informasi Perpustakaan Kampus (SIPK) dengan arsitektur monolit.

### 1.2 Scope
Aplikasi SIPK mencakup manajemen koleksi, sirkulasi, keanggotaan, perpustakaan digital, dan fitur multi-branch untuk perpustakaan kampus dengan >5000 pengguna.

### 1.3 Target Users
| User Role | Deskripsi |
|-----------|-----------|
| **Super Admin** | Administrator utama sistem |
| **Branch Admin** | Administrator perpustakaan fakultas |
| **Staff Sirkulasi** | Petugas sirkulasi (pinjam/kembali) |
| **Staff Koleksi** | Petugas pengelola koleksi |
| **Dosen** | Peminjam (prioritas tinggi) |
| **Mahasiswa** | Peminjam (pengguna terbanyak) |
| **Staf Kampus** | Peminjam |
| **Guest** | Pengunjung (read-only OPAC) |

---

## 2. Functional Requirements

### 2.1 Modul Authentication & Authorization (AUTH)

| ID | Requirement | Description | Priority |
|----|-------------|-------------|----------|
| AUTH-001 | Login | User dapat login dengan email + password | High |
| AUTH-002 | Logout | User dapat logout dengan aman | High |
| AUTH-003 | Session Management | Session timeout setelah 30 menit idle | High |
| AUTH-004 | Password Reset | Reset password via email | Medium |
| AUTH-005 | Remember Me | Opsi remember me (7 hari) | Low |
| AUTH-006 | Role-Based Access Control | Akses berdasarkan role | High |
| AUTH-007 | Branch-Based Access | Admin/staf hanya akses branchnya | High |
| AUTH-008 | Last Login | Menampilkan info login terakhir | Low |

---

### 2.2 Modul Manajemen Branch (BRANCH)

| ID | Requirement | Description | Priority |
|----|-------------|-------------|----------|
| BRANCH-001 | Create Branch | Super admin dapat membuat branch baru | High |
| BRANCH-002 | Update Branch | Update informasi branch (nama, alamat, telp) | High |
| BRANCH-003 | Deactivate Branch | Non-aktifkan branch (soft delete) | Medium |
| BRANCH-004 | View Branch List | Daftar semua branch dengan filter | High |
| BRANCH-005 | Branch Type | Tipe: Central, Faculty, Study Program | High |
| BRANCH-006 | Branch Settings | Setting khusus per branch | Medium |

---

### 2.3 Modul Manajemen Anggota (MEMBER)

| ID | Requirement | Description | Priority |
|----|-------------|-------------|----------|
| MEMBER-001 | Registration | Registrasi anggota baru oleh staff | High |
| MEMBER-002 | Self-Registration | Mahasiswa/dosen dapat registrasi mandiri | Medium |
| MEMBER-003 | Member Type | Tipe: Mahasiswa, Dosen, Staf | High |
| MEMBER-004 | Member Number | Generate nomor anggota otomatis | High |
| MEMBER-005 | Member Card | Generate kartu anggota (PDF) dengan QR Code | Medium |
| MEMBER-006 | Member Status | Status: Active, Suspended, Expired, Blacklisted | High |
| MEMBER-007 | Validity Period | Masa berlaku keanggotaan | High |
| MEMBER-008 | Renew Membership | Perpanjang masa berlaku | Medium |
| MEMBER-009 | Suspend Member | Suspended jika denda > batas atau pelanggaran | High |
| MEMBER-010 | Member Profile | Lihat dan edit profile anggota | High |
| MEMBER-011 | Member History | Riwayat peminjaman anggota | High |
| MEMBER-012 | Import Member | Import data dari sistem akademik/SLiMS | Medium |
| MEMBER-013 | Export Member | Export data ke CSV/Excel | Low |

---

### 2.4 Modul Manajemen Koleksi (COLLECTION)

| ID | Requirement | Description | Priority |
|----|-------------|-------------|----------|
| COLL-001 | Add Bibliographic | Tambah data bibliografi baru | High |
| COLL-002 | Edit Bibliographic | Edit data bibliografi | High |
| COLL-003 | Delete Bibliographic | Hapus data bibliografi (soft delete) | Medium |
| COLL-004 | Add Items | Tambah copy/eksemplar buku | High |
| COLL-005 | Edit Items | Edit data item (lokasi, kondisi) | High |
| COLL-006 | Delete Items | Hapus item | Medium |
| COLL-007 | Barcode Generation | Generate barcode otomatis | High |
| COLL-008 | Call Number | Generate call number otomatis (DDC/LCC) | High |
| COLL-009 | Cover Image | Upload cover image buku | Medium |
| COLL-010 | Item Status | Status: Available, Borrowed, Reserved, Lost, Damaged, In Transfer | High |
| COLL-011 | Item Condition | Kondisi: Good, Fair, Poor | Medium |
| COLL-012 | Item Location | Lokasi fisik item (rak, lantai) | High |
| COLL-013 | Collection Type | Tipe: Book, Journal, Thesis, DVD, Reference | High |
| COLL-014 | GMD Management | General Material Design (GMD) | Medium |
| COLL-015 | Publisher Management | Master data penerbit | Low |
| COLL-016 | Author Management | Master data penulis | Low |
| COLL-017 | Subject Management | Master data subjek/topik | Low |
| COLL-018 | Classification | Master data klasifikasi (DDC/LCC) | Medium |
| COLL-019 | Import MARC21 | Import data dari format MARC21 | Medium |
| COLL-020 | Export MARC21 | Export data ke format MARC21 | Medium |
| COLL-021 | Duplicate Check | Cek duplikat saat tambah bibliografi | Medium |

---

### 2.5 Modul Sirkulasi - Peminjaman (CIRCULATION-LOAN)

| ID | Requirement | Description | Priority |
|----|-------------|-------------|----------|
| LOAN-001 | Loan Transaction | Proses peminjaman dengan scan barcode | High |
| LOAN-002 | Member Validation | Validasi keanggotaan aktif | High |
| LOAN-003 | Item Validation | Validasi ketersediaan item | High |
| LOAN-004 | Loan Period | Hitung tanggal kembali otomatis | High |
| LOAN-005 | Loan Rules | Aturan pinjam per tipe anggota & tipe koleksi | High |
| LOAN-006 | Max Loan Limit | Batas jumlah pinjaman | High |
| LOAN-007 | Reference No Loan | Buku reference tidak bisa dipinjam | High |
| LOAN-008 | Print Receipt | Cetak struk peminjaman | Medium |
| LOAN-009 | Loan History | Riwayat peminjaman | High |
| LOAN-010 | Loan Report | Laporan peminjaman harian/bulanan/tahunan | High |

---

### 2.6 Modul Sirkulasi - Pengembalian (CIRCULATION-RETURN)

| ID | Requirement | Description | Priority |
|----|-------------|-------------|----------|
| RET-001 | Return Transaction | Proses pengembalian dengan scan barcode | High |
| RET-002 | Overdue Check | Cek keterlambatan otomatis | High |
| RET-003 | Fine Calculation | Hitung denda otomatis | High |
| RET-004 | Fine Payment | Catat pembayaran denda | High |
| RET-005 | Condition Check | Cek kondisi buku saat dikembalikan | Medium |
| RET-006 | Lost/Damaged | Penanganan buku hilang/rusak | High |
| RET-007 | Quick Return | Mode pengembalian cepat (scan only) | Medium |
| RET-008 | Return Report | Laporan pengembalian | High |

---

### 2.7 Modul Sirkulasi - Perpanjangan (CIRCULATION-RENEWAL)

| ID | Requirement | Description | Priority |
|----|-------------|-------------|----------|
| RENEW-001 | Renew Loan | Perpanjang masa pinjam | High |
| RENEW-002 | Renew Limit | Max 2x perpanjangan | High |
| RENEW-003 | Reservation Check | Tidak bisa perpanjang jika ada booking | High |
| RENEW-004 | Overdue No Renew | Terlambat tidak bisa perpanjang | High |

---

### 2.8 Modul Reservasi (RESERVATION)

| ID | Requirement | Description | Priority |
|----|-------------|-------------|----------|
| RES-001 | Create Reservation | Booking buku yang sedang dipinjam | Medium |
| RES-002 | Reservation Notification | Notifikasi saat buku tersedia | Medium |
| RES-003 | Reservation Expiry | Batas waktu ambil (3 hari) | Medium |
| RES-004 | Cancel Reservation | Batalkan reservasi | Low |
| RES-005 | Reservation Queue | Antrian jika >1 reservasi | Medium |

---

### 2.9 Modul Transfer Antar Branch (TRANSFER)

| ID | Requirement | Description | Priority |
|----|-------------|-------------|----------|
| TRANS-001 | Request Transfer | Request transfer item antar branch | Medium |
| TRANS-002 | Ship Transfer | Proses pengiriman (shipped) | Medium |
| TRANS-003 | Receive Transfer | Proses penerimaan (received) | Medium |
| TRANS-004 | Transfer Tracking | Track status transfer | Medium |
| TRANS-005 | Transfer History | Riwayat transfer | Low |

---

### 2.10 Modul OPAC & Search (OPAC)

| ID | Requirement | Description | Priority |
|----|-------------|-------------|----------|
| OPAC-001 | Simple Search | Cari sederhana (keyword) | High |
| OPAC-002 | Advanced Search | Cari lanjutan (filter: judul, penulis, tahun, subjek) | High |
| OPAC-003 | Full-Text Search | Search dalam isi abstrak/deskripsi | High |
| OPAC-004 | Search Results | Tampilkan hasil dengan pagination | High |
| OPAC-005 | Detail View | Lihat detail lengkap koleksi | High |
| OPAC-006 | Availability Check | Cek ketersediaan item | High |
| OPAC-007 | Branch Filter | Filter berdasarkan branch | High |
| OPAC-008 | Cover Thumbnail | Tampilkan cover image | Medium |
| OPAC-009 | Related Items | Rekomendasi buku terkait | Low |

---

### 2.11 Modul Digital Library (DIGITAL)

| ID | Requirement | Description | Priority |
|----|-------------|-------------|----------|
| DIG-001 | Upload File | Upload file digital (PDF, DOC, dll) | Medium |
| DIG-002 | File Preview | Preview file sebelum download | Medium |
| DIG-003 | Download Control | Akses download berdasarkan role | Medium |
| DIG-004 | File Management | Manage file digital (hapus, replace) | Medium |
| DIG-005 | Institutional Repository | Koleksi karya ilmiah kampus | Medium |
| DIG-006 | DOI Assignment | Assign DOI untuk karya ilmiah | Low |
| DIG-007 | DRM Protection | Watermark pada file (opsional) | Low |

---

### 2.12 Modul Laporan & Statistik (REPORT)

| ID | Requirement | Description | Priority |
|----|-------------|-------------|----------|
| REP-001 | Loan Report | Laporan peminjaman | High |
| REP-002 | Return Report | Laporan pengembalian | High |
| REP-003 | Overdue Report | Laporan keterlambatan | High |
| REP-004 | Fine Report | Laporan denda | High |
| REP-005 | Collection Report | Laporan koleksi | High |
| REP-006 | Member Report | Laporan anggota | Medium |
| REP-007 | Popular Items | Buku terpopuler | Medium |
| REP-008 | Active Members | Anggota paling aktif | Medium |
| REP-009 | Branch Comparison | Perbandingan antar branch | Medium |
| REP-010 | Dashboard | Dashboard statistik real-time | High |
| REP-011 | Export Report | Export laporan ke PDF/Excel | High |

---

### 2.13 Modul Settings & Configuration (SETTINGS)

| ID | Requirement | Description | Priority |
|----|-------------|-------------|----------|
| SET-001 | Library Info | Konfigurasi nama, alamat, logo perpustakaan | High |
| SET-002 | Loan Rules | Aturan pinjam (lama pinjam, max pinjam, denda) | High |
| SET-003 | Holiday Settings | Pengaturan hari libur | Medium |
| SET-004 | Operating Hours | Jam operasional | Low |
| SET-005 | Fine Configuration | Konfigurasi denda | High |
| SET-006 | Barcode Settings | Format barcode | Low |
| SET-007 | Backup Settings | Jadwal backup database | Medium |
| SET-008 | User Management | Kelola user dan role | High |

---

## 3. Business Rules

### 3.1 Aturan Peminjaman

| Tipe Anggota | Masa Pinjam | Max Pinjam | Denda/Hari |
|--------------|-------------|------------|------------|
| Mahasiswa | 7 hari | 3 buku | Rp 1.000 |
| Dosen | 14 hari | 5 buku | Rp 1.000 |
| Staf Kampus | 7 hari | 3 buku | Rp 1.000 |

### 3.2 Aturan Perpanjangan
- Max 2x perpanjangan
- Tidak bisa perpanjang jika:
  - Sudah terlambat
  - Ada reservasi dari member lain
  - Item sedang di-transfer

### 3.3 Aturan Denda
| Kondisi | Denda |
|---------|-------|
| Terlambat | Rp 1.000 / hari |
| Hilang | Harga buku + 20% |
| Rusak Berat | Harga buku |
| Rusak Ringan | Sesuai penilaian |

### 3.4 Aturan Sanksi
- Suspend jika denda > Rp 50.000
- Blacklist jika:
  - Hilang dan tidak mengganti dalam 30 hari
  - Pelanggaran berat (mencuri, merusak sengaja)

### 3.5 Aturan Reservasi
- Max 3 reservasi aktif per member
- Batas ambil: 3 hari setelah available
- Otomatis cancel jika lewat batas ambil

---

## 4. Non-Functional Requirements

### 4.1 Performance
| Metric | Target |
|--------|--------|
| Page Load Time | < 2 detik |
| Search Response | < 1 detik |
| Transaction Processing | < 3 detik |
| Concurrent Users | 100+ simultaneous users |

### 4.2 Security
| Requirement | Description |
|-------------|-------------|
| Authentication | Password hashing (bcrypt), session management |
| Authorization | Role-based access control, branch-based access |
| Input Validation | Server-side validation, XSS protection |
| SQL Injection | Parameterized queries (Eloquent) |
| CSRF Protection | CSRF tokens on forms |
| Audit Trail | Log semua transaksi penting |

### 4.3 Usability
| Requirement | Description |
|-------------|-------------|
| Responsive Design | Mobile-friendly, tablet-friendly |
| Accessibility | WCAG 2.1 AA compliant |
| Language | Bahasa Indonesia |
| Help Documentation | User guide per modul |

### 4.4 Reliability
| Metric | Target |
|--------|--------|
| Uptime | 99% |
| Data Backup | Daily backup |
| Error Handling | Graceful error handling |

### 4.5 Scalability
| Requirement | Description |
|-------------|-------------|
| Users | Support hingga 10.000+ users |
| Collections | Support hingga 100.000+ items |
| Branches | Support hingga 20+ branches |

---

## 5. User Stories

### 5.1 Mahasiswa Meminjam Buku
```
AS A mahasiswa
I WANT TO meminjam buku dari perpustakaan
SO THAT saya dapat belajar untuk tugas kuliah

Acceptance Criteria:
- Dapat login dengan NIM dan password
- Dapat mencari buku di OPAC
- Dapat melihat ketersediaan buku
- Dapat meminjam maksimal 3 buku
- Dapat melihat tanggal pengembalian
- Mendapat struk peminjaman
```

### 5.2 Staff Memproses Pengembalian
```
AS A staff sirkulasi
I WANT TO memproses pengembalian buku
SO THAT stok buku terupdate dan denda dihitung

Acceptance Criteria:
- Dapat scan barcode buku
- Sistem menghitung keterlambatan otomatis
- Sistem menghitung denda otomatis
- Dapat input kondisi buku
- Dapat memproses pembayaran denda
- Mendapat struk pengembalian
```

### 5.3 Branch Admin Mengelola Koleksi
```
AS A branch admin
I WANT TO menambah koleksi baru
SO THAT mahasiswa dapat meminjam buku tersebut

Acceptance Criteria:
- Dapat input data bibliografi
- Dapat upload cover image
- Dapat menambahkan multiple copies
- System generate call number otomatis
- System generate barcode otomatis
```

---

## 6. Data Requirements

### 6.1 Data Retention
| Data Type | Retention Period |
|-----------|------------------|
| Loan History | 5 tahun |
| Payment Records | 5 tahun |
| Activity Logs | 1 tahun |
| Member Data | Selama aktif + 5 tahun |

### 6.2 Data Backup
| Type | Frequency |
|------|----------|
| Full Backup | Daily |
| Incremental Backup | Hourly |
| Offsite Backup | Weekly |

---

*End of Functional Requirements Specification*
