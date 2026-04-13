# Requirements Document

## Introduction

Fitur **Admin Pura** memperkenalkan role baru (level 6) yang bertanggung jawab mengelola keuangan punia secara online untuk satu Pura tertentu. Role ini menggantikan tanggung jawab Admin Sistem (level 4) dalam hal pengelolaan pura dan banjar, sehingga Admin Sistem difokuskan kembali ke manajemen sistem secara umum. Admin Pura mendapatkan tampilan mobile yang ringan, mirip dengan Kelian Adat (level 2), dengan akses terbatas hanya pada fitur punia pura yang ditugaskan.

Perubahan ini juga mencakup pembersihan relasi `id_pura` dan `id_banjar` dari model/form Admin Sistem, serta penambahan fitur shareable link dan tampilan QRIS untuk pembayaran punia pura.

## Glossary

- **Admin_Pura**: Pengguna dengan level 6 yang ditugaskan mengelola punia satu Pura tertentu.
- **Admin_Sistem**: Pengguna dengan level 4 yang mengelola sistem secara umum (tidak lagi terikat ke Pura atau Banjar tertentu).
- **Pura**: Entitas tempat ibadah Hindu Bali, direpresentasikan oleh model `Pura` pada tabel `tb_pura`.
- **PuniaPura**: Transaksi dana punia yang masuk ke suatu Pura, direpresentasikan oleh model `PuniaPura` pada tabel `tb_punia_pura`.
- **QrisPura**: Data kode QRIS statis yang terdaftar untuk suatu Pura, direpresentasikan oleh model `QrisPura` pada tabel `tb_qris_pura`.
- **Shareable_Link**: URL publik yang dapat dibagikan kepada umat untuk melakukan punia ke Pura tertentu.
- **Dashboard_Mobile**: Tampilan antarmuka berbasis mobile (menggunakan layout `mobile_layout`) yang digunakan oleh role Kelian Adat dan Admin Pura.
- **RoleMiddleware**: Middleware Laravel yang memvalidasi `id_level` pengguna sebelum mengizinkan akses ke route tertentu.
- **Level**: Nilai integer pada kolom `id_level` di tabel `users` yang menentukan peran pengguna dalam sistem.

---

## Requirements

### Requirement 1: Penambahan Role Admin Pura (Level 6)

**User Story:** Sebagai Super Admin, saya ingin membuat role baru Admin Pura (level 6) yang terikat ke satu Pura, sehingga pengelolaan punia pura dapat didelegasikan kepada penanggung jawab pura tanpa memberikan akses sistem yang luas.

#### Acceptance Criteria

1. THE System SHALL mendukung nilai `id_level = 6` sebagai identifikasi role Admin_Pura pada tabel `users`.
2. WHEN pengguna dengan `id_level = 6` dibuat, THE System SHALL mewajibkan pengisian kolom `id_pura` yang merujuk ke satu entitas Pura yang valid pada tabel `tb_pura`.
3. THE System SHALL memastikan satu akun Admin_Pura hanya terikat ke satu Pura (relasi one-to-one antara `users.id_pura` dan `tb_pura.id_pura` untuk level 6).
4. WHEN Admin_Pura login, THE System SHALL membuat sesi dengan `level = 6` dan mengarahkan pengguna ke Dashboard_Mobile Admin Pura.
5. THE RoleMiddleware SHALL mengizinkan akses ke route Admin_Pura hanya untuk pengguna dengan `id_level = 6`.

---

### Requirement 2: Pembersihan Relasi Pura dan Banjar dari Admin Sistem

**User Story:** Sebagai Super Admin, saya ingin menghapus relasi pura dan banjar dari role Admin Sistem (level 4), sehingga Admin Sistem tidak lagi memiliki keterikatan ke entitas Pura atau Banjar tertentu.

#### Acceptance Criteria

1. THE System SHALL menghapus kolom `id_pura` dari daftar `$fillable` model `User` untuk konteks pembuatan/pembaruan akun Admin_Sistem (level 4).
2. WHEN form pembuatan atau pembaruan akun Admin_Sistem ditampilkan, THE System SHALL tidak menampilkan field pilihan Pura dan Banjar.
3. THE System SHALL memastikan logika pada `DashboardController` tidak lagi menggunakan relasi `pura` atau `banjar` untuk pengguna dengan `id_level = 4`.
4. IF pengguna dengan `id_level = 4` memiliki nilai `id_pura` yang tersimpan di database, THEN THE System SHALL mengabaikan nilai tersebut dan tidak menggunakannya untuk pembatasan akses data.

---

### Requirement 3: Dashboard Mobile Admin Pura

**User Story:** Sebagai Admin Pura, saya ingin memiliki dashboard mobile yang menampilkan ringkasan data punia pura saya, sehingga saya dapat memantau kondisi keuangan pura dengan cepat dari perangkat mobile.

#### Acceptance Criteria

1. WHEN Admin_Pura mengakses halaman dashboard, THE System SHALL menampilkan tampilan `Dashboard_Mobile` menggunakan layout `mobile_layout`.
2. THE Dashboard_Mobile SHALL menampilkan nama Pura yang ditugaskan kepada Admin_Pura yang sedang login.
3. THE Dashboard_Mobile SHALL menampilkan total nominal PuniaPura dengan `status_pembayaran = 'completed'` dan `aktif = '1'` untuk Pura yang ditugaskan.
4. THE Dashboard_Mobile SHALL menampilkan jumlah transaksi PuniaPura yang masuk hari ini untuk Pura yang ditugaskan.
5. THE Dashboard_Mobile SHALL menyediakan tautan navigasi cepat (quick action) ke halaman: Manajemen Punia, Tampilkan QRIS, dan Shareable Link.
6. IF Admin_Pura tidak memiliki `id_pura` yang valid, THEN THE System SHALL menampilkan pesan informasi bahwa belum ada Pura yang ditugaskan.

---

### Requirement 4: Manajemen Punia Pura secara Online

**User Story:** Sebagai Admin Pura, saya ingin dapat melihat dan mengelola data transaksi punia untuk pura saya, sehingga saya dapat memantau pemasukan dan melakukan verifikasi pembayaran manual.

#### Acceptance Criteria

1. WHEN Admin_Pura mengakses halaman manajemen punia, THE System SHALL menampilkan daftar transaksi PuniaPura yang hanya berasal dari `id_pura` milik Admin_Pura yang sedang login.
2. THE System SHALL menampilkan informasi setiap transaksi meliputi: nama donatur, nominal, metode pembayaran, status pembayaran, dan tanggal transaksi.
3. WHEN Admin_Pura memfilter data berdasarkan rentang tanggal, THE System SHALL menampilkan transaksi PuniaPura sesuai filter tanggal yang dipilih.
4. THE System SHALL menampilkan ringkasan total punia yang telah selesai (`status_pembayaran = 'completed'`) untuk periode yang ditampilkan.
5. WHEN terdapat transaksi PuniaPura dengan `metode_pembayaran = 'manual'` dan `status_verifikasi = 'pending'`, THE System SHALL menampilkan transaksi tersebut pada antrian verifikasi Admin_Pura.
6. WHEN Admin_Pura menyetujui verifikasi pembayaran manual, THE System SHALL memperbarui `status_verifikasi` menjadi `'approved'` dan `status_pembayaran` menjadi `'completed'` pada record PuniaPura yang bersangkutan.
7. WHEN Admin_Pura menolak verifikasi pembayaran manual, THE System SHALL memperbarui `status_verifikasi` menjadi `'rejected'` dan menyimpan catatan penolakan pada kolom `catatan_verifikasi`.
8. THE System SHALL membatasi Admin_Pura agar hanya dapat mengakses dan memodifikasi data PuniaPura yang `id_pura`-nya sesuai dengan `id_pura` milik Admin_Pura yang sedang login.

---

### Requirement 5: Tampilan Kode QRIS untuk Pembayaran Punia

**User Story:** Sebagai Admin Pura, saya ingin dapat menampilkan kode QRIS pura saya kepada umat, sehingga umat dapat melakukan pembayaran punia secara digital dengan mudah.

#### Acceptance Criteria

1. WHEN Admin_Pura mengakses halaman QRIS, THE System SHALL menampilkan gambar QRIS statis (`qris_image`) dari record QrisPura dengan `id_pura` yang sesuai dan `is_active = '1'`.
2. THE System SHALL menampilkan informasi merchant QRIS meliputi: nama merchant (`merchant_name`) dan NMID (`nmid`).
3. IF tidak ada record QrisPura aktif untuk Pura yang ditugaskan, THEN THE System SHALL menampilkan pesan informasi bahwa QRIS belum tersedia dan mengarahkan Admin_Pura untuk menghubungi Admin_Sistem.
4. THE System SHALL menyediakan tombol untuk mengunduh gambar QRIS dalam format PNG.
5. THE System SHALL membatasi Admin_Pura agar hanya dapat melihat QRIS milik Pura yang ditugaskan kepadanya.

---

### Requirement 6: Shareable Link Punia Pura

**User Story:** Sebagai Admin Pura, saya ingin dapat membuat dan membagikan link punia pura saya, sehingga umat dapat dengan mudah mengakses halaman pembayaran punia melalui tautan yang dapat dibagikan.

#### Acceptance Criteria

1. THE System SHALL menyediakan Shareable_Link dengan format URL yang dapat diakses publik tanpa login, menggunakan identifier unik Pura (contoh: `/punia/{slug_pura}` atau `/punia/pura/{id_pura}`).
2. WHEN Admin_Pura mengakses halaman Shareable Link, THE System SHALL menampilkan URL Shareable_Link untuk Pura yang ditugaskan.
3. THE System SHALL menyediakan tombol salin (copy) yang menyalin Shareable_Link ke clipboard pengguna.
4. WHEN umat mengakses Shareable_Link, THE System SHALL menampilkan halaman publik berisi informasi Pura dan formulir pembayaran punia.
5. THE System SHALL memastikan Shareable_Link hanya dapat digunakan untuk Pura yang memiliki status `aktif = '1'`.
6. IF Pura yang dirujuk oleh Shareable_Link memiliki status `aktif = '0'`, THEN THE System SHALL menampilkan halaman informasi bahwa link tidak tersedia.

---

### Requirement 7: Keamanan dan Pembatasan Akses Role Admin Pura

**User Story:** Sebagai Super Admin, saya ingin memastikan Admin Pura hanya dapat mengakses fitur yang relevan dengan pura yang ditugaskan, sehingga data pura lain tetap aman dan terisolasi.

#### Acceptance Criteria

1. THE RoleMiddleware SHALL menolak akses ke semua route Admin_Pura bagi pengguna yang tidak memiliki `id_level = 6`, dan mengarahkan mereka ke halaman login.
2. THE System SHALL menolak akses Admin_Pura ke route manajemen sistem yang diperuntukkan bagi Admin_Sistem (level 4) atau Bendesa Adat (level 1).
3. WHEN Admin_Pura mencoba mengakses data PuniaPura dengan `id_pura` yang berbeda dari `id_pura` miliknya, THE System SHALL mengembalikan respons HTTP 403 (Forbidden).
4. WHEN Admin_Pura mencoba mengakses data QrisPura dengan `id_pura` yang berbeda dari `id_pura` miliknya, THE System SHALL mengembalikan respons HTTP 403 (Forbidden).
5. THE System SHALL memvalidasi bahwa `id_pura` pada setiap request dari Admin_Pura sesuai dengan `id_pura` yang tersimpan pada sesi pengguna yang sedang login.
