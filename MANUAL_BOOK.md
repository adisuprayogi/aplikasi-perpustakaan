# MANUAL BOOK
## Aplikasi Perpustakaan Digital

---

---

## Daftar Isi

1. [Pendahuluan](#pendahuluan)
2. [Halaman Publik](#halaman-publik)
   - [OPAC (Online Public Access Catalog)](#opac-online-public-access-catalog)
   - [Digital Library](#digital-library)
   - [Repository](#repository)
   - [Halaman Login](#halaman-login)
3. [Dashboard Admin](#dashboard-admin)
4. [Manajemen Koleksi](#manajemen-koleksi)
5. [Manajemen Anggota](#manajemen-anggota)
6. [Sirkulasi & Peminjaman](#sirkulasi--peminjaman)
7. [Laporan](#laporan)
8. [Pengaturan & Admin](#pengaturan--admin)
9. [Tampilan Mobile](#tampilan-mobile)

---

---

## Pendahuluan

Aplikasi Perpustakaan Digital adalah sistem manajemen perpustakaan terintegrasi yang mencakup:
- Katalog buku online (OPAC)
- Manajemen koleksi fisik dan digital
- Sistem sirkulasi (peminjaman & pengembalian)
- Manajemen anggota dan denda
- Laporan statistik dan analitik
- Repository institusi

---

---

## Halaman Publik

### OPAC (Online Public Access Catalog)

OPAC memungkinkan pengunjung untuk mencari dan menelusuri katalog koleksi perpustakaan.

![OPAC Homepage](screenshots/01-opac-homepage.png)

**Gambar 1: Halaman Beranda OPAC**

Fitur utama halaman OPAC:
- Search bar untuk pencarian cepat
- Akses ke pencarian lanjutan
- Navigasi ke Digital Library dan Repository

---

#### Pencarian OPAC

Pengguna dapat mencari koleksi berdasarkan judul, penulis, ISBN, atau kata kunci.

![OPAC Search Results](screenshots/02-opac-search-results.png)

**Gambar 2: Hasil Pencarian OPAC**

Hasil pencarian menampilkan:
- Daftar koleksi yang sesuai
- Informasi judul, penulis, dan kategori
- Status ketersediaan

---

#### Pencarian Lanjutan

Untuk pencarian lebih spesifik, gunakan fitur Advanced Search.

![OPAC Advanced Search](screenshots/03-opac-advanced-search.png)

**Gambar 3: Pencarian Lanjutan OPAC**

Filter pencarian lanjutan:
- Kategori/koleksi
- Bahasa
- Tahun terbit
- Penerbit

---

### Digital Library

Digital Library menyediakan akses ke koleksi digital seperti e-book, jurnal, dan artikel.

![Digital Library](screenshots/04-digital-library.png)

**Gambar 4: Halaman Digital Library**

Fitur:
- Browser kategori digital
- Download file digital
- Preview dokumen

---

### Repository

Repository menyimpan karya ilmiah, skripsi, tesis, dan publikasi institusi.

![Repository](screenshots/05-repository.png)

**Gambar 5: Halaman Repository**

Kategori repository:
- Skripsi
- Tesis
- Disertasi
- Jurnal
- Prosiding

---

### Halaman Login

Untuk mengakses fitur admin, staf harus login terlebih dahulu.

![Login Page](screenshots/06-login-page.png)

**Gambar 6: Halaman Login**

Kredensial default:
- Email: `admin@library.test`
- Password: `password123`

---

---

## Dashboard Admin

Dashboard menampilkan ringkasan statistik dan visualisasi data perpustakaan.

![Dashboard](screenshots/07-dashboard.png)

**Gambar 7: Dashboard Admin**

---

### Statistik Dashboard

Ringkasan statistik penting ditampilkan dalam bentuk kartu:

![Dashboard Stats](screenshots/08-dashboard-stats.png)

**Gambar 8: Kartu Statistik Dashboard**

Statistik yang ditampilkan:
- Total Koleksi
- Total Anggota
- Peminjaman Aktif
- Denda Tertunda

---

### Grafik Dashboard

Visualisasi data untuk analisis tren dan pola:

![Dashboard Charts](screenshots/09-dashboard-charts.png)

**Gambar 9: Grafik Dashboard**

Grafik tersedia:
- Tren Sirkulasi (Line Chart)
- Koleksi berdasarkan Kategori (Doughnut Chart)
- Anggota berdasarkan Tipe (Doughnut Chart)
- Item Populer (Bar Chart)

---

---

## Manajemen Koleksi

Menu Koleksi mengelola semua item fisik di perpustakaan (buku, majalah, multimedia).

![Collections List](screenshots/10-collections-list.png)

**Gambar 10: Daftar Koleksi**

Fitur manajemen koleksi:
- Pencarian dan filter
- Import/Export data
- Manajemen item (copy)
- Label barcode
- Status ketersediaan

---

#### Tambah Koleksi Baru

Formulir untuk menambah koleksi baru ke sistem:

![Collections Create](screenshots/12-collections-create.png)

**Gambar 11: Form Tambah Koleksi**

Field yang diisi:
- Judul
- Penulis/Pengarang
- ISBN/ISSN
- Penerbit
- Tahun terbit
- Kategori
- Jumlah copy
- Lokasi rak

---

---

## Manajemen Anggota

Menu Anggota mengelola data anggota perpustakaan.

![Members List](screenshots/13-members-list.png)

**Gambar 12: Daftar Anggota**

Informasi anggota:
- Nama dan ID anggota
- Tipe anggota (Siswa, Guru, Staf)
- Status keanggotaan
- Masa berlaku

---

#### Tambah Anggota Baru

![Members Create](screenshots/15-members-create.png)

**Gambar 13: Form Tambah Anggota**

Data anggota:
- Informasi pribadi (nama, email, telepon)
- Tipe anggota
- Tanggal bergabung
- Batas peminjaman

---

---

## Sirkulasi & Peminjaman

Sistem sirkulasi mengelola peminjaman dan pengembalian koleksi.

![Loans List](screenshots/16-loans-list.png)

**Gambar 14: Daftar Peminjaman**

Status peminjaman:
- Aktif: Sedang dipinjam
- Kembali: Sudah dikembalikan
- Terlambat: Lewat jatuh tempo

---

#### Buat Peminjaman Baru

![Loans Create](screenshots/18-loans-create.png)

**Gambar 15: Form Peminjaman Baru**

Langkah peminjaman:
1. Pilih anggota
2. Scan/input barcode koleksi
3. Sistem hitung tanggal jatuh tempo
4. Konfirmasi peminjaman

---

---

## Laporan

Sistem menyediakan berbagai laporan untuk analisis dan audit.

### Laporan Peminjaman

![Reports Loans](screenshots/19-reports-loans.png)

**Gambar 16: Laporan Peminjaman**

Laporan peminjaman dapat difilter berdasarkan:
- Rentang tanggal
- Status peminjaman
- Kategori koleksi
- Tipe anggota

---

### Laporan Keterlambatan

![Reports Overdue](screenshots/20-reports-overdue.png)

**Gambar 17: Laporan Keterlambatan**

Laporan ini menampilkan:
- Anggota yang terlambat mengembalikan
- Hari keterlambatan
- Denda yang harus dibayar

---

### Laporan Denda

![Reports Fines](screenshots/21-reports-fines.png)

**Gambar 18: Laporan Denda**

Informasi denda:
- Total denda per anggota
- Status pembayaran
- Riwayat pembayaran

---

### Laporan Koleksi

![Reports Collections](screenshots/22-reports-collections.png)

**Gambar 19: Laporan Koleksi**

Statistik koleksi:
- Koleksi per kategori
- Koleksi tersedia vs dipinjam
- Koleksi rusak/hilang

---

### Laporan Anggota

![Reports Members](screenshots/23-reports-members.png)

**Gambar 20: Laporan Anggota**

Analisis anggota:
- Pertumbuhan anggota
- Anggota aktif vs tidak aktif
- Peminjaman per anggota

---

### Laporan Cabang

Untuk sistem multi-cabang:

![Reports Branches](screenshots/24-reports-branches.png)

**Gambar 21: Laporan Perbandingan Cabang**

Perbandingan performa antar cabang:
- Total anggota
- Peminjaman aktif
- Kunjungan
- Denda terkumpul

---

---

## Pengaturan & Admin

### Manajemen Cabang

![Branches List](screenshots/25-branches-list.png)

**Gambar 22: Daftar Cabang**

Untuk perpustakaan dengan multiple cabang/lokasi.

---

### Aturan Peminjaman

![Loan Rules](screenshots/26-loan-rules.png)

**Gambar 23: Aturan Peminjaman**

Konfigurasi aturan:
- Masa peminjaman per tipe anggota
- Batas item yang dapat dipinjam
- Denda per hari keterlambatan
- Masa perpanjangan

---

### Pengaturan Aplikasi

![Settings](screenshots/27-settings.png)

**Gambar 24: Pengaturan Aplikasi**

Konfigurasi sistem:
- Nama perpustakaan
- Alamat dan kontak
- Aturan default
- Pengaturan notifikasi

---

### Manajemen Pengguna

![Users List](screenshots/28-users-list.png)

**Gambar 25: Daftar Pengguna Sistem**

Manajemen akses:
- Administrator
- Staff
- Petugas
- Assign permissions

---

### File Digital

![Digital Files List](screenshots/29-digital-files-list.png)

**Gambar 26: Daftar File Digital**

Upload dan kelola file digital:
- E-book
- Jurnal
- Audio/Video

---

![Digital Files Create](screenshots/30-digital-files-create.png)

**Gambar 27: Upload File Digital**

---

### Repository

![Repositories List](screenshots/31-repositories-list.png)

**Gambar 28: Daftar Repository**

Kelola karya ilmiah dan publikasi.

---

![Repositories Create](screenshots/32-repositories-create.png)

**Gambar 29: Tambah Repository Baru**

---

![Repository Public](screenshots/33-repository-public.png)

**Gambar 30: Tampilan Repository Publik**

---

---

## Tampilan Mobile

Aplikasi responsif dan dapat diakses melalui berbagai perangkat mobile.

### OPAC Mobile

![Mobile OPAC](screenshots/34-mobile-opac-home.png)

**Gambar 31: OPAC Tampilan Mobile**

---

### Digital Library Mobile

![Mobile Digital Library](screenshots/35-mobile-digital-library.png)

**Gambar 32: Digital Library Tampilan Mobile**

---

### Dashboard Mobile

![Mobile Dashboard](screenshots/36-mobile-dashboard.png)

**Gambar 33: Dashboard Tampilan Mobile**

---

### Koleksi Mobile

![Mobile Collections](screenshots/37-mobile-collections.png)

**Gambar 34: Koleksi Tampilan Mobile**

---

### Peminjaman Mobile

![Mobile Loans](screenshots/38-mobile-loans.png)

**Gambar 35: Peminjaman Tampilan Mobile**

---

---

## Ringkasan Screenshot

| No | Screenshot | Deskripsi |
|---|-----------|-----------|
| 1 | 01-opac-homepage | Halaman beranda OPAC |
| 2 | 02-opac-search-results | Hasil pencarian OPAC |
| 3 | 03-opac-advanced-search | Pencarian lanjutan OPAC |
| 4 | 04-digital-library | Halaman Digital Library |
| 5 | 05-repository | Halaman Repository |
| 6 | 06-login-page | Halaman Login |
| 7 | 07-dashboard | Dashboard Admin |
| 8 | 08-dashboard-stats | Statistik Dashboard |
| 9 | 09-dashboard-charts | Grafik Dashboard |
| 10 | 10-collections-list | Daftar Koleksi |
| 11 | 12-collections-create | Tambah Koleksi |
| 12 | 13-members-list | Daftar Anggota |
| 13 | 15-members-create | Tambah Anggota |
| 14 | 16-loans-list | Daftar Peminjaman |
| 15 | 18-loans-create | Buat Peminjaman |
| 16 | 19-reports-loans | Laporan Peminjaman |
| 17 | 20-reports-overdue | Laporan Keterlambatan |
| 18 | 21-reports-fines | Laporan Denda |
| 19 | 22-reports-collections | Laporan Koleksi |
| 20 | 23-reports-members | Laporan Anggota |
| 21 | 24-reports-branches | Laporan Cabang |
| 22 | 25-branches-list | Daftar Cabang |
| 23 | 26-loan-rules | Aturan Peminjaman |
| 24 | 27-settings | Pengaturan Aplikasi |
| 25 | 28-users-list | Daftar Pengguna |
| 26 | 29-digital-files-list | Daftar File Digital |
| 27 | 30-digital-files-create | Upload File Digital |
| 28 | 31-repositories-list | Daftar Repository |
| 29 | 32-repositories-create | Tambah Repository |
| 30 | 33-repository-public | Repository Publik |
| 31 | 34-mobile-opac-home | OPAC Mobile |
| 32 | 35-mobile-digital-library | Digital Library Mobile |
| 33 | 36-mobile-dashboard | Dashboard Mobile |
| 34 | 37-mobile-collections | Koleksi Mobile |
| 35 | 38-mobile-loans | Peminjaman Mobile |

---

---

*Manual Book ini dibuat secara otomatis menggunakan Playwright Screenshot Automation*

*Dokumentasi ini mencakup 35 screenshot dari seluruh fitur Aplikasi Perpustakaan Digital*
