<x-mail::message>
# 📋 Reservasi Dibatalkan

Halo {{ $member->name }},

Reservasi Anda untuk koleksi berikut telah **dibatalkan**.

## Detail Reservasi

| Informasi | Detail |
|-----------|--------|
| **Judul** | {{ $collection->title }} |

@if($reason)
**Alasan Pembatalan:**
{{ $reason }}
@endif

---

## Informasi

Jika pembatalan bukan inisiatif Anda dan Anda ingin melakukan reservasi kembali, silakan:

1. Cek ketersediaan koleksi di OPAC
2. Lakukan reservasi baru jika koleksi masih dipinjam

<x-mail::button>
Cek Ketersediaan
</x-mail::button>

---

Jika Anda memiliki pertanyaan, hubungi staf perpustakaan kami.

Terima kasih,

Tim Perpustakaan
</x-mail::message>
