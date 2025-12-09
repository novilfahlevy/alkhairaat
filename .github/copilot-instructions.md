# GitHub Copilot Instructions

## 📌 Proyek

**Aplikasi Database Santri & Alumni Perguruan Islam Alkhairaat**
Teknologi utama: **Laravel + Tailwind CSS + MySQL/PostgreSQL**

Dokumen ini berfungsi sebagai pedoman perilaku dan gaya pengkodean GitHub Copilot agar sesuai dengan kebutuhan proyek.

---

## 🎯 Tujuan Utama Sistem

* Menyediakan database terpusat santri, alumni, dan lembaga Alkhairaat.
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

Aturan utama:

* User **sekolah hanya boleh mengakses datanya sendiri**
* User **tidak boleh mengakses data lembaga lain**
* super_admin dapat mengakses semua data, namun tidak dapat menambah/mengubah/menghapus data lembaga
* Semua query wajib memfilter berdasarkan `lembaga_id` jika user role = sekolah

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
