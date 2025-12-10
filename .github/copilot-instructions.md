# GitHub Copilot Instructions

## 📌 Proyek

**Aplikasi Database Santri & Alumni Perguruan Islam Alkhairaat**
Teknologi utama: **Laravel + Tailwind CSS + MySQL/PostgreSQL**

Dokumen ini berfungsi sebagai pedoman perilaku dan gaya pengkodean GitHub Copilot agar sesuai dengan kebutuhan proyek.

---

## Latar Belakang dan Tujuan

Perguruan Islam Alkhairaat memiliki ribuan unit pendidikan dan puluhan pesantren dengan puluhan ribu santri dan alumni yang tersebar di berbagai daerah, sehingga pengelolaan data secara manual sulit dan tidak terpusat. Diperlukan aplikasi database internal terintegrasi untuk mendata santri aktif/nonaktif, alumni, angkatan, dan profil lembaga pendidikan Alkhairaat secara nasional.

---

## 🎯 Tujuan Utama Sistem

* Menyediakan database terpusat yang menyimpan data-data santri, alumni, dan lembaga Alkhairaat di bawah koordinasi PB Alkhairaat di Palu.
* Memudahkan sekolah/pesantren Alkhairaat memperbarui data santri dan alumni secara berkala.
* Menyajikan statistik jumlah santri, alumni, dan sebaran angkatan sebagai dasar perencanaan pendidikan.
* Mendukung multi-role user (PB, wilayah, sekolah/pesantren).
* Fokus pada stabilitas, keamanan data, dan kemudahan input operator sekolah.

---

## 🧱 Arsitektur & Pola Pengembangan

Gunakan prinsip berikut:

* **MVC Laravel secara ketat** (Model, Controller, View)
* **Service Layer (opsional)** untuk logika kompleks
* **Repository Pattern (opsional)** untuk query besar
* Hindari logika bisnis di Blade

Struktur utama:

* `app/Models` → model Eloquent
* `app/Http/Controllers` → controller per modul
* `resources/views` → Blade + Tailwind
* `routes/web.php` → routing utama

---

## 🔐 Role & Hak Akses

Pastikan semua kode mempertimbangkan role berikut:

* `super_admin` → PB Alkhairaat
* `sekolah` → operator sekolah/pesantren
* `wilayah` → monitoring rekap

Super Admin:
1. Mengelola data Lembaga
2. Mengelola akun User (pengelola) Sekolah
3. Melihat semua laporan nasional

User Sekolah:
1. Mengelola data Santri (di unit Sekolah masing-masing)
2. Mengelola data Alumni (di unit Sekolah masing-masing)
3. Melihat laporan internal unit Sekolah
4. Tidak dapat melihat dan mengelola data unit Sekolah lain.

User Wilayah:
1. Dapat melihat data semua Sekolah di wilayahnya.
2. Tidak dapat mengelola data Santi dan Alumni.

Semua query wajib memfilter berdasarkan `lembaga_id` jika user role = sekolah

---

## 🗃️ Entitas Data Utama

Gunakan penamaan konsisten berikut:

### 1. Lembaga

* Table: `lembaga`
* Field utama:

  * `id`
  * `kode_lembaga`
  * `nama`
  * `jenjang`
  * `status`
  * `provinsi`
  * `kabupaten`

### 2. Santri

* Table: `santri`
* Field utama:

  * `id`
  * `nis`
  * `nama`
  * `nik`
  * `jenis_kelamin`
  * `kelas`
  * `status`
  * `tahun_masuk`
  * `lembaga_id`

### 3. Alumni

* Table: `alumni`
* Field utama:

  * `id`
  * `santri_id`
  * `tahun_lulus`
  * `angkatan`
  * `kontak`
  * `lanjutan_studi`
  * `pekerjaan`

### 4. User

* Gunakan default Laravel `users`
* Tambahan:

  * `role`
  * `lembaga_id`

---

## ✅ Standar Kode yang Wajib Diikuti

### Umum

* Gunakan **PSR-12 Coding Style**
* Gunakan **type hint & return type**
* Gunakan **strict validation** pada semua request
* Mohon berhati-hati dengan __null safety__ pada setiap data maupun relasi, gunakan `?` jika perlu, dan gunakan falback default value jika memungkinkan

### Controller

* Maksimal 1 controller per modul
* Gunakan **Form Request Validation**
* Hindari query kompleks langsung di controller

### Model

* Gunakan:

  * `$fillable`
  * Relasi Eloquent (`belongsTo`, `hasMany`)
  * Scope untuk filter data (`scopeAktif`, dll)

---

## 🎨 Aturan UI dengan Tailwind CSS

* Gunakan **utility-first approach**
* Konsisten pada:

  * `bg-white`, `rounded-lg`, `shadow-md`
  * `text-sm`, `text-base`, `text-lg`
* Gunakan layout:

  * Sidebar + Topbar
* Semua form wajib:

  * Label
  * Validasi error
  * Helper text

---

## 📊 Aturan Dashboard & Laporan

* Semua dashboard wajib berbasis:

  * Data real-time
  * Query terfilter berdasarkan role
* Gunakan **query builder atau Eloquent** yang optimal
* Hindari `N+1 Query`

---

## 📤 Aturan Ekspor Data

* Gunakan format:

  * CSV
  * Excel
* Data yang diekspor:

  * Santri
  * Alumni
  * Rekap nasional

Pastikan:

* Encoding UTF-8
* Header kolom jelas

---

## 🛡️ Keamanan

* Wajib:

  * CSRF Protection
  * Hash password (bcrypt)
  * Validasi input ketat
* Larangan:

  * Query tanpa filter `lembaga_id`
  * Akses data lintas sekolah

---

## 🧪 Testing

* Minimal testing untuk:

  * Login
  * CRUD santri
  * CRUD alumni
  * Hak akses user

---

## 🧠 Perilaku yang Diharapkan dari GitHub Copilot

GitHub Copilot harus:

* Mengutamakan **keamanan data santri & alumni**
* Menghasilkan kode **clean, readable, dan scalable**
* Menghindari:

  * Hardcoded role
  * Magic number
  * Query mentah tanpa sanitasi

---

## ✅ Prinsip Akhir

> "Aplikasi ini bersifat **amanah**, karena mengelola data santri dan alumni secara nasional. Setiap baris kode harus mengutamakan **keamanan, kejujuran data, dan kemudahan operator sekolah."*

---

Dokumen ini menjadi standar perilaku pengkodean selama pengembangan aplikasi Database Santri & Alumni Perguruan Islam Alkhairaat.
