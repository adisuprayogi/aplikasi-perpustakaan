<x-mail::message>
@if($isOverdue)
# ⚠️ Peminjaman Terlambat!
@else
# 📅 Peminjaman Akan Jatuh Tempo
@endif

Halo {{ $member->name }},

@if($isOverdue)
Peminjaman koleksi Anda sudah **terlambat**.
@else
Peminjaman koleksi Anda akan **jatuh tempo** dalam {{ $daysUntilDue }} hari.
@endif

## Detail Peminjaman

| Informasi | Detail |
|-----------|--------|
| **Judul** | {{ $collection->title }} |
| **Tanggal Jatuh Tempo** | {{ $dueDate }} |

@if($isOverdue)
| **Keterlambatan** | {{ abs($daysUntilDue) }} hari |
@else
| **Sisa Waktu** | {{ $daysUntilDue }} hari |
@endif

@if($isOverdue && $calculatedFine > 0)
| **Denda** | Rp {{ number_format($calculatedFine, 0, ',', '.') }} |
@endif

## Tindakan yang Diperlukan

@if($isOverdue)
1. **Segera kembalikan koleksi** ke perpustakaan
2. Denda akan terus bertambah selama koleksi belum dikembalikan
3. Pembayaran denda dapat dilakukan saat pengembalian
@else
Harap kembalikan koleksi sebelum tanggal **{{ $dueDate }}** untuk menghindari denda keterlambatan.
@endif

---

<x-mail::button>
{{ url('reservations.my') }}
</x-mail::button>

Jika Anda ingin memperpanjang masa peminjaman, silakan hubungi perpustakaan (jika tidak ada denda dan belum melebihi batas perpanjaman).

Terima kasih,

Tim Perpustakaan
</x-mail::message>
