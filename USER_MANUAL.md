# Panduan Pengguna Aplikasi Perpustakaan
## Sistem Informasi Perpustakaan Kampus

---

## Daftar Isi

1. [Pendahuluan](#pendahuluan)
2. [Login & Dashboard](#login--dashboard)
3. [Manajemen Anggota](#manajemen-anggota)
4. [Manajemen Koleksi](#manajemen-koleksi)
5. [Sirkulasi (Peminjaman & Pengembalian)](#sirkulasi-peminjaman--pengembalian)
6. [Reservasi](#reservasi)
7. [Laporan & Statistik](#laporan--statistik)
8. [Pengaturan](#pengaturan)

---

## Pendahuluan

Aplikasi Perpustakaan adalah sistem informasi lengkap untuk mengelola perpustakaan kampus, mencakup:
- Manajemen anggota (mahasiswa, dosen, staff)
- Manajemen koleksi (buku, jurnal, referensi)
- Sirkulasi (peminjaman, pengembalian, denda)
- Reservasi koleksi
- Laporan dan statistik
- Perpustakaan digital dan repository

### Role Pengguna

| Role | Keterangan |
|------|------------|
| **Super Admin** | Akses penuh ke semua fitur |
| **Branch Admin** | Mengelola cabang perpustakaan tertentu |
| **Circulation Staff** | Staff sirkulasi (peminjaman/pengembalian) |
| **Catalog Staff** | Staff katalog (manajemen koleksi) |
| **Report Viewer** | Hanya melihat laporan |
| **Member** | Anggota perpustakaan |

---

## Login & Dashboard

### Login

1. Buka URL aplikasi (misal: `https://library.univ.ac.id`)
2. Klik tombol **Login**
3. Masukkan **Email** dan **Password**
4. Klik **Masuk**

### Dashboard

Dashboard menampilkan ringkasan statistik:
- Total anggota aktif
- Total koleksi dan item
- Peminjaman aktif dan terlambat
- Reservasi pending
- Grafik tren sirkulasi 12 bulan

---

## Manajemen Anggota

### Menambah Anggota Baru

1. Buka menu **Anggota**
2. Klik tombol **+ Tambah Anggota**
3. Isi form:
   - **No. Anggota** - Nomor unik anggota
   - **Nama** - Nama lengkap
   - **Email** - Email aktif
   - **Tipe** - Mahasiswa/Dosen/Staff/Eksternal
   - **No. HP** - Nomor telepon
   - **Alamat** - Alamat lengkap
   - **Cabang** - Cabang perpustakaan
   - **Masa Berlaku** - Tanggal expired keanggotaan
4. Klik **Simpan**

### Memperbarui Anggota

1. Buka menu **Anggota**
2. Klik anggota yang akan diubah
3. Klik tombol **Edit**
4. Ubah data yang diperlukan
5. Klik **Update**

### Memperpanjang Keanggotaan

1. Buka detail anggota
2. Klik tombol **Perpanjang**
3. Pilih masa berlaku baru
4. Klik **Simpan**

### Menangguhkan Anggota

1. Buka detail anggota
2. Klik tombol **Tangguhkan**
3. Isi alasan penangguhan
4. Klik **Simpan**

---

## Manajemen Koleksi

### Menambah Koleksi Baru

1. Buka menu **Koleksi**
2. Klik **+ Tambah Koleksi**
3. Isi data koleksi:
   - **Judul** - Judul lengkap
   - **Penulis** - Nama penulis/pengarang
   - **Penerbit** - Penerbit
   - **Tahun** - Tahun terbit
   - **ISBN/ISSN** - Nomor ISBN/ISSN
   - **Tipe Koleksi** - Buku/Jurnal/Referensi
   - **Klasifikasi** - Kode DDC
   - **Subjek** - Kata kunci
   - **Ringkasan** - Abstrak/sinopsis
4. Klik **Simpan**

### Menambah Item (Copy Fisik)

Setelah koleksi dibuat, tambahkan item fisik:

1. Buka detail koleksi
2. Klik tab **Items**
3. Klik **+ Tambah Item**
4. Isi:
   - **Barcode** - Scan atau ketik barcode
   - **Cabang** - Lokasi item
   - **Call Number** - Nomor panggil
   - **Status** - Available/Damaged/Lost
5. Klik **Simpan**

### Import Koleksi (Bulk)

Untuk import banyak koleksi sekaligus:

1. Buka menu **Koleksi**
2. Klik **Import**
3. Upload file CSV/Excel dengan format:
   ```csv
   Judul,Penulis,Penerbit,Tahun,ISBN,Tipe,Barcode,Cabang
   ```
4. Klik **Import**

---

## Sirkulasi (Peminjaman & Pengembalian)

### Peminjaman Baru

1. Buka menu **Sirkulasi**
2. Klik **+ Peminjaman Baru**
3. Pilih **Anggota** dari dropdown atau scan kartu
4. Pilih **Item** dari dropdown atau scan barcode
5. Sistem otomatis menghitung:
   - Tanggal peminjaman
   - Tanggal jatuh tempo (berdasarkan aturan)
6. Klik **Proses Peminjaman**

### Pengembalian

1. Buka menu **Sirkulasi**
2. Cari peminjaman aktif
3. Klik tombol **Kembalikan** pada item yang dikembalikan
4. Sistem otomatis:
   - Mencatat tanggal pengembalian
   - Menghitung denda (jika terlambat)
   - Update status item menjadi Available

### Perpanjangan Peminjaman

1. Buka detail peminjaman
2. Klik **Perpanjang**
3. Sistem akan:
   - Cek batas perpanjangan
   - Update tanggal jatuh tempo baru
   - Catat jumlah perpanjaman

### Denda

Denda dihitung otomatis berdasarkan:
- **Tarif denda per hari** (di Pengaturan)
- **Hari keterlambatan** (termasuk hari libur jika diaktifkan)

Membayar denda:
1. Buka detail peminjaman dengan denda
2. Klik **Bayar Denda**
3. Masukkan jumlah pembayaran
4. Pilih metode pembayaran
5. Klik **Simpan**

---

## Reservasi

Anggota dapat memesan item yang sedang dipinjam.

### Membuat Reservasi

1. Buka menu **Reservasi**
2. Klik **+ Reservasi Baru**
3. Pilih anggota dan item
4. Tanggal kedatangan dihitung otomatis
5. Klik **Simpan**

### Memproses Reservasi

Saat item tersedia (dikembalikan):

1. Buka menu **Reservasi**
2. Cari reservasi dengan status **Pending**
3. Klik **Proses**
4. Pilih tanggal ambil
5. Notifikasi akan dikirim ke anggota

### Membatalkan Reservasi

1. Buka detail reservasi
2. Klik **Batalkan**
3. Isi alasan pembatalan
4. Klik **Simpan**

---

## Laporan & Statistik

### Dashboard Laporan

Menampilkan:
- Statistik real-time (anggota, koleksi, peminjaman)
- Grafik tren sirkulasi 12 bulan
- Distribusi koleksi berdasarkan tipe
- Distribusi anggota berdasarkan tipe
- Koleksi terpopuler

### Laporan Peminjaman

1. Buka menu **Laporan** → **Peminjaman**
2. Filter dengan **Rentang Tanggal**
3. Lihat statistik:
   - Total peminjaman
   - Peminjaman aktif/selesai
   - Rata-rata durasi peminjaman
4. Klik **Export CSV** untuk download

### Laporan Keterlambatan

1. Buka menu **Laporan** → **Keterlambatan**
2. Filter dengan **Rentang Tanggal**
3. Lihat:
   - Total keterlambatan
   - Total denda
   - Daftar peminjaman terlambat
4. Klik **Export CSV** untuk download

### Laporan Denda

1. Buka menu **Laporan** → **Pembayaran Denda**
2. Filter dengan **Rentang Tanggal**
3. Lihat:
   - Total pembayaran
   - Pembayaran per metode
4. Klik **Export CSV** untuk download

---

## Pengaturan

### Pengaturan Aplikasi

1. Buka menu **Pengaturan**
2. Edit konfigurasi:

| Pengaturan | Keterangan |
|------------|------------|
| Nama Perpustakaan | Nama instansi |
| Alamat | Alamat lengkap |
| Telepon/Email | Kontak |
| Denda per Hari | Tarif denda default |
| Max Perpanjangan | Batas perpanjangan |
| Hari Libur | Hitung denda termasuk libur |

### Pengaturan Aturan Peminjaman

1. Buka menu **Aturan Peminjaman**
2. Tambah/Edit aturan per tipe anggota dan tipe koleksi:
   - **Masa Peminjaman** - Hari
   - **Max Perpanjangan** - Kali
   - **Max Item** - Jumlah item yang bisa dipinjam
   - **Denda per Hari** - Rupiah

### Pengaturan Cabang

1. Buka menu **Cabang**
2. Tambah/Edit cabang:
   - Nama cabang
   - Alamat
   - Jam operasional
   - Status aktif/nonaktif

---

## Perpustakaan Digital

### Akses untuk User

Anggota dapat mengakses:
1. Buka menu **Perpustakaan Digital**
2. Cari berdasarkan judul, penulis, atau subjek
3. Klik file untuk download atau preview

### Upload File Baru (Staff)

1. Buka menu **Perpustakaan Digital** di admin
2. Klik **+ Upload File**
3. Pilih koleksi terkait (opsional)
4. Upload file (PDF, DOC, DOCX)
5. Isi metadata:
   - Judul
   - Deskripsi
   - Tipe akses (Public/Registered/Campus Only)
6. Klik **Simpan**

---

## Institutional Repository

### Submit Karya Ilmiah

Mahasiswa/dosen dapat submit:
- Skripsi/Tesis/Disertasi
- Jurnal ilmiah
- Paper konferensi
1. Buka menu **Repository**
2. Klik **+ Submit Karya**
3. Isi form:
   - Judul
   - Abstrak
   - Penulis
   - Dosen Pembimbing
   - Tipe dokumen
   - Upload file (PDF)
4. Klik **Submit**
5. Dokumen akan melalui proses moderasi

### Moderasi Repository (Admin)

1. Buka menu **Repository** di admin
2. Lihat daftar submit dengan status **Pending**
3. Review dokumen
4. Pilih aksi:
   - **Setujui** - Publish ke repository
   - **Tolak** - Tolak dengan alasan
   - **Arsipkan** - Arsipkan dokumen

---

## Tips & Shortcut

| Shortcut | Fungsi |
|----------|--------|
| **Ctrl/Cmd + K** | Quick search |
| **Ctrl/Cmd + N** | Tambah baru (di halaman index) |
| **Scan Barcode** | Gunakan barcode scanner untuk input cepat |
| **Enter** | Submit form (di halaman form) |

---

## FAQ

**Q: Bagaimana jika lupa password?**
A: Klik **Lupa Password** di halaman login. Link reset akan dikirim ke email.

**Q: Berapa lama masa peminjaman?**
A: Tergantung aturan peminjaman yang ditetapkan admin untuk tipe anggota dan tipe koleksi.

**Q: Apakah ada batas perpanjangan?**
A: Ya, batas perpanjaman ditentukan di aturan peminjaman.

**Q: Bagaimana cara reservasi item?**
A: Item yang sedang dipinjam dapat di-reservasi. Saat dikembalikan, notifikasi akan dikirim.

---

## Kontak & Support

Untuk bantuan teknis:
- **Email**: support@library.univ.ac.id
- **Telepon**: (021) 1234-5678
- **Jam Operasional**: Senin - Jumat, 08:00 - 16:00

---

*Versi Dokumen: 1.0*
*Tanggal Update: 2 Februari 2026*
