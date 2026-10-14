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

## Keamanan

Sudah diterapkan:
- Login dibatasi 5 kali gagal per username + IP (dikunci 5 menit), pesan error tidak membocorkan username.
- Proteksi CSRF, session di-regenerate saat login, sesi lain otomatis keluar saat password diganti.
- Password di-hash (bcrypt); password baru minimal 8 karakter berisi huruf dan angka.
- Semua isian divalidasi; teks selalu di-escape (anti-XSS); link hanya boleh `https://` atau `/...`.
- Upload hanya JPG/PNG/WEBP maks. 10 MB, nama file acak, dan eksekusi script di `public/uploads` diblokir.
- Gambar otomatis diperkecil & dikompres ke WebP (butuh ekstensi PHP `gd`; data EXIF/GPS ikut terhapus). Ukuran per menu diatur di `app/Http/Controllers/Admin/Concerns/HandlesUploads.php`.
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
