<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background-color: #fff;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px 25px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 3px solid #10b981;
        }

        .header h1 {
            font-size: 20px;
            margin-bottom: 8px;
            color: #059669;
        }

        .header h2 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }

        .header p {
            font-size: 11px;
            color: #666;
            margin: 3px 0;
        }

        .info-box {
            margin-bottom: 20px;
            padding: 12px 15px;
            background-color: #f3f4f6;
            border-left: 4px solid #10b981;
            border-radius: 4px;
        }

        .info-box p {
            margin: 5px 0;
            font-size: 11px;
        }

        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #10b981;
            color: white;
            border-radius: 4px;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .stats-row {
            display: table-row;
        }

        .stat-card {
            display: table-cell;
            padding: 12px;
            margin: 5px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            text-align: center;
            width: 33%;
        }

        .stat-card .label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .stat-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #059669;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background-color: #10b981;
            color: white;
        }

        table th,
        table td {
            padding: 10px 12px;
            text-align: left;
            border: 1px solid #d1d5db;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        table tbody tr:hover {
            background-color: #f3f4f6;
        }

        .table-number {
            text-align: right;
            font-weight: bold;
        }

        .sub-table {
            margin-left: 20px;
            margin-top: 10px;
            font-size: 10px;
        }

        .sub-table th,
        .sub-table td {
            padding: 6px 8px;
        }

        .sub-table thead {
            background-color: #6ee7b7;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #d1d5db;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }

        .page-break {
            page-break-after: always;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mt-2 {
            margin-top: 15px;
        }

        .mb-2 {
            margin-bottom: 15px;
        }

        .table-container {
            margin-bottom: 20px;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>SISTEM DATABASE NASIONAL ALKHAIRAAT</h1>
            <h2>Laporan Dashboard</h2>
            <p>Dicetak pada: {{ $tanggal_cetak }}</p>
            <p>Oleh: {{ $user->name }} ({{ $user->getRoleNames()->first() }})</p>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <p><strong>Wilayah Naungan:</strong></p>
            @if ($user->isSuperuser() || $user->isPengurusBesar())
                <p style="margin-left: 15px;">Seluruh Indonesia</p>
            @elseif($user->isKomisariatWilayah())
                @php
                    $nauanganProvinsiList = $user->editorLists
                        ->pluck('sekolah.kabupaten.provinsi.nama_provinsi')
                        ->filter()
                        ->unique()
                        ->sort()
                        ->values();
                @endphp
                @if ($nauanganProvinsiList->isNotEmpty())
                    <ul style="margin-left: 30px; margin-top: 5px; margin-bottom: 0;">
                        @foreach ($nauanganProvinsiList as $namaProvinsi)
                            <li>{{ $namaProvinsi }}</li>
                        @endforeach
                    </ul>
                @else
                    <p style="margin-left: 15px;">-</p>
                @endif
            @elseif($user->isKomisariatDaerah())
                @php
                    $nauanganKabupatenList = $user->editorLists
                        ->pluck('sekolah.kabupaten.nama_kabupaten')
                        ->filter()
                        ->unique()
                        ->sort()
                        ->values();
                @endphp
                @if ($nauanganKabupatenList->isNotEmpty())
                    <ul style="margin-left: 30px; margin-top: 5px; margin-bottom: 0;">
                        @foreach ($nauanganKabupatenList as $namaKabupaten)
                            <li>{{ $namaKabupaten }}</li>
                        @endforeach
                    </ul>
                @else
                    <p style="margin-left: 15px;">-</p>
                @endif
            @else
                @php
                    $nauanganSekolahList = $user->editorLists->pluck('sekolah.nama')->filter()->sort()->values();
                @endphp
                @if ($nauanganSekolahList->isNotEmpty())
                    <ul style="margin-left: 30px; margin-top: 5px; margin-bottom: 0;">
                        @foreach ($nauanganSekolahList as $namaSekolah)
                            <li>{{ $namaSekolah }}</li>
                        @endforeach
                    </ul>
                @else
                    <p style="margin-left: 15px;">-</p>
                @endif
            @endif
        </div>

        <!-- Statistik Sekolah (hanya tampil jika user menaungi lebih dari 1 sekolah) -->
        @if ($jumlah_sekolah_naungan > 1)
            <div class="section">
                <div class="section-title">Statistik Sekolah</div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th class="text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Total Sekolah</strong></td>
                            <td class="table-number">{{ number_format($sekolah['total']) }}</td>
                        </tr>
                        <tr>
                            <td>Sekolah Aktif</td>
                            <td class="table-number">{{ number_format($sekolah['aktif']) }}</td>
                        </tr>
                        <tr>
                            <td>Sekolah Tidak Aktif</td>
                            <td class="table-number">{{ number_format($sekolah['tidak_aktif']) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Jenis Sekolah</th>
                            <th class="text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>RA/TK</td>
                            <td class="table-number">{{ number_format($sekolah['jenis']['ra_tk']) }}</td>
                        </tr>
                        <tr>
                            <td>MI/SD</td>
                            <td class="table-number">{{ number_format($sekolah['jenis']['mi_sd']) }}</td>
                        </tr>
                        <tr>
                            <td>MTs/SMP</td>
                            <td class="table-number">{{ number_format($sekolah['jenis']['mts_smp']) }}</td>
                        </tr>
                        <tr>
                            <td>MA/SMA</td>
                            <td class="table-number">{{ number_format($sekolah['jenis']['ma_sma']) }}</td>
                        </tr>
                        <tr>
                            <td>Perguruan Tinggi</td>
                            <td class="table-number">{{ number_format($sekolah['jenis']['pt']) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Bentuk Pendidikan</th>
                            <th class="text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sekolah Umum</td>
                            <td class="table-number">{{ number_format($sekolah['bentuk']['umum']) }}</td>
                        </tr>
                        <tr>
                            <td>Pondok Pesantren</td>
                            <td class="table-number">{{ number_format($sekolah['bentuk']['ponpes']) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Statistik Murid & Guru -->
        <div class="section">
            <div class="section-title">Statistik Murid</div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th class="text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Total Murid</strong></td>
                            <td class="table-number">{{ number_format($murid_guru['murid']['total']) }}</td>
                        </tr>
                        <tr>
                            <td>Laki-laki</td>
                            <td class="table-number">{{ number_format($murid_guru['murid']['laki_laki']) }}</td>
                        </tr>
                        <tr>
                            <td>Perempuan</td>
                            <td class="table-number">{{ number_format($murid_guru['murid']['perempuan']) }}</td>
                        </tr>
                        <tr>
                            <td>Murid Aktif (Belum Lulus)</td>
                            <td class="table-number">{{ number_format($murid_guru['murid']['belum_lulus']) }}</td>
                        </tr>
                        <tr>
                            <td>Lulusan</td>
                            <td class="table-number">{{ number_format($murid_guru['murid']['lulus']) }}</td>
                        </tr>
                        <tr>
                            <td>Tidak Lulus</td>
                            <td class="table-number">{{ number_format($murid_guru['murid']['tidak_lulus']) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Statistik Guru</div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th class="text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Total Guru</strong></td>
                            <td class="table-number">{{ number_format($murid_guru['guru']['total']) }}</td>
                        </tr>
                        <tr>
                            <td>Guru Aktif</td>
                            <td class="table-number">{{ number_format($murid_guru['guru']['aktif']) }}</td>
                        </tr>
                        <tr>
                            <td>Guru Non-Aktif</td>
                            <td class="table-number">{{ number_format($murid_guru['guru']['non_aktif']) }}</td>
                        </tr>
                        <tr>
                            <td>Laki-laki</td>
                            <td class="table-number">{{ number_format($murid_guru['guru']['laki_laki']) }}</td>
                        </tr>
                        <tr>
                            <td>Perempuan</td>
                            <td class="table-number">{{ number_format($murid_guru['guru']['perempuan']) }}</td>
                        </tr>
                        <tr>
                            <td>PNS</td>
                            <td class="table-number">{{ number_format($murid_guru['guru']['pns']) }}</td>
                        </tr>
                        <tr>
                            <td>Non-PNS</td>
                            <td class="table-number">{{ number_format($murid_guru['guru']['non_pns']) }}</td>
                        </tr>
                        <tr>
                            <td>PPPK</td>
                            <td class="table-number">{{ number_format($murid_guru['guru']['pppk']) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Data per Provinsi (hanya untuk super/komisariat) -->
        @if (isset($provinsi) && $provinsi->isNotEmpty())
            <div class="page-break"></div>

            <!-- Summary Alumni per Provinsi -->
            <div class="section">
                <div class="section-title">Ringkasan Murid & Alumni per Provinsi</div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Provinsi</th>
                                <th class="text-right">Total Murid</th>
                                <th class="text-right">Total Alumni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $grandTotalMurid = 0;
                                $grandTotalAlumni = 0;
                            @endphp
                            @foreach ($provinsi as $prov)
                                @php
                                    $grandTotalMurid += $prov['total_murid'];
                                    $grandTotalAlumni += $prov['total_alumni'];
                                @endphp
                                <tr>
                                    <td><strong>{{ $prov['nama'] }}</strong></td>
                                    <td class="table-number">{{ number_format($prov['total_murid']) }}</td>
                                    <td class="table-number">{{ number_format($prov['total_alumni']) }}</td>
                                </tr>
                            @endforeach
                            <tr style="background-color: #d1fae5; font-weight: bold;">
                                <td><strong>TOTAL SELURUH WILAYAH</strong></td>
                                <td class="table-number">{{ number_format($grandTotalMurid) }}</td>
                                <td class="table-number">{{ number_format($grandTotalAlumni) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detail per Kabupaten -->
            <div class="section">
                <div class="section-title">Detail Murid & Alumni per Kabupaten/Kota</div>

                @foreach ($provinsi as $prov)
                    <div class="table-container mb-2">
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="3" style="background-color: #059669;">
                                        {{ $prov['nama'] }}
                                    </th>
                                </tr>
                                <tr>
                                    <th>Kabupaten/Kota</th>
                                    <th class="text-right">Total Murid</th>
                                    <th class="text-right">Total Alumni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prov['kabupatens'] as $kab)
                                    @php
                                        $persenKabAlumni =
                                            $kab['murid_count'] > 0
                                                ? ($kab['alumni_count'] / $kab['murid_count']) * 100
                                                : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $kab['nama'] }}</td>
                                        <td class="table-number">{{ number_format($kab['murid_count']) }}</td>
                                        <td class="table-number">{{ number_format($kab['alumni_count']) }}</td>
                                    </tr>
                                @endforeach
                                <tr style="background-color: #d1fae5; font-weight: bold;">
                                    <td><strong>Total {{ $prov['nama'] }}</strong></td>
                                    <td class="table-number">{{ number_format($prov['total_murid']) }}</td>
                                    <td class="table-number">{{ number_format($prov['total_alumni']) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini dicetak secara otomatis dari Sistem Database Nasional Alkhairaat</p>
            <p>&copy; {{ date('Y') }} Perguruan Islam Alkhairaat. Semua hak cipta dilindungi.</p>
        </div>
    </div>
</body>

</html>
