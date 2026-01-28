<x-mail::message>
# ⏰ Reservasi Akan Kadaluarsa

Halo {{ $member->name }},

Reservasi Anda untuk koleksi berikut akan **kadaluarsa dalam {{ $daysRemaining }} hari**.

## Detail Reservasi

| Informasi | Detail |
|-----------|--------|
| **Judul** | {{ $collection->title }} |
| **Lokasi** | {{ $branch->name }} |
| **Tanggal Kadaluarsa** | {{ $expiryDate }} |
| **Sisa Waktu** | {{ $daysRemaining }} hari |

## Tindakan yang Diperlukan

Untuk mempertahankan reservasi Anda, segera:
1. Datang ke perpustakaan **{{ $branch->name }}**
2. Ambil koleksi sebelum **{{ $expiryDate }}**

@if($daysRemaining <= 1)
---
**⚠️ PENTING:** Reservasi Anda akan kadaluarsa dalam waktu kurang dari 24 jam!
@endif

---

<x-mail::button>
{{ url('reservations.my') }}
</x-mail::button>

Jika Anda tidak lagi membutuhkan koleksi ini, silakan batalkan reservasi melalui halaman "Reservasi Saya".

Terima kasih,

Tim Perpustakaan
</x-mail::message>
