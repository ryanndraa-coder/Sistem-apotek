# 💊 Apotek Sehat — Web App PHP + MySQL (Laragon)

Sistem manajemen apotek lengkap berbasis PHP native, MySQL, dan phpMyAdmin. Siap dijalankan di **Laragon**.

## 📋 Fitur

### Admin
- Dashboard statistik (users, obat, pesanan, pendapatan)
- CRUD User (admin/apoteker/pelanggan)
- CRUD Obat (stok, harga, kadaluarsa, status)
- Kelola Pesanan (ubah status, lihat detail)
- Kelola Pembayaran (lunas/belum/gagal)
- Kelola Pengiriman (diproses/dikirim/terkirim)

### Apoteker
- Dashboard konsultasi
- Buat & kelola Konsultasi dengan pelanggan
- Rekomendasi obat (multi-pilih)
- Lihat daftar obat

### Pelanggan
- Daftar sendiri (registrasi)
- Beli obat → otomatis buat pesanan, pengiriman, pembayaran
- Riwayat pesanan + detail (item, ongkir, pembayaran, pengiriman)
- Kelola profil + multi nomor telepon

## 🚀 Cara Install di Laragon

1. **Extract ZIP** ke `C:\laragon\www\apotek-web\`
2. **Start Laragon** (Apache + MySQL)
3. **Buka phpMyAdmin** → http://localhost/phpmyadmin
4. **Import database**: klik tab **Import** → pilih file `sql/apotek.sql` → **Go**
5. **Buka aplikasi**: http://localhost/apotek-web/

## 🔐 Akun Demo (password: `password123`)

| Role      | Email              |
|-----------|--------------------|
| Admin     | admin@apotek.com   |
| Apoteker  | budi@apotek.com    |
| Apoteker  | siti@apotek.com    |
| Pelanggan | andi@mail.com      |
| Pelanggan | rina@mail.com      |

## 🗄️ Struktur Database

11 tabel sesuai ERD:
- `user` (supertype) → `admin` / `apoteker` / `pelanggan` (subtype)
- `pelanggan_no_telp` (multivalued attribute)
- `obat`, `konsultasi`, `detail_konsultasi`
- `pesanan`, `detail_pesanan`, `pengiriman`, `pembayaran`

Semua relasi menggunakan **Foreign Key** dengan `ON DELETE CASCADE` sesuai kebutuhan.

## ⚙️ Konfigurasi

Edit `config/database.php` jika kredensial MySQL Anda berbeda:
```php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'apotek';
```
