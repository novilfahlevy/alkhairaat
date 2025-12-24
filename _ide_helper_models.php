<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int|null $id_murid
 * @property int|null $id_sekolah
 * @property int|null $id_guru
 * @property string $jenis
 * @property string|null $provinsi
 * @property string|null $kabupaten
 * @property string|null $kecamatan
 * @property string|null $kelurahan
 * @property string|null $rt
 * @property string|null $rw
 * @property string|null $kode_pos
 * @property string|null $alamat_lengkap
 * @property string|null $koordinat_x
 * @property string|null $koordinat_y
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $guru
 * @property-read \App\Models\Murid|null $murid
 * @property-read \App\Models\Sekolah|null $sekolah
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat byJenis(string $jenis)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat forMurid(int $muridId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat forSekolah(int $sekolahId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereAlamatLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereIdGuru($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereIdMurid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereIdSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereKelurahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereKodePos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereKoordinatX($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereKoordinatY($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereUpdatedAt($value)
 */
	class Alamat extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_murid
 * @property int $tahun_lulus
 * @property string|null $angkatan
 * @property string|null $kontak
 * @property string|null $email
 * @property string|null $alamat_sekarang
 * @property string|null $lanjutan_studi Jenjang pendidikan lanjutan: S1, S2, S3, D3, dll
 * @property string|null $nama_institusi Nama universitas/institusi
 * @property string|null $jurusan
 * @property string|null $pekerjaan
 * @property string|null $nama_perusahaan
 * @property string|null $kota_perusahaan
 * @property string|null $riwayat_pekerjaan
 * @property string|null $jabatan
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $nama
 * @property-read string|null $nis
 * @property-read \App\Models\Murid|null $murid
 * @property-read \App\Models\Sekolah|null $sekolah
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni bekerja()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni byAngkatan(string $angkatan)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni bySekolah(int $sekolahId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni byTahunLulus(int $tahun)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni melanjutkanStudi()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereAlamatSekarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereAngkatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereIdMurid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereJurusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereKontak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereKotaPerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereLanjutanStudi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereNamaInstitusi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereNamaPerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni wherePekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereRiwayatPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereTahunLulus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereUpdatedAt($value)
 */
	class Alumni extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string|null $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sekolah> $sekolah
 * @property-read int|null $sekolah_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan whereUpdatedAt($value)
 */
	class BentukPendidikan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_user
 * @property int $id_sekolah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Sekolah $sekolah
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList whereIdSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EditorList whereUpdatedAt($value)
 */
	class EditorList extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_sekolah
 * @property string $image_path
 * @property string|null $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Sekolah $sekolah
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah whereIdSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah whereUpdatedAt($value)
 */
	class GaleriSekolah extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $status
 * @property string|null $nama_gelar_depan
 * @property string $nama
 * @property string|null $nama_gelar_belakang
 * @property string|null $tempat_lahir
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir
 * @property string $jenis_kelamin
 * @property string|null $status_perkawinan
 * @property string|null $nik
 * @property string|null $status_kepegawaian
 * @property string|null $npk
 * @property string|null $nuptk
 * @property string|null $kontak_wa_hp
 * @property string|null $kontak_email
 * @property string|null $nomor_rekening
 * @property string|null $rekening_atas_nama
 * @property string|null $bank_rekening
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Alamat> $alamatList
 * @property-read int|null $alamat_list_count
 * @property-read string $full_name
 * @property-read string $jenis_kelamin_label
 * @property-read string $nama_lengkap
 * @property-read string $status_kepegawaian_label
 * @property-read string $status_label
 * @property-read string $status_perkawinan_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JabatanGuru> $jabatanGuru
 * @property-read int|null $jabatan_guru_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru byJenisKelamin(string $jenisKelamin)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru byStatusKepegawaian(string $status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru tidakAktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereBankRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereKontakEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereKontakWaHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNamaGelarBelakang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNamaGelarDepan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNomorRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNpk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNuptk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereRekeningAtasNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereStatusKepegawaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereStatusPerkawinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereUpdatedAt($value)
 */
	class Guru extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_guru
 * @property int $id_sekolah
 * @property string $jenis_jabatan
 * @property string|null $keterangan_jabatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $jenis_jabatan_label
 * @property-read \App\Models\Guru $guru
 * @property-read \App\Models\Sekolah $sekolah
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru byGuru(int $guruId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru byJenisJabatan(string $jenisJabatan)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru bySekolah(int $sekolahId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru guru()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru kepalaSekolah()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru whereIdGuru($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru whereIdSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru whereJenisJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru whereKeteranganJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JabatanGuru whereUpdatedAt($value)
 */
	class JabatanGuru extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kode_jenis
 * @property string $nama_jenis
 * @property string|null $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sekolah> $sekolah
 * @property-read int|null $sekolah_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah whereKodeJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah whereNamaJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah whereUpdatedAt($value)
 */
	class JenisSekolah extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kode_kabupaten
 * @property string $nama_kabupaten
 * @property int $id_provinsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $nama
 * @property-read \App\Models\Provinsi $provinsi
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sekolah> $sekolah
 * @property-read int|null $sekolah_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten naungan()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten whereIdProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten whereKodeKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten whereNamaKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten whereUpdatedAt($value)
 */
	class Kabupaten extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nisn
 * @property string|null $kontak_wa_hp
 * @property string|null $kontak_email
 * @property string $nama
 * @property string|null $nik
 * @property string|null $tempat_lahir
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir
 * @property string $jenis_kelamin
 * @property string|null $nama_ayah
 * @property string|null $nomor_hp_ayah
 * @property string|null $nama_ibu
 * @property string|null $nomor_hp_ibu
 * @property bool $status_alumni
 * @property \Illuminate\Support\Carbon $tanggal_update_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $full_name
 * @property-read string $jenis_kelamin_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sekolah> $sekolah
 * @property-read int|null $sekolah_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SekolahMurid> $sekolahMurid
 * @property-read int|null $sekolah_murid_count
 * @property-read \App\Models\ValidasiAlumni|null $validasiAlumni
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid alumni()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid byJenisKelamin(string $jenisKelamin)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid nonAlumni()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereKontakEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereKontakWaHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereNamaAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereNamaIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereNisn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereNomorHpAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereNomorHpIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereStatusAlumni($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereTanggalUpdateData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereUpdatedAt($value)
 */
	class Murid extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kode_provinsi
 * @property string $nama_provinsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kabupaten> $kabupaten
 * @property-read int|null $kabupaten_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi naungan()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi whereKodeProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi whereNamaProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi whereUpdatedAt($value)
 */
	class Provinsi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kode_sekolah
 * @property string|null $jenis_sekolah
 * @property string|null $bentuk_pendidikan
 * @property string|null $no_npsn
 * @property string $nama
 * @property string $status
 * @property int|null $id_kabupaten
 * @property string|null $kecamatan
 * @property string|null $alamat
 * @property string|null $telepon
 * @property string|null $email
 * @property string|null $website
 * @property string|null $nomor_rekening
 * @property string|null $rekening_atas_nama
 * @property string|null $bank_rekening
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Alamat> $alamatList
 * @property-read int|null $alamat_list_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EditorList> $editorLists
 * @property-read int|null $editor_lists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GaleriSekolah> $galeri
 * @property-read int|null $galeri_count
 * @property-read string $bentuk_pendidikan_label
 * @property-read string $jenis_sekolah_label
 * @property-read mixed $provinsi
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JabatanGuru> $jabatanGuru
 * @property-read int|null $jabatan_guru_count
 * @property-read \App\Models\Kabupaten|null $kabupaten
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Murid> $murid
 * @property-read int|null $murid_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SekolahMurid> $sekolahMurid
 * @property-read int|null $sekolah_murid_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah naungan()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereBankRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereBentukPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereIdKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereJenisSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereKodeSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereNoNpsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereNomorRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereRekeningAtasNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereWebsite($value)
 */
	class Sekolah extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $jenis_sekolah
 * @property string|null $bentuk_pendidikan
 * @property string $nama_sekolah
 * @property string $kota_sekolah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $bentuk_pendidikan_label
 * @property-read string $jenis_sekolah_label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahExternal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahExternal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahExternal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahExternal whereBentukPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahExternal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahExternal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahExternal whereJenisSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahExternal whereKotaSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahExternal whereNamaSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahExternal whereUpdatedAt($value)
 */
	class SekolahExternal extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_murid
 * @property int|null $id_sekolah
 * @property int|null $id_sekolah_external
 * @property int $tahun_masuk
 * @property int|null $tahun_keluar
 * @property int|null $tahun_mutasi_masuk
 * @property string|null $alasan_mutasi_masuk
 * @property int|null $tahun_mutasi_keluar
 * @property string|null $alasan_mutasi_keluar
 * @property string|null $kelas
 * @property string|null $status_kelulusan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $status_kelulusan_label
 * @property-read \App\Models\Murid $murid
 * @property-read \App\Models\Sekolah|null $sekolah
 * @property-read \App\Models\SekolahExternal|null $sekolahExternal
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid byMurid(int $muridId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid bySekolah(int $sekolahId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid byStatusKelulusan(string $status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid byTahunMasuk(int $tahun)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid lulus()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereAlasanMutasiKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereAlasanMutasiMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereIdMurid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereIdSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereIdSekolahExternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereStatusKelulusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereTahunKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereTahunMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereTahunMutasiKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereTahunMutasiMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereUpdatedAt($value)
 */
	class SekolahMurid extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EditorList> $editorLists
 * @property-read int|null $editor_lists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kabupaten> $kabupaten
 * @property-read int|null $kabupaten_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User byRole(string $role)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_murid
 * @property string|null $profesi_sekarang
 * @property string|null $nama_tempat_kerja
 * @property string|null $kota_tempat_kerja
 * @property string|null $riwayat_pekerjaan
 * @property string|null $kontak_wa
 * @property string|null $kontak_email
 * @property string $update_alamat_sekarang
 * @property \Illuminate\Support\Carbon $tanggal_update_data_alumni
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $full_info
 * @property-read \App\Models\Murid $murid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni byKota(string $kota)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni byMurid(int $muridId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni byProfesi(string $profesi)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni recentUpdates(int $days = 30)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereIdMurid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereKontakEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereKontakWa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereKotaTempatKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereNamaTempatKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereProfesiSekarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereRiwayatPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereTanggalUpdateDataAlumni($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereUpdateAlamatSekarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereUpdatedAt($value)
 */
	class ValidasiAlumni extends \Eloquent {}
}

