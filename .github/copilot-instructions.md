# Copilot Instructions — Project Tarbiyya

## Ringkasan

**Tarbiyya** adalah aplikasi manajemen informasi pesantren sekaligus kanal informasi bagi wali santri (mobile-first PWA). Dibangun di atas **CodeIgniter 4** (`codeigniter4/framework ^4.5`), **PHP 8.1+**, dan **MySQL**, dengan frontend SPA berbasis **Alpine.js + Pinecone Router**.

Tiga pilar arsitektur:

1. **Page-based routing** — routing dikelola lewat struktur folder di `app/Pages/` (mirip Next.js / Laravel Folio), **bukan** lewat `app/Config/Routes.php` (file itu sengaja kosong).
2. **Multi-tenant** — setiap pesantren memiliki database sendiri; database dipilih berdasarkan kode pesantren yang dikirim lewat request.
3. **SPA frontend** — halaman member dimuat asinkron sebagai fragment HTML (`getContent()`) + endpoint data JSON (`getSupply()`).

> Detail arsitektur, struktur folder, contoh kode, konvensi, dan workflow: baca **[`.github/ARCHITECTURE.md`](ARCHITECTURE.md)**.

## Batasan & Aturan Wajib

1. **Jangan mengubah `app/Config/Routes.php`** — routing berbasis folder `app/Pages/`. Setiap folder = satu route dengan `PageController.php`; method controller diawali verb HTTP (mis. `getContent()`, `getSupply()`, `postCheckIn()`).
2. **Jangan hardcode nama database** — selalu ambil koneksi DB pesantren via `(new \App\Libraries\Tarbiyya())->initDBPesantren()`; validasi auth memakai `checkToken()`.
3. **Build frontend WAJIB** setelah mengubah **file `script.js` mana pun** di `app/Pages/**`:
   ```bash
   cd public/mobilekit && npm run build
   ```
4. **Jangan mengubah folder `heroic/`** (vendor lokal paket ci4-pages) kecuali benar-benar diperlukan.
5. **Jangan commit key rahasia** — key di `app/Config/App.php` (`jwtKey`, `xendit*`, `recaptcha`) adalah development.
6. **Jangan berasumsi skema tabel** — verifikasi langsung ke DB pesantren. Contoh nyata: data video ada di `mein_posts` (`type='video'`, `embed_video` = ID YouTube), BUKAN di `mein_microblogs`.
7. **Hari Minggu (day_of_week)** harus simetris 0↔7 antara JS `getDay()` dan ISO `date('N')`; gunakan helper `dayOfWeekIn()`.
8. **`fetchPageData()` bisa mengembalikan `undefined`** saat error — selalu guard dengan optional chaining (`data?.data?...`), `.catch` saja tidak cukup.
9. Halaman member baru **extends `App\Pages\member\PageController`** (bukan langsung `BaseController`).
10. Ikuti gaya kode file di sekitarnya (CI4 umumnya tab, PSR-4, komentar mengikuti bahasa file tsb).
