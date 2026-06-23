<?php

use App\Livewire\MuridGuruCountsWidget;
use App\Models\Murid;
use App\Models\Scopes\MuridNauanganScope;
use App\Models\Scopes\NauanganSekolahScope;
use App\Models\Sekolah;
use App\Models\SekolahMurid;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMockingConsoleOutput();
    $this->seed(RolePermissionSeeder::class);

    $provinsiId = \Illuminate\Support\Facades\DB::table('provinsi')->insertGetId([
        'kode_provinsi' => '72',
        'nama_provinsi' => 'Provinsi Uji',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $kabupatenId = \Illuminate\Support\Facades\DB::table('kabupaten')->insertGetId([
        'kode_kabupaten' => '7201',
        'nama_kabupaten' => 'Kabupaten Uji',
        'id_provinsi' => $provinsiId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->kabupatenId = $kabupatenId;
});

function createSuperuser(): User
{
    $user = User::factory()->create();
    $user->assignRole(User::ROLE_SUPERUSER);

    return $user;
}

function createMuridRecord(string $nisn, string $nama = 'Murid Uji'): Murid
{
    return Murid::withoutGlobalScope(MuridNauanganScope::class)->create([
        'nisn' => $nisn,
        'nama' => $nama,
        'jenis_kelamin' => Murid::JENIS_KELAMIN_LAKI,
        'status_alumni' => false,
        'tanggal_update_data' => now(),
    ]);
}

test('widget menampilkan jumlah murid belum terdaftar sekolah', function () {
    $user = createSuperuser();
    $this->actingAs($user);

    $sekolah = Sekolah::withoutGlobalScope(NauanganSekolahScope::class)->create([
        'kode_sekolah' => 'TST-001',
        'no_npsn' => '99999001',
        'nama' => 'Sekolah Uji',
        'status' => Sekolah::STATUS_AKTIF,
        'jenis_sekolah' => Sekolah::JENIS_SEKOLAH_MI_SD,
        'bentuk_pendidikan' => Sekolah::BENTUK_PENDIDIKAN_UMUM,
        'id_kabupaten' => $this->kabupatenId,
    ]);

    $muridTerdaftar = createMuridRecord('1000000001', 'Murid Terdaftar');
    createMuridRecord('1000000002', 'Murid Belum Terdaftar');

    SekolahMurid::create([
        'id_murid' => $muridTerdaftar->id,
        'id_sekolah' => $sekolah->id,
        'tahun_masuk' => 2024,
        'status_kelulusan' => null,
    ]);

    expect(Murid::count())->toBe(2);
    expect(Murid::doesntHave('sekolahMurid')->count())->toBe(1);
    expect(SekolahMurid::whereNull('status_kelulusan')->count())->toBe(1);

    Livewire::test(MuridGuruCountsWidget::class)
        ->assertSee('Belum Terdaftar Sekolah')
        ->assertSeeHtml('>1</p>', false);
});

test('total murid sama dengan jumlah aktif lulus tidak lulus dan belum terdaftar', function () {
    $user = createSuperuser();
    $this->actingAs($user);

    $sekolah = Sekolah::withoutGlobalScope(NauanganSekolahScope::class)->create([
        'kode_sekolah' => 'TST-002',
        'no_npsn' => '99999002',
        'nama' => 'Sekolah Uji Dua',
        'status' => Sekolah::STATUS_AKTIF,
        'jenis_sekolah' => Sekolah::JENIS_SEKOLAH_MI_SD,
        'bentuk_pendidikan' => Sekolah::BENTUK_PENDIDIKAN_UMUM,
        'id_kabupaten' => $this->kabupatenId,
    ]);

    $aktif = createMuridRecord('2000000001');
    $belumTerdaftar = createMuridRecord('2000000002');

    SekolahMurid::create([
        'id_murid' => $aktif->id,
        'id_sekolah' => $sekolah->id,
        'tahun_masuk' => 2024,
        'status_kelulusan' => null,
    ]);

    $total = Murid::count();
    $belumTerdaftarCount = Murid::doesntHave('sekolahMurid')->count();
    $aktifCount = SekolahMurid::whereNull('status_kelulusan')->count();
    $lulusCount = SekolahMurid::where('status_kelulusan', SekolahMurid::STATUS_LULUS_YA)->count();
    $tidakLulusCount = SekolahMurid::where('status_kelulusan', SekolahMurid::STATUS_LULUS_TIDAK)->count();

    expect($total)->toBe($aktifCount + $lulusCount + $tidakLulusCount + $belumTerdaftarCount);
    expect($belumTerdaftarCount)->toBe(1);
    expect($belumTerdaftar->sekolahMurid)->toBeEmpty();
});
