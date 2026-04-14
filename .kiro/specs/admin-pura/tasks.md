# Implementation Plan: Admin Pura (Level 6)

## Overview

Implementasi role baru Admin Pura (level 6) secara incremental: mulai dari fondasi database dan middleware, lalu controller, routes, views mobile, hingga halaman publik shareable link. Setiap langkah langsung terintegrasi ke sistem yang sudah berjalan.

## Tasks

- [ ] 1. Database migration dan model update
  - [ ] 1.1 Buat migration untuk menambahkan kolom `slug_pura` ke tabel `tb_pura`
    - Cek apakah kolom `slug_pura VARCHAR(100) UNIQUE NULL` sudah ada; jika belum, buat migration baru
    - File: `database/migrations/xxxx_add_slug_pura_to_tb_pura.php`
    - _Requirements: 6.1_

  - [ ] 1.2 Tambahkan method `getShareableLinkAttribute()` ke model `Pura`
    - Method mengembalikan URL `/punia/pura/{slug_pura ?? id_pura}`
    - Tambahkan `slug_pura` ke `$fillable` jika belum ada
    - File: `app/Models/Pura.php`
    - _Requirements: 6.1, 6.2_

  - [ ]* 1.3 Tulis unit test untuk `getShareableLinkAttribute()`
    - Test dengan pura yang punya `slug_pura` → URL menggunakan slug
    - Test dengan pura tanpa `slug_pura` → URL menggunakan `id_pura`
    - _Requirements: 6.1_

- [ ] 2. Update RoleMiddleware dan DashboardController untuk level 6
  - [ ] 2.1 Tambahkan case level 6 di `DashboardController::indexhome()`
    - Import model `PuniaPura` di bagian atas file
    - Tambahkan blok `else if ($level == "6")` setelah blok level 5
    - Ambil `$pura = Auth::user()->pura`; jika null, return view dengan data kosong dan pesan info
    - Hitung `$total_punia` dari `PuniaPura` dengan `status_pembayaran = 'completed'` dan `aktif = '1'` untuk `id_pura` user
    - Hitung `$transaksi_hari_ini` dari `PuniaPura` dengan `whereDate('created_at', today())` dan `aktif = '1'`
    - Return `view('backend.adminpura.home', compact('pura', 'total_punia', 'transaksi_hari_ini'))`
    - File: `app/Http/Controllers/Administrator/DashboardController.php`
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.6_

  - [ ]* 2.2 Tulis unit test untuk `DashboardController` level 6
    - Test redirect ke view `backend.adminpura.home` saat login sebagai level 6
    - Test data kosong saat `id_pura` null (Requirement 3.6)
    - Test `total_punia` hanya menghitung transaksi `completed` dan `aktif = '1'`
    - _Requirements: 3.3, 3.6_

- [ ] 3. Buat AdminPuraController
  - [ ] 3.1 Buat file `AdminPuraController.php` dengan helper keamanan
    - Buat class `AdminPuraController extends BaseController`
    - Implementasikan private method `getAuthPuraId()` dan `authorizeForPura(int $requestedPuraId)`
    - `authorizeForPura` memanggil `abort(403)` jika `id_pura` tidak cocok
    - File: `app/Http/Controllers/Administrator/AdminPuraController.php`
    - _Requirements: 7.3, 7.4, 7.5_

  - [ ] 3.2 Implementasikan method `puniaIndex()` dan `puniaVerifikasi()`
    - `puniaIndex()`: query `PuniaPura` dengan `where('id_pura', $this->getAuthPuraId())`, support filter tanggal dari request `date_awal` dan `date_akhir`, hitung `$total_punia` untuk periode yang ditampilkan
    - `puniaVerifikasi()`: query `PuniaPura` dengan `id_pura` user, `metode_pembayaran = 'manual'`, `status_verifikasi = 'pending'`
    - Return view `backend.adminpura.punia` dan `backend.adminpura.punia_verifikasi`
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.8_

  - [ ] 3.3 Implementasikan method `puniaApprove()` dan `puniaReject()`
    - `puniaApprove()`: validasi request, panggil `authorizeForPura()` dengan `id_pura` dari record, update `status_verifikasi = 'approved'` dan `status_pembayaran = 'completed'`
    - `puniaReject()`: validasi request, panggil `authorizeForPura()`, update `status_verifikasi = 'rejected'` dan `catatan_verifikasi` dari request
    - Jika transaksi sudah diproses (bukan `pending`), redirect back dengan pesan error
    - _Requirements: 4.6, 4.7_

  - [ ] 3.4 Implementasikan method `qris()` dan `qrisDownload()`
    - `qris()`: ambil `QrisPura` dengan `id_pura = getAuthPuraId()` dan `is_active = '1'`; jika tidak ada, pass `$qris = null` ke view
    - `qrisDownload()`: verifikasi `id_pura`, return response download file gambar QRIS
    - Return view `backend.adminpura.qris`
    - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5_

  - [ ] 3.5 Implementasikan method `shareableLink()` dan `publicPunia()`
    - `shareableLink()`: ambil pura user, pass `$shareable_url = $pura->shareable_link` ke view
    - `publicPunia(string $identifier)`: cari pura by `slug_pura` atau `id_pura`; jika `aktif = '0'`, pass flag `$pura_inactive = true`; jika tidak ditemukan, abort 404
    - Return view `backend.adminpura.shareable_link` dan `public.punia_pura`
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5, 6.6_

  - [ ]* 3.6 Tulis property test untuk isolasi data punia (Property 1)
    - **Property 1: Isolasi Data Punia**
    - Generate N pura dengan M transaksi masing-masing; login sebagai Admin Pura pura ke-i; verifikasi `puniaIndex()` hanya mengembalikan transaksi dengan `id_pura = i`
    - **Validates: Requirements 4.1, 4.8, 7.3, 7.5**

  - [ ]* 3.7 Tulis property test untuk akses lintas pura (Property 2)
    - **Property 2: Akses Lintas Pura Menghasilkan 403**
    - Generate dua pura berbeda; login sebagai Admin Pura A; coba akses endpoint dengan `id_pura` pura B; verifikasi response 403
    - **Validates: Requirements 4.8, 5.5, 7.3, 7.4**

  - [ ]* 3.8 Tulis property test untuk total punia dashboard (Property 4)
    - **Property 4: Total Punia Dashboard Konsisten dengan Data**
    - Generate N transaksi dengan berbagai status; hitung expected sum manual; bandingkan dengan nilai dari controller
    - **Validates: Requirements 3.3, 4.4**

  - [ ]* 3.9 Tulis property test untuk filter tanggal (Property 5)
    - **Property 5: Filter Tanggal Mengembalikan Transaksi dalam Rentang**
    - Generate transaksi dengan tanggal acak; generate rentang tanggal acak; verifikasi semua hasil berada dalam rentang (inklusif)
    - **Validates: Requirements 4.3**

  - [ ]* 3.10 Tulis property test untuk antrian verifikasi (Property 6)
    - **Property 6: Antrian Verifikasi Hanya Berisi Transaksi Manual Pending**
    - Generate transaksi dengan berbagai kombinasi `metode_pembayaran` dan `status_verifikasi`; verifikasi antrian hanya berisi `manual + pending`
    - **Validates: Requirements 4.5**

  - [ ]* 3.11 Tulis property test untuk state transition verifikasi (Property 7)
    - **Property 7: State Transition Verifikasi Konsisten**
    - Generate transaksi pending acak; jalankan approve/reject; verifikasi state akhir: approve → `approved + completed`, reject → `rejected + catatan_verifikasi = N`
    - **Validates: Requirements 4.6, 4.7**

  - [ ]* 3.12 Tulis property test untuk shareable link aktif/non-aktif (Property 8)
    - **Property 8: Shareable Link Aktif/Non-Aktif**
    - Generate pura aktif dan non-aktif; akses shareable link masing-masing; verifikasi pura aktif menampilkan form, pura non-aktif menampilkan halaman "tidak tersedia"
    - **Validates: Requirements 6.5, 6.6**

- [ ] 4. Checkpoint — Pastikan semua tests pass
  - Pastikan semua unit test dan property test yang sudah ditulis lulus, tanyakan ke user jika ada pertanyaan.

- [ ] 5. Tambahkan routes untuk Admin Pura
  - [ ] 5.1 Tambahkan route group `role:6` di `routes/web.php`
    - Tambahkan di dalam grup `administrator`, setelah blok Ticket Counter (level 5)
    - Route yang ditambahkan:
      - `GET /pura/punia` → `AdminPuraController@puniaIndex`
      - `GET /pura/punia/verifikasi` → `AdminPuraController@puniaVerifikasi`
      - `POST /pura/punia/approve` → `AdminPuraController@puniaApprove`
      - `POST /pura/punia/reject` → `AdminPuraController@puniaReject`
      - `GET /pura/qris` → `AdminPuraController@qris`
      - `GET /pura/qris/download` → `AdminPuraController@qrisDownload`
      - `GET /pura/shareable-link` → `AdminPuraController@shareableLink`
    - File: `routes/web.php`
    - _Requirements: 1.5, 7.1, 7.2_

  - [ ] 5.2 Tambahkan route publik shareable link di luar grup `administrator`
    - `GET /punia/pura/{identifier}` → `AdminPuraController@publicPunia` dengan nama `public.punia.pura`
    - Tambahkan di bagian public routes (dekat route pura yang sudah ada)
    - File: `routes/web.php`
    - _Requirements: 6.1, 6.4_

  - [ ]* 5.3 Tulis property test untuk proteksi route (Property 3)
    - **Property 3: Akses Route Admin Pura Hanya untuk Level 6**
    - Generate user dengan level acak dari {1,2,3,4,5,7}; coba akses route `role:6`; verifikasi redirect ke login
    - **Validates: Requirements 1.5, 7.1**

- [ ] 6. Buat views mobile Admin Pura
  - [ ] 6.1 Buat view `backend/adminpura/home.blade.php`
    - Extends `mobile_layout`, section `isi_menu`
    - Tampilkan nama pura (`$pura->nama_pura`) di header card
    - Tampilkan total punia (`$total_punia`) dan jumlah transaksi hari ini (`$transaksi_hari_ini`)
    - Tampilkan 3 quick action card: link ke `/administrator/pura/punia`, `/administrator/pura/qris`, `/administrator/pura/shareable-link`
    - Jika `$pura == null`, tampilkan pesan info "Belum ada Pura yang ditugaskan"
    - Ikuti pola visual dari `resources/views/backend/kelian/home.blade.php`
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6_

  - [ ] 6.2 Buat view `backend/adminpura/punia.blade.php`
    - Extends `mobile_layout`, section `isi_menu`
    - Tampilkan filter tanggal (date_awal, date_akhir) dengan form GET
    - Tampilkan ringkasan total punia periode yang ditampilkan
    - Tampilkan tabel/list transaksi: nama donatur, nominal (format rupiah), metode, status, tanggal
    - Sertakan link ke halaman verifikasi jika ada transaksi pending
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5_

  - [ ] 6.3 Buat view `backend/adminpura/punia_verifikasi.blade.php`
    - Extends `mobile_layout`, section `isi_menu`
    - Tampilkan list transaksi manual pending dengan detail: nama donatur, nominal, bukti transfer
    - Sediakan tombol Setujui (POST ke `/administrator/pura/punia/approve`) dan Tolak (POST ke `/administrator/pura/punia/reject`) per transaksi
    - Form reject menyertakan field `catatan_verifikasi`
    - _Requirements: 4.5, 4.6, 4.7_

  - [ ] 6.4 Buat view `backend/adminpura/qris.blade.php`
    - Extends `mobile_layout`, section `isi_menu`
    - Jika `$qris` ada: tampilkan gambar QRIS (`$qris->qris_image`), nama merchant, NMID, dan tombol download
    - Jika `$qris` null: tampilkan pesan "QRIS belum tersedia, hubungi Admin Sistem"
    - Tombol download mengarah ke `/administrator/pura/qris/download`
    - _Requirements: 5.1, 5.2, 5.3, 5.4_

  - [ ] 6.5 Buat view `backend/adminpura/shareable_link.blade.php`
    - Extends `mobile_layout`, section `isi_menu`
    - Tampilkan URL shareable link dalam input readonly
    - Sediakan tombol salin (copy to clipboard) menggunakan JavaScript `navigator.clipboard.writeText()`
    - Tampilkan preview URL yang akan dibagikan
    - _Requirements: 6.2, 6.3_

  - [ ]* 6.6 Tulis unit test untuk view dashboard (Property 9)
    - **Property 9: Dashboard Menampilkan Nama Pura yang Ditugaskan**
    - Generate Admin Pura dengan pura acak; akses dashboard; verifikasi nama pura muncul di response HTML
    - **Validates: Requirements 3.2**

- [ ] 7. Update `mobile_layout.blade.php` untuk bottom navigation level 6
  - [ ] 7.1 Tambahkan label `'Admin Pura'` untuk level 6 di `$roleLabel`
    - Ubah ternary/match expression di header `mobile_layout.blade.php` untuk menambahkan case `level == '6'`
    - File: `resources/views/mobile_layout.blade.php`
    - _Requirements: 1.4_

  - [ ] 7.2 Tambahkan blok bottom navigation untuk level 6
    - Tambahkan blok `@if(Session::get('level') == "6")` setelah blok level 5 (Ticket Counter)
    - Isi dengan 4 link: Home (`/administrator/`), Punia (`/administrator/pura/punia`), QRIS (`/administrator/pura/qris`), Link (`/administrator/pura/shareable-link`)
    - Ikuti pola visual yang sama dengan blok level 2 (Kelian Adat)
    - File: `resources/views/mobile_layout.blade.php`
    - _Requirements: 3.5_

- [ ] 8. Buat halaman publik punia pura
  - [ ] 8.1 Buat view `public/punia_pura.blade.php`
    - Extends `mobile_layout_public`
    - Jika `$pura_inactive == true`: tampilkan halaman informasi "Link tidak tersedia"
    - Jika pura aktif: tampilkan nama pura, deskripsi, gambar, dan form punia sederhana (nama donatur, nominal, metode pembayaran)
    - Form POST ke route yang sudah ada (`public.pura.punia.submit`) atau buat route baru jika diperlukan
    - _Requirements: 6.4, 6.5, 6.6_

- [ ] 9. Bersihkan form Admin Sistem (level 4) dari field banjar
  - [ ] 9.1 Update view `data_user/table.blade.php` untuk menyembunyikan field banjar saat level 4
    - Field banjar saat ini muncul untuk level 2, 3, 5, 6, 7 — hapus `4` dari kondisi `x-show` jika ada, atau tambahkan kondisi `userLevel != 4` pada field banjar
    - Verifikasi field pura sudah hanya muncul untuk `userLevel == 6` (sudah benar di kode yang ada)
    - File: `resources/views/admin/pages/data_user/table.blade.php`
    - _Requirements: 2.2_

- [ ] 10. Checkpoint akhir — Pastikan semua tests pass
  - Pastikan semua unit test dan property test lulus, verifikasi integrasi end-to-end, tanyakan ke user jika ada pertanyaan.

## Notes

- Tasks bertanda `*` bersifat opsional dan dapat dilewati untuk MVP yang lebih cepat
- Setiap task mereferensikan requirements spesifik untuk traceability
- Property tests menggunakan PHPUnit + Faker sebagai generator, minimum 100 iterasi per property
- Tag format property test: `/** @group Feature:admin-pura, Property {N}: {deskripsi} */`
- `RoleMiddleware` tidak perlu diubah — sudah mendukung `role:6` secara otomatis via `in_array()`
- Model `PuniaPura`, `QrisPura`, dan `User` tidak perlu perubahan — sudah lengkap
