# Architecture — Project Tarbiyya

Dokumen ini berisi detail arsitektur, struktur project, contoh kode, dan hal teknis lain dari **Tarbiyya**.
Untuk ringkasan singkat dan batasan project, lihat [`.github/copilot-instructions.md`](copilot-instructions.md).

## 1. Tech Stack

| Lapisan | Teknologi |
|---|---|
| Backend | CodeIgniter 4 (`codeigniter4/framework ^4.5`), PHP 8.1+ |
| Routing kustom | `yllumi/ci4-pages` — di-vendor lokal di folder `heroic/` (composer `psr-4`: `Yllumi\Ci4Pages\` → `heroic/src/`) |
| PWA / Alpine | `yllumi/heroic` (di `vendor/`, namespace `Yllumi\Heroic\`) |
| Frontend | Alpine.js 3, Pinecone Router, Bootstrap 5, axios, Leaflet, Swiper, webpack |
| Auth | JWT (`firebase/php-jwt`, HS256) |
| Password member | Phpass (hash gaya WordPress) |
| Payment | Xendit (development key) |
| Testing | PHPUnit 9 (CI4 testing), `composer test` |

## 2. Struktur Folder Kunci

```
app/
├── Config/                  # Konfigurasi CI4 + kustom (App.php, SidebarMenu.php, Email.php, ...)
├── Controllers/
│   └── BaseController.php   # Base seluruh controller (helpers: pageview)
├── Libraries/               # Tarbiyya, Tenant, Payment, Phpass, Transaction, LatteFileLoader, ...
├── Models/                  # Pesantren, Checkout
├── Pages/                   # ⭐ Halaman / routing (folder = route)
│   ├── Router.php           #   Daftar route top-level (diproses renderRouter())
│   ├── layout.php           #   Layout shell SPA global
│   ├── BaseController.php   #   Base page controller (extends HeroicController)
│   ├── home/  dashboard/  whatsnext/  offline/  notfound/
│   ├── member/              # ⭐ Aplikasi member (SPA)
│   │   ├── router.php           #   Daftar route Alpine (x-route / x-template)
│   │   ├── layout.php           #   Layout member (extend template/layout_member)
│   │   ├── PageController.php   #   Base controller semua halaman member
│   │   ├── login/ register/ reset_password/ profile/ home/ ...
│   │   ├── uangsaku/ santri/ tagihan/ pengumuman/ program_pesantren/ feeds/ videos/ kajian/
│   │   ├── checkin/ (+checkin/rekap/)   # presensi karyawan
│   │   └── ...
│   └── _components/         # komponen yang di-include (bottommenu, ...)
├── Views/
│   ├── layouts/default.php
│   └── template/layout_member.php   # Shell HTML member (mobilekit)
├── Database/                # Migrations, Seeds
heroic/                      # vendor lokal ci4-pages (⚠️ jangan diedit sembarangan)
public/
├── index.php
├── mobilekit/               # ⭐ Frontend: webpack.config.js, helpers.js, assets/
│   └── assets/js/           #   OUTPUT build (pagescript.js, helpers.bundle.js)
└── admin/                   # panel admin terpisah
requests/                    # contoh request API (.request)
tests/                       # PHPUnit
```

## 3. Konsep Kunci

### 3.1 Page-Based Routing
- `app/Config/Routes.php` sengaja **kosong**; routing ditangani `Yllumi\Ci4Pages\PageRouter` (`heroic/src/PageRouter.php`).
- Setiap folder di `app/Pages/` = satu route. Contoh: `app/Pages/member/uangsaku` → URL `/member/uangsaku`.
- Wajib ada `PageController.php`; view fragment di folder yang sama dipanggil via `pageView('member/uangsaku/index', $data)`.
- Method controller diawali **verb HTTP + nama aksi PascalCase**: `getIndex()`, `getContent()`, `getSupply()`, `postIndex()`, `postCheckIn()`, dst. Segmen URI setelah folder route menjadi parameter method secara berurutan.
- Pola SPA member:
  - `getContent()` → fragment HTML (`pageView(...)`).
  - `getSupply()` → data JSON (`$this->respond(...)`).
- Endpoint yang memakai `?dataonly=1` (ditambahkan otomatis oleh `fetchPageData()`) tetap endpoint normal — param tsb dipakai sebagai penanda klien.

Contoh `PageController` halaman member:

```php
<?php namespace App\Pages\member\uangsaku;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/uangsaku/index', $this->data);
    }

    public function getSupply()
    {
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        $db = $Tarbiyya->initDBPesantren();

        $santri = $db->query("SELECT ... FROM md_student_user ...", ['user_id' => $user->user_id])->getResultArray();

        return $this->respond(['santri' => $santri]);
    }
}
```

### 3.2 Hirarki Controller
```
App\Controllers\BaseController   (helpers: pageview; data['themeURL'], data['title'])
  └── App\Pages\BaseController   (extends Yllumi\Heroic\Controllers\HeroicController;
                                  ResponseTrait; helpers: pageview + heroic)
        ├── App\Pages\home\PageController
        ├── App\Pages\member\PageController   (base semua halaman member)
        └── dst.
```
- `App\Pages\BaseController` dan seluruh controller page memakai `ResponseTrait` (untuk `$this->respond()`).
- `Yllumi\Heroic\Controllers\HeroicController` juga menyediakan `respondSecure()` (validasi same-origin + AJAX saat production).
- Halaman member baru sebaiknya extends `App\Pages\member\PageController` (bukan langsung BaseController) agar mendapat helper umum seperti `dayOfWeekIn()`.

### 3.3 Multi-tenant Database
- DB pusat: `tarbiyya` (tabel `tenants`, `tenant_logs`).
- DB per pesantren (contoh: `ppitarbiyya`, `benda`, `mipersis119`, `pajagalan`, `67benda`).
- Identifikasi pesantren:
  - **Header `Pesantrenku-Id`** → `hex(encrypter->encrypt(nama_db))` — sumber utama.
  - Fallback query param: `pid`, `pesantrenID`.
  - Resolusi: `App\Libraries\Tarbiyya::initDBPesantren()`.
- Method lain di `Tarbiyya`: `initDBTarbiyya()` (DB pusat), `initDBPondok()` (nilai pondok), `checkToken()`/`getUserToken()` (JWT), `sendWhatsapp()`, `sendEmail()`, `normalizePhoneNumber()`.
- Pola standar di controller:
  ```php
  $Tarbiyya = new \App\Libraries\Tarbiyya();
  $user = $Tarbiyya->checkToken();           // validasi JWT (401 & exit jika gagal)
  $db   = $Tarbiyya->initDBPesantren();      // koneksi DB pesantren
  // lalu $db->query(...) atau $db->table(...)
  ```

### 3.4 Autentikasi (JWT)
- Token: `Authorization: Bearer <jwt>` atau query param `authorization`.
- Encode/decode: `config('App')->jwtKey['secret']`, HS256.
- Payload: `user_id`, `role` (`role_slug`), `email`, `timestamp`.
- Password member di-hash dengan Phpass (`App\Libraries\Phpass::CheckPassword`).
- Alur login (contoh `member/login`): lookup `mein_users` (email/phone) → `CheckPassword` → `JWT::encode(...)` → respond `{ found, jwt, user }`.

### 3.5 Tabel DB Pesantren yang Sering Dipakai
- `mein_users`, `mein_roles`, `mein_options` (setting; option_group: `site`, `tarbiyya`, `pendaftaran`)
- `mein_posts` (artikel & video — `type='video'`, `embed_video` = ID YouTube), `mein_microblogs` (⚠️ sering kosong untuk video)
- `md_santri`, `md_student_user`, `md_class`, `md_student_class`
- `pres_*` (presensi/checkin): `pres_attendances`, `pres_office_locations`, `pres_employee_schedules`, `pres_unit_schedules`
- `pengumuman`, `menus` (menu YAML, mis. `tarbiyya-bottommenu`), `tenants`/`tenant_logs`

## 4. Frontend (mobilekit)

### 4.1 Pola Halaman Member (Alpine SPA)
- `app/Pages/member/router.php` mendefinisikan route dengan tag `<template>`:
  ```html
  <template
      x-route="/uangsaku/:nis"
      x-template.preload="['/member/uangsaku/detail/content', '/_components/bottommenu?pid=' + pesantrenID]"
      x-handler="isLoggedIn"
  ></template>
  ```
  - `x-handler` bisa array: `x-handler="[isKodePesantrenSet,isLoggedIn]"`.
- `script.js` di tiap folder page = komponen Alpine (mis. `Alpine.data("member_uangsaku", ...)`) yang memanggil `fetchPageData('member/uangsaku/supply')` saat `init()`.

### 4.2 ⚠️ Build WAJIB Setelah Ubah `script.js`
- Semua `app/Pages/**/script.js` dikompilasi webpack → `public/mobilekit/assets/js/pagescript.js`. Entry: `public/mobilekit/webpack.config.js`.
- Setelah mengedit **script.js apa pun**, WAJIB jalankan:
  ```bash
  cd public/mobilekit && npm run build
  ```
- `helpers.js` → `helpers.bundle.js`. Ada juga `npm run dev` (watch, development).
- Karena minifier mengganti nama variabel, verifikasi hasil build lewat literal string yang unik.

### 4.3 Helper Frontend (`public/mobilekit/helpers.js`)
- `fetchPageData(page)` — GET `page` + `?dataonly=1`, header `Authorization: Bearer <localStorage: heroic_token>` dan `Pesantrenku-Id: <localStorage: pesantrenID>`. **Resolve `undefined` saat error — selalu guard dengan `data?.data?...`; `.catch` saja TIDAK cukup.**
- `postPageData(page, data)` — POST FormData (dukung array/file/objek), header sama.
- `cachePageData` untuk cache per-halaman.

## 5. Konvensi Kode

- Method controller diawali verb HTTP; gunakan `$this->respond()` untuk JSON dan `pageView()` untuk view di dalam `app/Pages/`.
- Ambil DB pesantren selalu via `initDBPesantren()` — **jangan hardcode nama database**.
- Setting tampilan dibaca dari tabel `mein_options` DB pesantren; fallback ke `config('App')->...` bila perlu (mis. recaptcha).
- `asset_url('path/aset')` → URL aset dengan cache-busting filemtime (bukan `base_url()` langsung untuk file statis).
- `renderRouter(App\Pages\Router::$router)` untuk route shell; `heroic()` untuk menulis URL fetch berparam.
- Konfigurasi kustom ditambahkan di `app/Config/App.php` (`jwtKey`, `saungWA`, `recaptcha`, `xenditSecretKey`, `activePaymentMethods`, `defaultPage='member'`) atau file Config baru (mis. `SidebarMenu`).
- Indentasi ikuti gaya file di sekitarnya (CI4 umumnya memakai tab). Nama class/file mengikuti PSR-4.
- Bahasa komentar: pertahankan bahasa yang sudah dipakai file tsb (fitur member umumnya Bahasa Indonesia, kode framework Inggris).

## 6. Pitfalls & Catatan Penting (dari pengalaman nyata)

1. **Data video**: video sebenarnya ada di `mein_posts` (`type='video'`, `embed_video` berisi **ID** YouTube), BUKAN di `mein_microblogs` (hampir kosong). Sebelum berasumsi tabel mana yang dipakai suatu fitur, verifikasi langsung ke DB pesantren.
2. **Konvensi hari (day_of_week)**: server memakai `date('N')` (ISO: 1=Senin … 7=Minggu), sedangkan JS `getDay()` (0=Minggu … 6=Sabtu) dipakai admin panel. Pemetaan Minggu 0↔7 harus **simetris di mana-mana** (klausa `IN(...)` dan array PHP). Gunakan helper `dayOfWeekIn()` yang sudah ada di `App\Pages\member\PageController`.
3. **`fetchPageData` bisa mengembalikan `undefined`** saat error — selalu lindungi akses properti dengan optional chaining.
4. **Presensi/checkin**: multi-lokasi (`pres_office_locations`); validasi memilih lokasi terdekat dalam radius masing-masing. Rekap berbasis coverage (`pres_attendances.unit_schedule_id` vs `pres_unit_schedules`), fallback ke jumlah record bila unit tidak punya jadwal. Baca `app/Pages/member/checkin/PRD-Sistem-Presensi-Pesantren.md` sebelum mengubah fitur ini.
5. **Jangan mengubah `heroic/`** (vendor lokal ci4-pages) kecuali benar-benar diperlukan.
6. Key di `app/Config/App.php` adalah key **development** (Xendit/recaptcha dev) — jangan pakai di production, jangan commit key rahasia.
7. `.env` lokal memakai `app.baseURL='https://tarbiyya.test/'` dan kredensial `admin/admin` — jangan berasumsi kredensial production.

## 7. Perintah yang Sering Dipakai

```bash
# Build frontend (WAJIB setelah mengubah app/Pages/**/script.js)
cd public/mobilekit && npm run build

# Dev watch frontend
cd public/mobilekit && npm run dev

# Menjalankan test
composer test
# atau
./vendor/bin/phpunit

# Melihat daftar route
php spark routes

# Membuat halaman baru (dari paket ci4-pages)
php spark page:create nama_halaman
```

## 8. Workflow Menambah Fitur Halaman Member

1. Buat folder `app/Pages/member/<fitur>/` berisi `PageController.php`, `index.php` (fragment view), `script.js` (komponen Alpine).
2. Di `PageController`: `getContent()` → render fragment; `getSupply()` → JSON data (atau method lain sesuai verb).
3. Daftarkan route di `app/Pages/member/router.php` dengan `<template x-route=... x-template.preload=... x-handler=...>`.
4. Tulis komponen Alpine di `script.js` memakai `fetchPageData` / `postPageData`.
5. Jalankan `cd public/mobilekit && npm run build`.
6. Uji di `https://tarbiyya.test/member/...` (sesuai `.env`) dengan header `Pesantrenku-Id` yang valid.
