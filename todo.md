# TODO - Asset IT (vristo-react-laravel-asset-it)

> **Status: DALAM PENGEMBANGAN** — Backend API + Migration + Seeder + Frontend sudah dibuat.

## Referensi Aplikasi PHP Lama
- Lokasi: `D:\laragon-ent\www\tujuh-dua\asset-it`

---

## Selesai

### Backend (Laravel API)
- [x] AuthController — Login, Register, Logout
- [x] BrandController — CRUD Brand
- [x] KategoriController — CRUD Kategori + Tipe
- [x] ManufakturController — CRUD Manufaktur
- [x] ModelProdukController — CRUD Model Produk (auto-create stock)
- [x] DepartemenController — CRUD Departemen
- [x] LokasiController — CRUD Lokasi
- [x] PenggunaController — CRUD Pengguna + Detail
- [x] AssetController — CRUD Asset + Checkout/Checkin + Upload + Export
- [x] AsesorisController — Stok Masuk/Keluar + Checkout/Checkin
- [x] InventoriController — Stok Masuk/Keluar + Checkout
- [x] KomponenController — Stok Masuk/Keluar + Checkout/Checkin
- [x] DashboardController — Statistik & Overview
- [x] LaporanController — Laporan Aktivitas
- [x] ProfilController — Profil, Password, Avatar

### Models (17 model)
- [x] Semua model sudah dibuat

### Database
- [x] Migration untuk semua tabel (22 migration files)
- [x] Seeder untuk data awal (7 seeder)

### Frontend (React)
- [x] Dashboard
- [x] Master Kategori
- [x] Master Manufaktur
- [x] Master Model Produk
- [x] Master Departemen
- [x] Master Lokasi
- [x] Master Pengguna
- [x] Transaksi Asset (CRUD + Checkout/Checkin)
- [x] Transaksi Asesoris (Stok Masuk/Keluar)
- [x] Transaksi Inventori (Stok Masuk/Keluar)
- [x] Transaksi Komponen (Pasang/Lepas dari Aset)
- [x] Laporan Aktivitas

---

## Belum Selesai

### Konfigurasi
- [ ] Jalankan `php artisan migrate`
- [ ] Jalankan `php artisan db:seed`

### Sidebar Menu
- [ ] Update sidebar dengan menu baru

### Fitur Tambahan
- [ ] Profil User (halaman frontend)
- [ ] Export PDF
- [ ] Print View
- [ ] Permission/Role Access
- [ ] File Upload untuk Asset
