# GitHub Copilot Instructions

## 📌 Proyek

**Aplikasi Database Murid & Alumni Perguruan Islam Alkhairaat**
Teknologi utama: **Laravel + Tailwind CSS + MySQL/PostgreSQL**

Dokumen ini berfungsi sebagai pedoman perilaku dan gaya pengkodean GitHub Copilot agar sesuai dengan kebutuhan proyek.

---

## Latar Belakang dan Tujuan

Perguruan Islam Alkhairaat memiliki ribuan unit pendidikan dan puluhan pesantren dengan puluhan ribu murid dan alumni yang tersebar di berbagai daerah, sehingga pengelolaan data secara manual sulit dan tidak terpusat. Diperlukan aplikasi database internal terintegrasi untuk mendata murid aktif/nonaktif, alumni, angkatan, dan profil lembaga pendidikan Alkhairaat secara nasional.

---

## 🎯 Tujuan Utama Sistem

* Menyediakan database terpusat yang menyimpan data-data murid, alumni, dan lembaga Alkhairaat di bawah koordinasi PB Alkhairaat di Palu.
* Memudahkan sekolah/pesantren Alkhairaat memperbarui data murid dan alumni secara berkala.
* Menyajikan statistik jumlah murid, alumni, dan sebaran angkatan sebagai dasar perencanaan pendidikan.
* Mendukung multi-role user (PB, wilayah, sekolah/pesantren).
* Fokus pada stabilitas, keamanan data, dan kemudahan input operator sekolah.
* Tujuan akhirnya adalah terdapat laporan berapa jumlah murid dan alumni di sekolah, di kabupaten, provinsi, dan seluruh indonesia, dalam periode waktu tertentu.

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

* `Superuser` → Tim IT atau orang PB Alkhairaat yang mengelola sistem.
* `Pengurus Besar` → Pengurus besar Alkhairaat di tingkat pusat.
* `Komisariat Wilayah` → Bertugas menaungi semua sekolah di wilayah provinsi yang ditentukan oleh PB.
* `Komisariat Daerah` → Bertugas menaungi semua sekolah di wilayah kabupaten/kota yang ditentukan oleh Komisariat Wilayah.
* `Sekolah/Pesantren` → User di tingkat sekolah/pesantren yang mengelola data guru, murid, dan alumni di sekolahnya masing-masing.

---

## ✅ Standar Kode yang Wajib Diikuti

### Umum

* Gunakan **PSR-12 Coding Style**.
* Gunakan **type hint & return type**.
* Gunakan **strict validation** pada semua request.
* Mohon berhati-hati dengan __null safety__ pada setiap data maupun relasi, gunakan `?` jika diperlukan, dan gunakan falback default value jika memungkinkan, ini penting.

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

## 🛡️ Keamanan

* Wajib:

  * CSRF Protection
  * Hash password (bcrypt)
  * Validasi input ketat

---

## 🧠 Perilaku yang Diharapkan dari GitHub Copilot

GitHub Copilot harus:

* Menghasilkan kode **clean, readable, dan scalable**
* Menghindari:

  * Hardcoded role
  * Magic number
  * Query mentah tanpa sanitasi
  * Tidak boleh ada logika bisnis di Blade
  * Wajib memprioritaskan clean code dan best practices Laravel
  * Wajib Memperhatikan potensi Null Reference Exception, terutama pada relasi Eloquent, berikan pengecekan dan fallback yang tepat

---

## ✅ Prinsip Akhir

> "Aplikasi ini bersifat **amanah**, karena mengelola data guru, murid, dan alumni secara nasional. Setiap baris kode harus mengutamakan **keamanan, kejujuran data, dan kemudahan operator sekolah."*

---

Dokumen ini menjadi standar perilaku pengkodean selama pengembangan aplikasi Database Perguruan Islam Alkhairaat.
