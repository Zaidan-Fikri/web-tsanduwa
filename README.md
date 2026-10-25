# Website MTs. Darul Ulum Waru

Website resmi MTs. Darul Ulum Waru (Laravel 12) dengan tampilan dari `prototype1.html` dan panel admin untuk mengelola seluruh isi.

## Instalasi

```bash
composer install
cp .env.example .env        # lalu atur DB_DATABASE, DB_USERNAME, DB_PASSWORD
php artisan key:generate
php artisan migrate --seed  # membuat tabel + isi awal + akun superadmin
php artisan serve
```

- Website: `http://127.0.0.1:8000`
- Panel admin: `http://127.0.0.1:8000/admin/login`
- Akun awal: **superadmin** / **superadmin123** (segera ganti lewat menu *Akun Saya*)

## Struktur tampilan

| Bagian | Lokasi |
| --- | --- |
| Master layout publik | `resources/views/layouts/app.blade.php` |
| Top bar, navbar, footer, modal | `resources/views/partials/` |
| Halaman per menu | `resources/views/pages/` (`profil/`, `berita/`, `program`, `spmb`, dst.) |
| Layout & halaman admin | `resources/views/layouts/admin.blade.php`, `resources/views/admin/` |
| Komponen form admin | `resources/views/components/admin/` |
| CSS / JS | `public/css/site.css`, `public/js/site.js`, `public/css/admin.css`, `public/js/admin.js` |

Gambar dari panel admin disimpan di `public/uploads/` (tidak perlu `storage:link`).

## Tagihan SPP (menu Pembayaran)

- **Admin → Pembayaran → Upload Excel Tagihan**: upload file template *Data Tagihan Bulanan* (.xlsx, boleh beberapa file sekaligus). Setiap sheet = satu kelas; kolom tagihan (PPDB, Kls 7, Kls 8, SPP, Kekurangan SMT1, ...) dikenali otomatis dari judul kolom.
- Setelah upload muncul **preview** per kelas (baris error/peringatan ditandai). Data baru tersimpan setelah klik **Simpan**; upload berikutnya memperbarui tagihan siswa dengan NISN yang sama.
- Call center & rekening di bawah tabel Excel bisa langsung dipakai untuk **Info Pembayaran**.
- Wali murid membuka **Informasi → Pembayaran**, memasukkan **NISN + kode akses**, lalu melihat rincian tagihan.
- **Kode akses** (6 karakter, unik per siswa) dibuat otomatis saat siswa pertama kali diupload dan tidak berubah saat upload ulang. Cetak slip per kelas di **Tagihan Pembayaran Sekolah → Cetak Kode Akses**; kode bisa dibuat ulang di halaman Edit bila tersebar.
- Keamanan cek tagihan: pesan salah dibuat umum (tidak membocorkan NISN terdaftar), NISN dikunci 30 menit setelah 5x kode salah, dan pencarian dibatasi 10x/menit & 60x/hari per IP.
- File Excel berisi data siswa diabaikan Git (`*.xlsx` di `.gitignore`), jangan di-commit.

## Keamanan

Sudah diterapkan:
- Login dibatasi 5 kali gagal per username + IP (dikunci 5 menit), pesan error tidak membocorkan username.
- Proteksi CSRF, session di-regenerate saat login, sesi lain otomatis keluar saat password diganti.
- Password di-hash (bcrypt); password baru minimal 8 karakter berisi huruf dan angka.
- Semua isian divalidasi; teks selalu di-escape (anti-XSS); link hanya boleh `https://` atau `/...`.
- Upload hanya JPG/PNG/WEBP maks. 10 MB, nama file acak, dan eksekusi script di `public/uploads` diblokir.
- Gambar dikompres dua kali: di browser sebelum diupload, lalu di server ke WebP (butuh ekstensi PHP `gd`; data EXIF/GPS ikut terhapus). Ukuran per menu diatur di `app/Support/ImageOptimizer.php`.
- Semua gambar konten disimpan di **database** (tabel `media`) dan ditampilkan lewat `/media/{id}.webp`. Gambar lama (file `public/img` / link Unsplash) dipindahkan dengan `php artisan media:import`. Pastikan `max_allowed_packet` MySQL minimal 16M (XAMPP: `C:\xampp\mysql\bin\my.ini`).
- Header keamanan (CSP, X-Frame-Options, nosniff, dll.); halaman admin tidak di-cache & tidak diindeks.
- Menu *Pengguna Admin* hanya untuk peran Super Admin.

Sebelum online (production), ubah `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-madrasah.sch.id
ADMIN_PATH=kata-rahasia-anda      # alamat login jadi /kata-rahasia-anda/login
SESSION_SECURE_COOKIE=true        # jika sudah memakai HTTPS
```

lalu jalankan `php artisan config:cache && php artisan route:cache && php artisan view:cache`.
