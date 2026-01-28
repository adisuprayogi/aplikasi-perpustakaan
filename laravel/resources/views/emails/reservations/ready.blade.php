<x-mail::message>
# ✅ Koleksi Siap Diambil!

Halo {{ $member->name }},

Koleksi yang Anda reservasi sudah **siap diambil** di perpustakaan.

## Detail Koleksi

| Informasi | Detail |
|-----------|--------|
| **Judul** | {{ $collection->title }} |
| **Lokasi Pengambilan** | {{ $branch->name }} |
| **Batas Pengambilan** | {{ $expiryDate }} |

## Tindakan yang Diperlukan

Silakan datang ke perpustakaan **{{ $branch->name }}** untuk mengambil koleksi sebelum tanggal **{{ $expiryDate }}**.

Jangan lupa membawa:
- Kartu Anggota
- Kode Reservasi (jika diperlukan)

---

Catatan: Jika koleksi tidak diambil hingga batas tanggal, reservasi akan otomatis dibatalkan.

<x-mail::button>
{{ url('reservations.my') }}
</x-mail::button>

Terima kasih telah menggunakan perpustakaan kami!

Tim Perpustakaan
</x-mail::message>
