# Product Requirements Document

## Sistem Presensi Karyawan & Guru Berbasis Lokasi — Aplikasi Pesantren

**Versi Dokumen:** 1.0
**Tanggal:** 5 Juli 2026
**Status:** Draft
**Tech Stack Terkait:** Backend Webman (PHP), Frontend Alpine.js

---

## Daftar Isi

1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Latar Belakang & Masalah](#2-latar-belakang--masalah)
3. [Tujuan](#3-tujuan)
4. [Ruang Lingkup](#4-ruang-lingkup)
5. [Pengguna & Peran](#5-pengguna--peran)
6. [Kebutuhan Fungsional](#6-kebutuhan-fungsional)
7. [Logika Status Kehadiran](#7-logika-status-kehadiran)
8. [Model Data (Ringkasan)](#8-model-data-ringkasan)
9. [Alur Pengguna Utama](#9-alur-pengguna-utama)
10. [Kebutuhan Non-Fungsional](#10-kebutuhan-non-fungsional)
11. [Metrik Keberhasilan](#11-metrik-keberhasilan)
12. [Risiko & Mitigasi](#12-risiko--mitigasi)
13. [Pengembangan Lanjutan](#13-pengembangan-lanjutan-di-luar-fase-ini)
14. [Lampiran](#14-lampiran)

---

## 1. Ringkasan Eksekutif

Pesantren membutuhkan sistem presensi karyawan dan guru yang menggantikan pencatatan manual dengan validasi otomatis berbasis lokasi (geofencing). Karyawan hanya dapat melakukan check-in ketika berada dalam radius tertentu dari titik lokasi pesantren. Sistem ini juga mendukung jadwal kerja yang tidak seragam — terutama untuk guru yang jadwal mengajarnya tidak setiap hari — serta mengecualikan tanggal libur secara otomatis dari perhitungan ketidakhadiran.

Sistem terdiri dari dua sisi utama: aplikasi mobile untuk karyawan (check-in/out dan riwayat presensi pribadi) dan panel admin untuk mengelola jadwal kerja, kalender libur, serta meninjau rekap kehadiran seluruh karyawan.

## 2. Latar Belakang & Masalah

- Presensi manual sulit direkap secara konsisten dan rentan terhadap kesalahan pencatatan.
- Guru memiliki jadwal mengajar yang tidak seragam setiap hari, sehingga jadwal kerja tidak bisa disamaratakan untuk seluruh karyawan.
- Diperlukan cara memvalidasi kehadiran fisik karyawan tanpa menggunakan verifikasi foto/selfie.
- Tanpa pengecualian otomatis, karyawan yang tidak presensi pada hari libur berisiko tercatat sebagai tidak hadir/alpa.

## 3. Tujuan

- Memastikan presensi hanya dapat dilakukan ketika karyawan secara fisik berada di lokasi pesantren.
- Mendukung jadwal kerja yang bervariasi per individu, termasuk histori jadwal per periode (mis. per semester).
- Mengotomatiskan penentuan status kehadiran (hadir, terlambat, tidak hadir, libur, bukan hari kerja) tanpa intervensi manual admin.
- Menyediakan rekap dan riwayat kehadiran yang akurat dan mudah ditelusuri untuk keperluan administrasi.

## 4. Ruang Lingkup

### 4.1 Termasuk dalam Ruang Lingkup

- Presensi berbasis lokasi (check-in dan check-out) untuk karyawan/guru.
- Manajemen jadwal kerja per karyawan per periode, termasuk jam kehadiran opsional.
- Kalender hari libur dengan cakupan per unit.
- Rekap kehadiran harian untuk seluruh karyawan.
- Halaman detail riwayat dan statistik kehadiran per karyawan.

### 4.2 Tidak Termasuk dalam Ruang Lingkup Fase Ini

- Alur pengajuan izin/cuti formal dengan proses persetujuan berjenjang.
- Verifikasi kehadiran melalui foto/selfie.
- Integrasi langsung dengan sistem penggajian (payroll).
- Dukungan multi-cabang/multi-lokasi pesantren dalam satu akun karyawan.
- Notifikasi otomatis (push notification/WhatsApp) untuk keterlambatan atau ketidakhadiran.

## 5. Pengguna & Peran

### 5.1 Karyawan / Guru

Melakukan check-in dan check-out presensi melalui aplikasi mobile, serta melihat riwayat dan ringkasan kehadiran pribadi.

### 5.2 Admin (Tata Usaha / Kepegawaian)

Mengelola titik lokasi geofence, jadwal kerja setiap karyawan per periode, kalender hari libur, serta meninjau rekap dan detail kehadiran seluruh karyawan.

## 6. Kebutuhan Fungsional

### 6.1 Presensi Karyawan (Aplikasi Mobile)

| ID | Deskripsi Kebutuhan | Prioritas |
|---|---|---|
| FR-1 | Sistem mendeteksi lokasi karyawan secara real-time menggunakan Geolocation API browser. | Wajib |
| FR-2 | Sistem menghitung jarak karyawan terhadap titik lokasi pesantren menggunakan formula Haversine. | Wajib |
| FR-3 | Tombol "Absen Masuk" hanya aktif apabila jarak karyawan berada dalam radius yang dikonfigurasi admin. | Wajib |
| FR-4 | Jarak yang menentukan sah/tidaknya presensi divalidasi ulang di server, tidak hanya mengandalkan perhitungan di sisi klien. | Wajib |
| FR-5 | Sistem menampilkan indikator akurasi GPS dan peringatan apabila akurasi rendah. | Diharapkan |
| FR-6 | Karyawan dapat melakukan absen pulang setelah absen masuk tercatat pada hari yang sama. | Wajib |
| FR-7 | Karyawan dapat melihat riwayat presensi pribadinya beserta ringkasan bulanan. | Wajib |
| FR-8 | Proses presensi tidak menyertakan verifikasi foto/selfie, sesuai keputusan produk. | Wajib |

### 6.2 Manajemen Jadwal Kerja (Admin)

| ID | Deskripsi Kebutuhan | Prioritas |
|---|---|---|
| FR-9 | Admin dapat menentukan hari kerja per karyawan/guru secara individual (bukan satu jadwal seragam untuk semua). | Wajib |
| FR-10 | Admin dapat menetapkan jam kehadiran (jam masuk, toleransi keterlambatan, jam pulang opsional) per karyawan. | Wajib |
| FR-11 | Jika jam kehadiran tidak diset untuk seorang karyawan, sistem hanya mencatat status Hadir/Tidak Hadir tanpa status Terlambat. | Wajib |
| FR-12 | Admin dapat mengatur jam kehadiran yang berbeda untuk hari kerja yang berbeda pada satu karyawan yang sama. | Diharapkan |
| FR-13 | Jadwal kerja dikelola dalam satuan Periode Jadwal (mis. per semester); histori periode sebelumnya tetap tersimpan dan tidak tertimpa. | Wajib |
| FR-14 | Admin dapat menyalin jadwal dari karyawan lain sebagai titik awal sebelum disesuaikan. | Diharapkan |
| FR-15 | Admin dapat membuat periode jadwal baru tanpa memengaruhi data presensi pada periode yang sudah berlalu. | Wajib |

### 6.3 Kalender Hari Libur (Admin)

| ID | Deskripsi Kebutuhan | Prioritas |
|---|---|---|
| FR-16 | Admin dapat menambahkan tanggal atau rentang tanggal libur beserta kategorinya (nasional, pesantren, cuti bersama, lainnya). | Wajib |
| FR-17 | Cakupan hari libur dapat berlaku untuk seluruh karyawan atau dibatasi ke unit/bagian tertentu. | Wajib |
| FR-18 | Presensi pada tanggal libur otomatis berstatus "Libur" dan tidak dihitung sebagai tidak hadir, tanpa input manual per karyawan. | Wajib |

### 6.4 Rekap & Detail Kehadiran (Admin)

| ID | Deskripsi Kebutuhan | Prioritas |
|---|---|---|
| FR-19 | Admin dapat melihat rekap kehadiran seluruh karyawan pada tanggal tertentu, dengan default tanggal hari kerja aktif terkini. | Wajib |
| FR-20 | Rekap harian menampilkan status per karyawan: Hadir, Terlambat, Tidak Hadir, Libur, atau Bukan Hari Kerja. | Wajib |
| FR-21 | Admin dapat memfilter status dan mencari karyawan berdasarkan nama pada rekap harian. | Diharapkan |
| FR-22 | Admin dapat melihat detail riwayat dan statistik kehadiran per karyawan per periode, termasuk tampilan kalender bulanan. | Wajib |
| FR-23 | Halaman detail karyawan menampilkan daftar catatan ketidakhadiran (alpa/terlambat) secara terpisah untuk memudahkan audit. | Diharapkan |

## 7. Logika Status Kehadiran

Status kehadiran pada setiap tanggal ditentukan oleh sistem melalui urutan pemeriksaan berikut, dievaluasi di sisi server:

1. Periksa apakah tanggal tersebut termasuk hari libur (berlaku umum atau khusus unit karyawan). Jika ya, status menjadi **"Libur"**.
2. Jika bukan hari libur, periksa apakah karyawan dijadwalkan bekerja pada hari tersebut (berdasarkan jadwal periode yang aktif). Jika tidak dijadwalkan, status menjadi **"Bukan Hari Kerja"**.
3. Jika dijadwalkan namun tidak ada catatan check-in hingga batas waktu, status menjadi **"Tidak Hadir"**.
4. Jika terdapat catatan check-in:
   - Apabila jam kehadiran **tidak diset** untuk karyawan tersebut, status menjadi **"Hadir"**.
   - Apabila jam kehadiran **diset**, waktu check-in dibandingkan terhadap jam yang ditetapkan ditambah toleransi keterlambatan — tepat waktu menghasilkan status **"Hadir"**, melewati toleransi menghasilkan status **"Terlambat"**.

> **Catatan penting:** perhitungan jarak lokasi yang dilakukan di aplikasi mobile hanya berfungsi sebagai umpan balik visual instan bagi karyawan. Keabsahan presensi tetap ditentukan oleh perhitungan ulang di server.

## 8. Model Data (Ringkasan)

Rincian lengkap skema database (kolom, tipe data, relasi, dan indeks) didokumentasikan secara terpisah pada berkas `skema-database-presensi.sql`. Ringkasan tabel utama:

| Tabel | Fungsi |
|---|---|
| `units` | Daftar unit/bagian kerja (mis. Pengajaran, Tata Usaha, Keamanan), dipakai untuk pengelompokan dan cakupan libur. |
| `employees` | Data karyawan dan guru, termasuk unit dan status aktif. |
| `office_locations` | Titik lokasi geofence pesantren beserta radius absen yang berlaku. |
| `schedule_periods` | Periode berlakunya sebuah jadwal (mis. Semester Ganjil 2025/2026), menjaga histori jadwal lama. |
| `employee_schedules` | Hari kerja per karyawan per periode, beserta jam kehadiran opsional dan toleransi keterlambatan. |
| `holidays` | Kalender hari libur beserta kategorinya dan cakupan (seluruh unit atau unit tertentu). |
| `holiday_units` | Relasi cakupan libur ke unit tertentu, dipakai saat sebuah libur tidak berlaku untuk semua unit. |
| `attendances` | Catatan presensi harian: waktu masuk/pulang, koordinat, jarak dari lokasi, dan status akhir. |

## 9. Alur Pengguna Utama

### 9.1 Alur Karyawan

1. Membuka aplikasi dan melihat status jarak terhadap lokasi pesantren secara real-time.
2. Menekan tombol "Absen Masuk" ketika berada dalam radius yang diizinkan; tombol otomatis nonaktif jika di luar radius.
3. Melihat status presensi hari ini (waktu masuk, tepat waktu/terlambat) pada halaman beranda.
4. Menekan "Absen Pulang" pada akhir jam kerja.
5. Meninjau riwayat presensi pribadi pada tab Riwayat.

### 9.2 Alur Admin

1. Memilih tanggal pada halaman rekap kehadiran untuk melihat status seluruh karyawan pada tanggal tersebut.
2. Menyaring atau mencari karyawan tertentu dalam daftar rekap.
3. Membuka detail seorang karyawan untuk meninjau statistik dan kalender kehadiran per periode.
4. Mengelola jadwal kerja karyawan (hari kerja dan jam kehadiran) melalui halaman manajemen jadwal.
5. Menambahkan tanggal libur beserta cakupan unit yang berlaku melalui halaman kalender libur.

## 10. Kebutuhan Non-Fungsional

- **Keamanan:** validasi jarak lokasi dan otorisasi aksi presensi dilakukan di server, bukan hanya di sisi klien.
- **Prasyarat teknis:** aplikasi presensi mobile memerlukan koneksi HTTPS agar Geolocation API browser dapat berfungsi.
- **Kinerja:** halaman rekap harian dan kalender bulanan admin dimuat dalam waktu wajar (di bawah 2 detik pada koneksi standar).
- **Responsif:** seluruh antarmuka berfungsi baik pada perangkat mobile dan tetap rapi ketika diakses dari layar yang lebih besar.
- **Auditability:** perubahan jadwal kerja dan data kalender libur dapat ditelusuri (dicatat siapa dan kapan melakukan perubahan).
- **Skalabilitas:** struktur data mendukung penambahan unit kerja dan periode jadwal baru tanpa migrasi data besar.

## 11. Metrik Keberhasilan

- Lebih dari 95% presensi tervalidasi otomatis tanpa perlu koreksi manual oleh admin.
- Waktu yang dibutuhkan admin untuk menyusun rekap bulanan berkurang signifikan dibandingkan proses manual sebelumnya.
- Tidak ada kasus presensi yang sah tercatat sebagai tidak hadir akibat tanggal libur yang tidak dikecualikan dengan benar.
- Guru dengan jadwal mengajar tidak harian tidak tercatat sebagai alpa pada hari-hari di luar jadwalnya.

## 12. Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
|---|---|---|
| Pemalsuan lokasi (fake GPS) | Karyawan dapat tercatat hadir padahal tidak berada di lokasi. | Validasi jarak tetap dihitung ulang di server; radius diberi toleransi wajar; potensi verifikasi tambahan di fase berikutnya. |
| Akurasi GPS rendah di dalam gedung | Karyawan yang benar-benar hadir gagal presensi karena sinyal GPS lemah. | Radius absen diberi toleransi yang wajar (bukan terlalu ketat) dan sistem menampilkan indikator akurasi kepada pengguna. |
| Perubahan jadwal di tengah periode | Data presensi lama berubah makna jika jadwal baru menimpa jadwal lama. | Jadwal dikelola per Periode Jadwal dengan histori tersimpan; presensi lama tetap merujuk periode yang berlaku saat itu. |
| Akses tanpa HTTPS | Geolocation API tidak dapat berjalan pada koneksi HTTP biasa. | Deployment produksi wajib menggunakan HTTPS; halaman menampilkan pesan jelas jika prasyarat ini tidak terpenuhi. |
| Libur tidak dikecualikan dengan benar | Karyawan tercatat alpa pada tanggal yang seharusnya libur. | Logika status dihitung otomatis di server berdasarkan tabel kalender libur sebelum status lain dievaluasi. |

## 13. Pengembangan Lanjutan (Di Luar Fase Ini)

- Alur pengajuan izin/sakit/cuti formal dengan persetujuan atasan, terpisah dari kalender libur bersama.
- Riwayat perpindahan unit kerja karyawan, agar perhitungan cakupan libur pada tanggal lampau tetap akurat.
- Dukungan multi-lokasi/cabang pesantren dalam satu sistem presensi.
- Notifikasi otomatis untuk keterlambatan atau ketidakhadiran berulang.

## 14. Lampiran

Dokumen dan berkas teknis pendukung yang dihasilkan bersamaan dengan PRD ini:

- `skema-database-presensi.sql` — skema database lengkap beserta relasi dan indeks.
- `presensi-karyawan.html` — implementasi halaman presensi karyawan siap produksi, termasuk kontrak API.
- `admin-rekap-kehadiran.html` / `admin-rekap-kehadiran-tema-biru.html` — implementasi halaman rekap dan detail kehadiran admin siap produksi.
- `admin-jadwal-kerja-mockup.html` — desain halaman manajemen jadwal kerja admin.
- Kontrak API (endpoint, format request/response) didokumentasikan langsung sebagai komentar pada masing-masing berkas HTML produksi terkait.
