# API Kasir — Dokumentasi untuk Android App

Dokumentasi lengkap REST API untuk aplikasi kasir Android.  
Backend: Laravel 12 + Sanctum token auth.

---

## Konvensi Umum

| Hal | Nilai |
|-----|-------|
| Base URL | `http://<server>/api` |
| Format request | `Content-Type: application/json` |
| Format response | JSON |
| Auth | Bearer Token (Sanctum) |
| Semua harga | Integer (Rupiah, bukan desimal) |
| Semua tanggal | ISO 8601: `2024-01-15T10:30:00.000000Z` |

### Header Wajib (setelah login)
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

### Struktur Response Error Standar
```json
{
  "message": "Pesan error"
}
```

### Kode HTTP yang Digunakan
| Kode | Arti |
|------|------|
| `200` | OK — sukses GET |
| `201` | Created — sukses POST transaksi baru |
| `401` | Unauthenticated — token tidak ada / expired |
| `403` | Forbidden — bukan kasir |
| `404` | Not found |
| `422` | Validation error / business logic error |
| `500` | Server error |

---

## 1. Autentikasi

### 1.1 Login

> Endpoint publik, tidak perlu token.

```
POST /api/login
```

**Request Body:**
```json
{
  "email": "kasir@toko.com",
  "password": "password123"
}
```

**Response 200 — Sukses:**
```json
{
  "token": "1|abc123xyz...",
  "user": {
    "id": 5,
    "name": "Budi Kasir",
    "email": "kasir@toko.com",
    "role": "kasir",
    "gudang_id": 2,
    "gudang": {
      "id": 2,
      "nama": "Gudang Jakarta Timur",
      "kode": "JKT-TIM",
      "tipe": "toko",
      "lokasi": "Jl. Raya No. 10",
      "penanggung_jawab": "Pak Andi",
      "aktif": true
    }
  }
}
```

**Response 422 — Email/password salah:**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["Email atau password salah."]
  }
}
```

**Response 403 — User bukan kasir:**
```json
{
  "message": "Akses ditolak. Hanya kasir yang dapat login di aplikasi ini."
}
```

> **Simpan token** di SharedPreferences / Secure Storage. Token tidak punya expiry otomatis — hanya hangus saat logout.

---

### 1.2 Logout

```
POST /api/logout
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "message": "Logout berhasil."
}
```

> Setelah logout, hapus token dari storage. Token yang sudah di-logout tidak bisa dipakai lagi.

---

### 1.3 Info Kasir yang Sedang Login

```
GET /api/me
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "id": 5,
  "name": "Budi Kasir",
  "email": "kasir@toko.com",
  "role": "kasir",
  "gudang_id": 2,
  "gudang": {
    "id": 2,
    "nama": "Gudang Jakarta Timur",
    "kode": "JKT-TIM",
    "tipe": "toko",
    "lokasi": "Jl. Raya No. 10",
    "penanggung_jawab": "Pak Andi",
    "aktif": true
  }
}
```

---

## 2. Gudang

### 2.1 List Gudang

```
GET /api/gudang
Authorization: Bearer {token}
```

> Kasir biasa hanya dapat gudang miliknya sendiri (1 item).  
> Super admin dapat semua gudang aktif.

**Response 200:**
```json
{
  "data": [
    {
      "id": 2,
      "nama": "Gudang Jakarta Timur",
      "kode": "JKT-TIM",
      "tipe": "toko",
      "lokasi": "Jl. Raya No. 10",
      "penanggung_jawab": "Pak Andi",
      "aktif": true
    }
  ]
}
```

---

## 3. Produk

### 3.1 List Semua Produk

```
GET /api/produk
Authorization: Bearer {token}
```

**Query Params (opsional):**
| Param | Tipe | Contoh | Keterangan |
|-------|------|--------|------------|
| `kategori_id` | integer | `?kategori_id=3` | Filter by kategori |

> Hanya menampilkan produk dari gudang kasir yang login. Stok dihitung real-time.

**Response 200:**
```json
{
  "data": [
    {
      "id": 12,
      "merk": "Kaos Polos Premium",
      "ukuran": "L",
      "kategori": "Kaos",
      "kategori_id": 1,
      "harga_karton": 600000,
      "harga_satuan": 30000,
      "pcs_per_karton": 20,
      "stok_karton": 15,
      "total_pcs": 300,
      "foto": "http://server/storage/products/kaos-polos.jpg",
      "gudang_id": 2,
      "gudang": "Gudang Jakarta Timur",
      "is_active": true
    },
    {
      "id": 13,
      "merk": "Celana Jeans Slim",
      "ukuran": "32",
      "kategori": "Celana",
      "kategori_id": 2,
      "harga_karton": 1200000,
      "harga_satuan": 120000,
      "pcs_per_karton": 10,
      "stok_karton": 8,
      "total_pcs": 80,
      "foto": null,
      "gudang_id": 2,
      "gudang": "Gudang Jakarta Timur",
      "is_active": true
    }
  ]
}
```

**Field Penting:**
| Field | Keterangan |
|-------|------------|
| `harga_karton` | Harga per karton (satuan transaksi di POS) |
| `harga_satuan` | Harga per pcs (= harga_karton / pcs_per_karton) — untuk display |
| `stok_karton` | Stok tersedia dalam karton (real-time) |
| `total_pcs` | Stok tersedia dalam pcs (= stok_karton × pcs_per_karton) |
| `foto` | URL lengkap gambar, atau `null` jika tidak ada |

---

### 3.2 Search Produk

```
GET /api/produk/search?q={keyword}
Authorization: Bearer {token}
```

**Query Params:**
| Param | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `q` | string | Ya | Kata kunci — cari di merk dan ukuran |

**Contoh:**
```
GET /api/produk/search?q=kaos
GET /api/produk/search?q=L
GET /api/produk/search?q=jeans 32
```

**Response 200:** sama dengan List Produk (max 50 hasil)

**Response 422 — Query kosong:**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "q": ["The q field is required."]
  }
}
```

---

### 3.3 Detail Satu Produk

```
GET /api/produk/{id}
Authorization: Bearer {token}
```

**Response 200:** sama dengan item di List Produk

**Response 404:**
```json
{
  "message": "No query results for model [App\\Models\\Product]."
}
```

---

## 4. Transaksi

### 4.1 Riwayat Transaksi

```
GET /api/transaksi
Authorization: Bearer {token}
```

**Query Params (opsional):**
| Param | Tipe | Default | Keterangan |
|-------|------|---------|------------|
| `per_page` | integer | `20` | Jumlah item per halaman |
| `tanggal` | string | — | Filter tanggal: `2024-01-15` (YYYY-MM-DD) |
| `page` | integer | `1` | Halaman ke-n |

**Contoh:**
```
GET /api/transaksi?per_page=10&page=2
GET /api/transaksi?tanggal=2024-01-15
```

**Response 200:**
```json
{
  "data": [
    {
      "id": 101,
      "kode_transaksi": "TRX-A1B2C3D4",
      "kasir": "Budi Kasir",
      "gudang": "Gudang Jakarta Timur",
      "gudang_id": 2,
      "total_harga": 1800000,
      "total_bayar": 2000000,
      "kembalian": 200000,
      "status": "selesai",
      "metode_pembayaran": "tunai",
      "nama_pembeli": "Pak Hasan",
      "catatan": null,
      "items": [
        {
          "id": 201,
          "product_id": 12,
          "nama_produk": "Kaos Polos Premium",
          "ukuran": "L",
          "harga_satuan": 600000,
          "jumlah": 2,
          "subtotal": 1200000
        },
        {
          "id": 202,
          "product_id": 13,
          "nama_produk": "Celana Jeans Slim",
          "ukuran": "32",
          "harga_satuan": 1200000,
          "jumlah": 1,
          "subtotal": 600000
        }
      ],
      "created_at": "2024-01-15T09:30:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 20,
    "total": 98
  }
}
```

---

### 4.2 Detail Transaksi

```
GET /api/transaksi/{id}
Authorization: Bearer {token}
```

> Hanya bisa akses transaksi milik kasir sendiri. Transaksi kasir lain → 404.

**Response 200:** sama dengan satu item di Riwayat Transaksi

**Response 404:** transaksi tidak ditemukan / bukan milik kasir ini

---

### 4.3 Buat Transaksi Baru (Checkout)

```
POST /api/transaksi
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "nama_pembeli": "Pak Hasan",
  "metode_pembayaran": "tunai",
  "total_bayar": 2000000,
  "catatan": "Minta nota",
  "items": [
    {
      "product_id": 12,
      "jumlah": 2
    },
    {
      "product_id": 13,
      "jumlah": 1
    }
  ]
}
```

**Field Request:**
| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `nama_pembeli` | string | Ya | Nama pembeli, max 255 karakter |
| `metode_pembayaran` | string | Ya | Pilihan: `tunai`, `transfer`, `qris`, `debit`, `kredit` |
| `total_bayar` | integer | Ya | Uang yang diterima kasir (dalam Rupiah) |
| `catatan` | string | Tidak | Catatan opsional, max 500 karakter |
| `items` | array | Ya | Min 1 item |
| `items[].product_id` | integer | Ya | ID produk |
| `items[].jumlah` | integer | Ya | Jumlah karton yang dibeli, min 1 |

> **Catatan penting:** `jumlah` adalah jumlah **karton**, bukan pcs. Server otomatis hitung total harga dari `harga_karton × jumlah`. Tidak perlu kirim harga dari Android.

**Response 201 — Transaksi berhasil:**
```json
{
  "message": "Transaksi berhasil.",
  "data": {
    "id": 102,
    "kode_transaksi": "TRX-E5F6G7H8",
    "kasir": "Budi Kasir",
    "gudang": "Gudang Jakarta Timur",
    "gudang_id": 2,
    "total_harga": 1800000,
    "total_bayar": 2000000,
    "kembalian": 200000,
    "status": "selesai",
    "metode_pembayaran": "tunai",
    "nama_pembeli": "Pak Hasan",
    "catatan": "Minta nota",
    "items": [
      {
        "id": 203,
        "product_id": 12,
        "nama_produk": "Kaos Polos Premium",
        "ukuran": "L",
        "harga_satuan": 600000,
        "jumlah": 2,
        "subtotal": 1200000
      },
      {
        "id": 204,
        "product_id": 13,
        "nama_produk": "Celana Jeans Slim",
        "ukuran": "32",
        "harga_satuan": 1200000,
        "jumlah": 1,
        "subtotal": 600000
      }
    ],
    "created_at": "2024-01-15T10:45:00.000000Z"
  }
}
```

**Response 422 — Stok tidak cukup:**
```json
{
  "message": "Stok Kaos Polos Premium tidak cukup. Tersedia: 3 karton.",
  "product_id": 12
}
```

**Response 422 — Uang kurang:**
```json
{
  "message": "Uang diterima kurang dari total harga.",
  "total_harga": 1800000,
  "total_bayar": 1500000
}
```

**Response 422 — Kasir tidak punya gudang:**
```json
{
  "message": "Kasir tidak terikat gudang. Hubungi admin."
}
```

**Response 422 — Validasi field:**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "nama_pembeli": ["The nama pembeli field is required."],
    "metode_pembayaran": ["The selected metode pembayaran is invalid."],
    "items": ["The items field is required."]
  }
}
```

---

## 5. Alur Lengkap Android App

### Flow Login
```
1. POST /api/login  → dapat token + info user + gudang
2. Simpan token ke Secure Storage
3. Simpan gudang_id untuk filter produk
```

### Flow POS (Kasir Input Transaksi)
```
1. GET /api/produk              → load daftar produk (pada saat buka layar)
2. GET /api/produk/search?q=... → saat kasir ketik di search bar
3. [User pilih produk, tambah ke cart di lokal]
4. [User isi nama pembeli, metode bayar, nominal bayar]
5. POST /api/transaksi          → checkout
6. Tampilkan struk dari response data
```

### Flow Riwayat
```
1. GET /api/transaksi           → list transaksi hari ini
2. GET /api/transaksi?tanggal=2024-01-10  → filter tanggal
3. GET /api/transaksi/{id}      → detail + items untuk reprint struk
```

---

## 6. Skema Data (Kotlin Data Classes)

```kotlin
data class LoginRequest(
    val email: String,
    val password: String
)

data class LoginResponse(
    val token: String,
    val user: UserDto
)

data class UserDto(
    val id: Int,
    val name: String,
    val email: String,
    val role: String,
    val gudang_id: Int?,
    val gudang: GudangDto?
)

data class GudangDto(
    val id: Int,
    val nama: String,
    val kode: String,
    val tipe: String,
    val lokasi: String?,
    val penanggung_jawab: String?,
    val aktif: Boolean
)

data class ProdukDto(
    val id: Int,
    val merk: String,
    val ukuran: String?,
    val kategori: String?,
    val kategori_id: Int?,
    val harga_karton: Long,
    val harga_satuan: Long,
    val pcs_per_karton: Int,
    val stok_karton: Int,
    val total_pcs: Int,
    val foto: String?,
    val gudang_id: Int,
    val gudang: String?,
    val is_active: Boolean
)

data class TransaksiItemDto(
    val id: Int,
    val product_id: Int,
    val nama_produk: String,
    val ukuran: String?,
    val harga_satuan: Long,
    val jumlah: Int,
    val subtotal: Long
)

data class TransaksiDto(
    val id: Int,
    val kode_transaksi: String,
    val kasir: String?,
    val gudang: String?,
    val gudang_id: Int,
    val total_harga: Long,
    val total_bayar: Long,
    val kembalian: Long,
    val status: String,
    val metode_pembayaran: String,
    val nama_pembeli: String?,
    val catatan: String?,
    val items: List<TransaksiItemDto>,
    val created_at: String
)

data class CreateTransaksiRequest(
    val nama_pembeli: String,
    val metode_pembayaran: String,
    val total_bayar: Long,
    val catatan: String?,
    val items: List<TransaksiItemRequest>
)

data class TransaksiItemRequest(
    val product_id: Int,
    val jumlah: Int
)

data class PaginationDto(
    val current_page: Int,
    val last_page: Int,
    val per_page: Int,
    val total: Int
)
```

---

## 7. Penanganan Error di Android

```kotlin
// Intercept semua response error
when (response.code()) {
    401 -> {
        // Token expired / tidak valid
        // Hapus token, redirect ke layar login
    }
    403 -> {
        // Bukan kasir — seharusnya tidak terjadi jika login sudah divalidasi
        showError("Akses ditolak")
    }
    404 -> {
        showError("Data tidak ditemukan")
    }
    422 -> {
        val error = response.errorBody()?.string()
        // Parse JSON error.message untuk ditampilkan ke user
        showError(parseErrorMessage(error))
    }
    500 -> {
        showError("Server error. Coba lagi nanti.")
    }
}
```

---

## 8. Catatan Implementasi

### Stok
- `stok_karton` dihitung **real-time** dari database setiap kali di-fetch.
- **Jangan cache stok** di Android — selalu ambil dari API sebelum checkout.
- Saat buat transaksi, server re-validasi stok. Jika stok berubah antara load produk dan checkout → response 422.

### Harga
- Semua harga dalam **integer Rupiah**. Tidak ada desimal.
- `harga_karton` = harga per karton = satuan jual di POS.
- `harga_satuan` = harga per pcs (untuk display saja, bukan untuk transaksi).
- Server yang hitung total — Android cukup kirim `product_id` dan `jumlah`.

### Token
- Token tidak punya expiry otomatis. Simpan permanen sampai user logout.
- Cek validitas token dengan `GET /api/me` saat app dibuka.
- Jika `/api/me` return 401 → redirect ke login.

### Pagination
- Default 20 transaksi per halaman.
- Gunakan `pagination.last_page` untuk tahu apakah masih ada halaman berikutnya.
- Load lebih: `GET /api/transaksi?page=2`

### Metode Pembayaran
Nilai yang valid untuk field `metode_pembayaran`:
- `tunai`
- `transfer`
- `qris`
- `debit`
- `kredit`
