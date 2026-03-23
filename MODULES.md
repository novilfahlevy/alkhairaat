# Mapping Fitur & Modul — Website Database Alkhairaat

## Latar Belakang dan Tujuan

Perguruan Islam Alkhairaat memiliki ribuan unit pendidikan dan puluhan pesantren dengan puluhan ribu murid dan alumni yang tersebar di berbagai daerah, sehingga pengelolaan data secara manual sulit dan tidak terpusat. Diperlukan aplikasi database internal terintegrasi untuk mendata murid aktif/nonaktif, alumni, angkatan, dan profil lembaga pendidikan Alkhairaat secara nasional.

---

## Tujuan Utama Sistem

* Menyediakan database terpusat yang menyimpan data-data murid, alumni, dan lembaga Alkhairaat di bawah koordinasi PB Alkhairaat di Palu.
* Memudahkan sekolah/pesantren Alkhairaat memperbarui data murid dan alumni secara berkala.
* Menyajikan statistik jumlah murid, alumni, dan sebaran angkatan sebagai dasar perencanaan pendidikan.
* Mendukung multi-role user (PB, wilayah, sekolah/pesantren).
* Fokus pada stabilitas, keamanan data, dan kemudahan input operator sekolah.
* Tujuan akhirnya adalah terdapat laporan berapa jumlah murid dan alumni di sekolah, di kabupaten, provinsi, dan seluruh indonesia, dalam periode waktu tertentu.

## 1. Autentikasi & Profil
**Controller:** `LoginController`, `RegisterController`, `ProfileController`
- Login (via email atau username)
- Register akun baru
- Logout
- Edit profil user

---

## 2. Dashboard & Statistik
**Controller:** `DashboardController`  
**Livewire:** `SekolahCountsWidget`, `MuridGuruCountsWidget`, `MuridAlumniCountsByProvinceWidget`, `AlumniPerProvinsiWidget`, `KomwilPerProvinsiCountsWidget`

- Dashboard Super/Pengurus Besar — statistik nasional (total sekolah, murid, guru, alumni)
- Dashboard Komisariat Wilayah — statistik per provinsi
- Dashboard Komisariat Daerah — statistik per kabupaten
- Dashboard Sekolah — statistik per sekolah
- Export PDF laporan dashboard
- Livewire Widgets (real-time): jumlah sekolah, murid, guru, alumni, dan komisariat per provinsi

---

## 3. Master Data Wilayah
**Controller:** `ProvinsiController`, `KabupatenController`

- **Provinsi:** list, tambah, edit, hapus, detail
- **Kabupaten:** list, tambah, edit, hapus, detail (dengan relasi ke provinsi)

---

## 4. Manajemen Sekolah
**Controller:** `SekolahController`  
**Request:** `StoreSekolahRequest`, `UpdateSekolahRequest`  
**Views:** `resources/views/pages/sekolah/`

- List sekolah (dengan filter & pencarian)
- Tambah, edit, hapus sekolah
- Detail sekolah (profil, rekening, alamat, galeri foto)
- Validasi kode sekolah (AJAX real-time)

---

## 5. Manajemen Sekolah External
**Controller:** `SekolahExternalController`  
**Request:** `StoreSekolahExternalRequest`, `UpdateSekolahExternalRequest`

- List sekolah external (non-Alkhairaat)
- Tambah, edit, hapus, detail sekolah external
- Filter berdasarkan jenis sekolah

---

## 6. Manajemen Murid (Global)
**Controller:** `MuridController`  
**Views:** `resources/views/pages/murid/`

- List murid global lintas sekolah (filter gender & status alumni)
- Tambah, edit, hapus, detail murid

---

## 7. Manajemen Murid per Sekolah
**Controller:** `SekolahController` + `MuridSekolahTrait`  
**Request:** `StoreMuridBulkRequest`, `UpdateMuridRequest`  
**Import:** `MuridImport`  
**Export:** `MuridSekolahExport`  
**Job:** `ProcessMuridBulkFile`  
**Views:** `resources/views/pages/sekolah/murid/`

- List murid di sekolah tertentu
- Tambah murid baru ke sekolah
- Tambah murid yang sudah ada di sistem (*existing murid*)
- Import murid massal via file Excel (diproses via queue)
- Download template Excel import murid
- Edit data murid di sekolah
- Edit murid massal (*bulk edit*)
- Hapus murid dari sekolah
- Detail murid di sekolah
- Export daftar murid ke Excel

---

## 8. Manajemen Guru (Global)
**Controller:** `GuruController`  
**Views:** `resources/views/pages/guru/`

- List guru global lintas sekolah (dengan pencarian)
- Tambah, edit, hapus, detail guru

---

## 9. Manajemen Guru per Sekolah
**Controller:** `SekolahController` + `GuruSekolahTrait`  
**Request:** `StoreGuruRequest`, `StoreExistingGuruRequest`, `UpdateGuruRequest`  
**Import:** `GuruImport`  
**Job:** `ProcessGuruBulkFile`  
**Views:** `resources/views/pages/sekolah/guru/`

- List guru di sekolah tertentu
- Tambah guru baru ke sekolah
- Tambah guru yang sudah ada di sistem (*existing guru*)
- Import guru massal via file Excel (diproses via queue)
- Download template Excel import guru
- Edit, hapus, detail guru di sekolah
- Manajemen jabatan guru (tambah/hapus jabatan: Kepala Sekolah, Guru, dll)

---

## 10. Manajemen Alumni
**Controller:** `AlumniController`  
**Request:** `StoreAlumniRequest`  
**Import:** `AlumniImport`  
**Views:** `resources/views/pages/alumni/`

- List alumni (dengan filter & pencarian)
- Tambah, edit, hapus, detail alumni
- Import alumni massal via file Excel
- Download template Excel import alumni

---

## 11. Validasi Alumni (Self-Service Publik)
**Controller:** `ValidasiAlumniController`  
**Views:** `resources/views/pages/validasi-alumni/`

- Form publik (tanpa login): alumni mengisi sendiri data terkini (kontak, pekerjaan, alamat)
- Pencarian murid berdasarkan NIK (AJAX, publik)
- List pengajuan validasi (authenticated)
- Approve validasi — data disetujui dan dimasukkan ke sistem

---

## 12. Manajemen User & Akses (Role-Based)
**Controller:** `UserController`, `KomwilController`, `KomdaController`, `AkunSekolahController`

| Sub-Modul | Dikelola oleh | Keterangan |
|---|---|---|
| User (semua role) | Superuser | CRUD lengkap semua user |
| Komisariat Wilayah | Pengurus Besar | CRUD akun Komwil |
| Komisariat Daerah | Komisariat Wilayah | CRUD akun Komda |
| Akun Sekolah | Komisariat Daerah | CRUD akun operator sekolah |

---

## 13. Background Jobs (Queue)
**File:** `app/Jobs/`

- `ProcessMuridBulkFile` — memproses file Excel import murid secara async
- `ProcessGuruBulkFile` — memproses file Excel import guru secara async

---

## 14. API Endpoint Publik (Tanpa Autentikasi)
- `GET /api/cari-nik-murid` — mencari murid berdasarkan NIK (untuk form validasi alumni)
- `GET /api/kabupaten-by-provinsi` — mengambil daftar kabupaten berdasarkan provinsi

---

## Ringkasan Modul & Estimasi Kompleksitas

| No | Modul | Kompleksitas |
|---|---|---|
| 1 | Autentikasi & Profil | Rendah |
| 2 | Dashboard & Statistik (Livewire) | Tinggi |
| 3 | Master Data Wilayah (Provinsi & Kabupaten) | Rendah |
| 4 | Manajemen Sekolah | Sedang |
| 5 | Manajemen Sekolah External | Rendah |
| 6 | Manajemen Murid Global | Sedang |
| 7 | Manajemen Murid per Sekolah (+ Import/Export Excel) | Tinggi |
| 8 | Manajemen Guru Global | Sedang |
| 9 | Manajemen Guru per Sekolah (+ Import Excel) | Tinggi |
| 10 | Manajemen Alumni (+ Import Excel) | Sedang |
| 11 | Validasi Alumni (Self-Service Publik + Approve) | Sedang |
| 12 | Manajemen User & Akses (Role-Based) | Sedang |
| 13 | Background Jobs (Queue) | Rendah |
| 14 | API Endpoint Publik | Rendah |