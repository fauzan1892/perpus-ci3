# Sistem Informasi Perpustakaan

Aplikasi web untuk mengelola data buku, anggota, peminjaman, pengembalian, dan denda. Aplikasi dibangun dengan CodeIgniter 3 dan AdminLTE.

## Produk resmi

Source code pada repository ini tersedia secara gratis untuk dipelajari dan dikembangkan.
Versi premium resmi tersedia di [Source Code Aplikasi Sistem Informasi Perpustakaan berbasis Website Premium](https://www.codekop.com/products/source-code-aplikasi-sistem-informasi-perpustakaan-berbasis-website-5.html)
 dengan harga promo **Rp350.000**.

## Kebutuhan sistem

- PHP 8.2 atau lebih baru
- MySQL/MariaDB dengan ekstensi `mysqli`
- Ekstensi PHP `mbstring`, `fileinfo`, `gd`, dan `openssl`
- Apache dengan `mod_rewrite`, atau web server lain yang dikonfigurasi untuk meneruskan request ke `index.php`

Versi framework yang digunakan:

- CodeIgniter 3.1.10
- AdminLTE 2.4.5

## Instalasi

1. Salin repository ke document root web server.
2. Buat database `projek_perpus`, lalu import satu-satunya dump database aplikasi:

   ```bash
   mariadb -u root -p projek_perpus < Database/projek_perpus.sql
   ```

4. Sesuaikan koneksi database di `application/config/database.php`.
5. Pastikan direktori berikut dapat ditulis oleh PHP:

   - `application/cache`
   - `application/cache/sessions`
   - `application/logs`
   - `assets/image`
   - `assets/image/buku`

6. `base_url` sudah dibuat dinamis dan otomatis mengikuti protokol, host, port, serta folder aplikasi dari URL yang sedang digunakan. Tidak perlu mengatur environment variable atau `.env`.

   Jika perlu, pengaturan URL tersebut dapat dilihat di `application/config/config.php`. Kunci enkripsi tetap diatur langsung di file yang sama:

   ```php
   $config['encryption_key'] = 'ganti-dengan-kunci-acak-yang-panjang';
   ```

   Gunakan kunci enkripsi acak yang panjang dan stabil untuk instalasi tersebut.

7. Buka aplikasi melalui URL yang digunakan saat mengakses aplikasi. `base_url` akan mengikuti URL tersebut secara otomatis.

Untuk pengujian lokal sederhana, aplikasi dapat dijalankan dengan:

```bash
php -S 127.0.0.1:8099
```

Kemudian buka `http://127.0.0.1:8099/index.php/login`.

## Akun seed development

Database contoh menyediakan akun berikut. Segera ganti password setelah instalasi.

| Peran | Username | Password |
|---|---|---|
| Petugas perpustakaan | `anang` | `secret` |
| Anggota perpustakaan | `fauzan` | `secret` |

Password disimpan menggunakan bcrypt. MD5 tidak lagi diterima oleh proses login; akun lama yang masih memakai MD5 perlu di-reset password-nya.

User yang di-soft-delete tidak akan ditampilkan dan tidak dapat login kembali.

## Fitur transaksi

- Tanggal jatuh tempo dihitung berdasarkan tanggal pinjam dan lama pinjam.
- Hari keterlambatan dihitung menggunakan kalender, sehingga pergantian bulan dan tahun tetap akurat.
- Denda dihitung dengan rumus:

  ```text
  hari terlambat × jumlah buku × tarif denda per hari
  ```

- Satu transaksi tidak dapat dikembalikan dua kali atau menghasilkan denda ganda.
- Penghapusan buku, rak, kategori, user, pinjaman, dan denda menggunakan soft delete.
- Buku tanpa sampul menggunakan `assets/image/default-book.svg`, sedangkan user tanpa foto menggunakan `assets/image/default-user.svg`.

## Keamanan

- CSRF protection CodeIgniter diaktifkan pada seluruh form POST.
- Query login menggunakan Query Builder dan password diverifikasi dengan `password_verify()`.
- Request AJAX `POST` otomatis menyertakan token CSRF.
- Upload memakai whitelist tipe file, pemeriksaan MIME, batas ukuran, nama file acak, dan aturan `.htaccess` untuk mencegah eksekusi PHP di direktori upload.
- Jangan mengaktifkan `display_errors` di production.
- Gunakan HTTPS di production dan isi `base_url` dengan URL HTTPS yang benar.

## Changelog

### PHP 8.2 compatibility

- Menambahkan kompatibilitas CodeIgniter 3 dengan PHP 8.2, termasuk deklarasi properti inti yang sebelumnya dibuat secara dinamis.
- Menambahkan kompatibilitas return type pada session driver CodeIgniter.
- Memperbaiki konfigurasi session file agar menggunakan `application/cache/sessions`.
- Menghapus ketergantungan environment variable; `base_url` dibuat dinamis dari request aktif dan `encryption_key` diatur langsung pada `application/config/config.php`.
- Password menggunakan bcrypt melalui `password_hash()` dan diverifikasi dengan `password_verify()`; MD5 tidak lagi digunakan.
- Data buku, rak, kategori, user, transaksi pinjam, dan denda menggunakan soft delete melalui kolom `deleted_at`; user yang sudah dihapus tidak dapat login.
- Buku tanpa sampul dan user tanpa foto menggunakan aset SVG default yang aman.
- Memperketat upload file dengan whitelist MIME/ekstensi, nama file acak, pembatasan ukuran, dan pencegahan eksekusi script di folder upload.
- Merapikan perhitungan tanggal jatuh tempo, keterlambatan, dan denda pada proses peminjaman serta pengembalian.


## Fitur utama yang tersedia:

- Login dengan hak akses Petugas dan Anggota.
- Manajemen user/anggota, buku, kategori, dan rak.
- Upload sampul buku serta lampiran PDF dengan validasi file.
- Pencarian dan pengurutan data menggunakan jQuery DataTables.
- Keranjang peminjaman, transaksi peminjaman, dan pengembalian.
- Perhitungan tanggal jatuh tempo, keterlambatan, dan denda.
- Riwayat status buku yang sedang dipinjam dan sudah dikembalikan.
- Password bcrypt, CSRF protection, dan soft delete data penting.
- Kompatibel dengan PHP 8.2 dan CodeIgniter 3.

## Kontribusi

Kontribusi dipersilakan. Silakan buat branch, lakukan perubahan yang terukur, jalankan pengecekan sintaks, lalu ajukan Pull Request.

## Kredit

- Source awal: [Codekop](https://www.codekop.com/read/source-code-sistem-informasi-perpustakaan-dengan-codeigniter-3-61.html)
- Pengembang awal: [Fauzan Falah](https://fauzan.codekop.com/)
- Website: [Codekop.com](https://www.codekop.com/)
