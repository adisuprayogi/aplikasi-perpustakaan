# Manual Aplikasi Perpustakaan
## Library Management System - User Guide

---

## Table of Contents

1. [Pendahuluan](#pendahuluan)
2. [Halaman Publik (OPAC)](#halaman-publik-opac)
3. [Login](#login)
4. [Dashboard Admin](#dashboard-admin)
5. [Manajemen Koleksi](#manajemen-koleksi)
6. [Manajemen Anggota](#manajemen-anggota)
7. [Sirkulasi (Peminjaman)](#sirkulasi-peminjaman)
8. [Laporan](#laporan)
9. [Perpustakaan Digital](#perpustakaan-digital)
10. [Repositori Institusi](#repositori-institusi)
11. [Pengaturan](#pengaturan)
12. [Tampilan Mobile](#tampilan-mobile)

---

## Pendahuluan

Aplikasi Perpustakaan adalah sistem manajemen perpustakaan terpadu yang mencakup:
- **OPAC (Online Public Access Catalog)** - Katalog publik untuk pencarian koleksi
- **Sirkulasi** - Manajemen peminjaman dan pengembalian
- **Manajemen Koleksi** - Pengelolaan buku dan item perpustakaan
- **Manajemen Anggota** - Data anggota dan keanggotaan
- **Laporan** - Berbagai laporan statistik dan aktivitas
- **Perpustakaan Digital** - File digital dan e-resources
- **Repositori** - Karya ilmiah dan publikasi institusi

---

## Halaman Publik (OPAC)

### Homepage OPAC

![OPAC Homepage](screenshots/01-opac-homepage.png)

Halaman utama OPAC menyediakan:
- Kolom pencarian cepat untuk koleksi
- Akses ke pencarian lanjut
- Link ke Perpustakaan Digital dan Repositori
- Informasi perpustakaan

### Pencarian Koleksi

![OPAC Search Results](screenshots/02-opac-search-results.png)

Hasil pencarian menampilkan:
- Daftar koleksi yang sesuai dengan kata kunci
- Informasi judul, penulis, dan penerbit
- Status ketersediaan item
- Nomor panggil (call number)

### Pencarian Lanjut

![OPAC Advanced Search](screenshots/03-opac-advanced-search.png)

Pencarian lanjut memungkinkan:
- Pencarian berdasarkan judul, penulis, penerbit
- Filter berdasarkan tipe koleksi
- Filter berdasarkan tahun terbit
- Kombinasi beberapa kriteria pencarian

---

## Perpustakaan Digital

![Perpustakaan Digital](screenshots/04-digital-library.png)

Perpustakaan Digital menyediakan:
- Akses ke file PDF, e-book, dan dokumen digital
- Pencarian dokumen digital
- Download dan preview dokumen
- Kategorisasi berdasarkan subjek

---

## Repositori Institusi

![Repositori](screenshots/05-repository.png)

Repositori Institusi berisi:
- Karya ilmiah dosen dan mahasiswa
- Skripsi, tesis, dan disertasi
- Jurnal dan prosiding
- Publikasi penelitian

---

## Login

### Halaman Login

![Halaman Login](screenshots/06-login-page.png)

Untuk login ke sistem admin:
1. Masukkan email terdaftar
2. Masukkan password
3. Klik tombol "Masuk"

**Akun Demo:**
- Email: `admin@kampus.ac.id`
- Password: `password`

---

## Dashboard Admin

![Dashboard](screenshots/07-dashboard.png)

Dashboard menampilkan ringkasan aktivitas perpustakaan:
- Total anggota terdaftar
- Total koleksi perpustakaan
- Peminjaman yang aktif
- Item yang terlambat dikembalikan

### Statistik Dashboard

![Statistik Dashboard](screenshots/08-dashboard-stats.png)

Kartu statistik memberikan:
- Angka real-time untuk setiap metrik
- Warna indikator (hijau=normal, kuning=peringatan, merah=masalah)
- Link langsung ke detail

### Grafik Tren

![Grafik Dashboard](screenshots/09-dashboard-charts.png)

Grafik interaktif menampilkan:
- Tren sirkulasi bulanan
- Perbandingan peminjaman vs pengembalian
- Koleksi terpopuler
- Distribusi jenis koleksi

---

## Manajemen Koleksi

### Daftar Koleksi

![Daftar Koleksi](screenshots/10-collections-list.png)

Halaman koleksi menampilkan:
- Daftar semua koleksi buku
- Fitur pencarian dan filter
- Tombol tambah koleksi baru
- Aksi edit dan hapus

### Pencarian Koleksi

![Pencarian Koleksi](screenshots/11-collections-search.png)

Hasil pencarian koleksi:
- Highlight kata kunci yang dicari
- Filter berdasarkan kategori
- Sortir berdasarkan berbagai kriteria

### Tambah Koleksi Baru

![Tambah Koleksi](screenshots/12-collections-create.png)

Form tambah koleksi mencakup:
- Informasi judul dan penulis
- Data penerbit dan tahun terbit
- ISBN dan ISSN
- Klasifikasi dan subjek
- Upload sampul buku

---

## Manajemen Anggota

### Daftar Anggota

![Daftar Anggota](screenshots/13-members-list.png)

Halaman anggota menampilkan:
- Daftar semua anggota terdaftar
- Filter berdasarkan tipe anggota
- Status keanggotaan (aktif/tidak aktif)
- Masa berlaku keanggotaan

### Filter Anggota

![Filter Anggota](screenshots/14-members-filter.png)

Filter anggota berdasarkan:
- Tipe anggota (mahasiswa, dosen, staff)
- Status aktif/non-aktif
- Fakultas/jurusan
- Masa berlaku

### Tambah Anggota Baru

![Tambah Anggota](screenshots/15-members-create.png)

Form tambah anggota:
- Nomor anggota (otomatis/unik)
- Nama lengkap dan email
- Tipe anggota
- Informasi kontak
- Masa berlaku keanggotaan

---

## Sirkulasi (Peminjaman)

### Daftar Peminjaman

![Daftar Peminjaman](screenshots/16-loans-list.png)

Halaman sirkulasi menampilkan:
- Daftar semua transaksi peminjaman
- Filter berdasarkan status (aktif, dikembalikan, terlambat)
- Tanggal jatuh tempo
- Denda (jika ada)

### Buat Peminjaman Baru

![Buat Peminjaman](screenshots/18-loans-create.png)

Form peminjaman:
- Scan/pilih nomor anggota
- Scan/pilih barcode item
- Tanggal peminjaman (default hari ini)
- Perhitungan otomatis tanggal jatuh tempo

---

## Laporan

### Laporan Peminjaman

![Laporan Peminjaman](screenshots/19-reports-loans.png)

Laporan statistik peminjaman:
- Total peminjaman per periode
- Peminjaman per jenis koleksi
- Peminjaman per anggota

### Laporan Keterlambatan

![Laporan Keterlambatan](screenshots/20-reports-overdue.png)

Laporan item terlambat:
- Daftar item melewati jatuh tempo
- Total hari keterlambatan
- Perhitungan denda

### Laporan Denda

![Laporan Denda](screenshots/21-reports-fines.png)

Laporan denda:
- Total denda terkumpul
- Denda per anggota
- Status pembayaran denda

### Laporan Koleksi

![Laporan Koleksi](screenshots/22-reports-collections.png)

Laporan statistik koleksi:
- Koleksi per kategori
- Koleksi per lokasi
- Pertumbuhan koleksi

### Laporan Anggota

![Laporan Anggota](screenshots/23-reports-members.png)

Laporan keanggotaan:
- Pertumbuhan anggota
- Distribusi tipe anggota
- Anggota aktif vs non-aktif

### Laporan Cabang

![Laporan Cabang](screenshots/24-reports-branches.png)

Perbandingan antar cabang:
- Statistik per cabang
- Distribusi koleksi
- Aktivitas sirkulasi

---

## File Digital

### Daftar File Digital

![Daftar File Digital](screenshots/29-digital-files-list.png)

Manajemen file digital:
- Daftar semua file yang diupload
- Kategori dan jenis file
- Ukuran file
- Download counter

### Upload File Digital

![Upload File Digital](screenshots/30-digital-files-create.png)

Form upload file:
- Pilih file dari komputer
- Input metadata (judul, penulis, tahun)
- Kategorisasi
- Akses publik/terbatas

---

## Repositori

### Daftar Repositori

![Daftar Repositori](screenshots/31-repositories-list.png)

Manajemen repositori:
- Daftar submission karya ilmiah
- Status review (pending, approved, rejected)
- Filter berdasarkan kategori

### Tambah Repositori

![Tambah Repositori](screenshots/32-repositories-create.png)

Form submission repositori:
- Upload file PDF
- Metadata lengkap (judul, penulis, abstrak)
- Kategori dan kata kunci
- Informasi publikasi

### Repositori Publik

![Repositori Publik](screenshots/33-repository-public.png)

Tampilan publik repositori:
- Pencarian karya ilmiah
- Filter berdasarkan kategori
- Download full-text

---

## Pengaturan

### Daftar Cabang

![Daftar Cabang](screenshots/25-branches-list.png)

Manajemen cabang perpustakaan:
- Tambah/edit/hapus cabang
- Lokasi dan kontak
- Jam operasional

### Aturan Peminjaman

![Aturan Peminjaman](screenshots/26-loan-rules.png)

Konfigurasi aturan:
- Masa pinjam per tipe anggota
- Masa pinjam per tipe koleksi
- Batas jumlah pinjaman
- Perpanjangan pinjaman

### Pengaturan Sistem

![Pengaturan Sistem](screenshots/27-settings.png)

Pengaturan aplikasi:
- Informasi perpustakaan
- Konfigurasi denda
- Pengaturan email
- Pengaturan OPAC

### Manajemen Pengguna

![Daftar Pengguna](screenshots/28-users-list.png)

Manajemen user sistem:
- Tambah/edit user admin/staff
- Assign role dan permission
- Status aktif user

---

## Tampilan Mobile

### OPAC Mobile

![OPAC Mobile](screenshots/34-mobile-opac-home.png)

Tampilan OPAC yang responsif:
- Navigasi mobile-friendly
- Pencarian cepat
- Akses mudah dari smartphone

### Digital Library Mobile

![Digital Library Mobile](screenshots/35-mobile-digital-library.png)

Akses perpustakaan digital dari mobile:
- Browse file digital
- Download langsung ke device
- Preview dokumen

### Dashboard Mobile

![Dashboard Mobile](screenshots/36-mobile-dashboard.png)

Dashboard admin mobile:
- Ringkasan statistik
- Quick actions
- Navigasi hamburger menu

### Koleksi Mobile

![Koleksi Mobile](screenshots/37-mobile-collections.png)

Daftar koleksi tampilan mobile:
- Scroll horizontal
- Quick actions
- Detail view

### Peminjaman Mobile

![Peminjaman Mobile](screenshots/38-mobile-loans.png)

Sirkulasi dari mobile:
- Scan barcode dengan kamera
- Quick checkout
- Status peminjaman

---

## Support dan Bantuan

Untuk bantuan teknis atau pertanyaan:
- **Email:** support@library.example.com
- **Telepon:** (021) 1234-5678
- **Jam Operasional:** Senin - Jumat, 08:00 - 16:00

---

## Changelog

### Versi 1.0.0
- Rilis awal sistem manajemen perpustakaan
- Modul OPAC dan Sirkulasi
- Manajemen Koleksi dan Anggota
- Laporan dan Statistik
- Perpustakaan Digital dan Repositori
- Tampilan Mobile Responsif

---

*Dokumen ini dibuat otomatis oleh sistem dokumentasi Playwright Automation*
*Last Updated: 3 Februari 2026*
