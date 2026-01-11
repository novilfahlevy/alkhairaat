<?php

namespace App\Exports;

use App\Models\Sekolah;
use App\Models\Alamat;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MuridSekolahExport implements FromArray, WithStyles, WithColumnWidths
{
    private Collection $murid;
    private Sekolah $sekolah;

    public function __construct(Sekolah $sekolah, Collection $murid)
    {
        $this->sekolah = $sekolah;
        $this->murid = $murid;
    }

    /**
     * Get the data as array (including headers)
     */
    public function array(): array
    {
        $data = [];

        // Row 1: Group headers
        $data[] = $this->getGroupHeaders();

        // Row 2: Column headers
        $data[] = $this->getColumnHeaders();

        // Data rows
        foreach ($this->murid as $item) {
            $alamatAsli = Alamat::where('id_murid', $item->id)->where('jenis', Alamat::JENIS_ASLI)->first();
            $alamatDomisili = Alamat::where('id_murid', $item->id)->where('jenis', Alamat::JENIS_DOMISILI)->first();
            $alamatAyah = Alamat::where('id_murid', $item->id)->where('jenis', Alamat::JENIS_AYAH)->first();
            $alamatIbu = Alamat::where('id_murid', $item->id)->where('jenis', Alamat::JENIS_IBU)->first();

            $data[] = [
                // Data Pribadi (8 columns + 1 separator)
                $item->nisn ?? '',
                $item->nama ?? '',
                $item->jenis_kelamin ?? '',
                $item->nik ?? '',
                $item->tempat_lahir ?? '',
                $item->tanggal_lahir ? $item->tanggal_lahir->format('d/m/Y') : '',
                $item->kontak_wa_hp ?? '',
                $item->kontak_email ?? '',
                '', // Separator

                // Data Sekolah dan Pendidikan (8 columns + 1 separator)
                $item->pivot?->kelas ?? '',
                $item->pivot?->tahun_masuk ?? '',
                $item->pivot?->tahun_keluar ?? '',
                $this->getStatusKelulusanLabel($item->pivot?->status_kelulusan),
                $item->pivot?->tahun_mutasi_masuk ?? '',
                $item->pivot?->alasan_mutasi_masuk ?? '',
                $item->pivot?->tahun_mutasi_keluar ?? '',
                $item->pivot?->alasan_mutasi_keluar ?? '',
                '', // Separator

                // Data Orang Tua (4 columns + 1 separator)
                $item->nama_ayah ?? '',
                $item->nomor_hp_ayah ?? '',
                $item->nama_ibu ?? '',
                $item->nomor_hp_ibu ?? '',
                '', // Separator

                // Alamat Asli (10 columns + 1 separator)
                $alamatAsli?->provinsi ?? '',
                $alamatAsli?->kabupaten ?? '',
                $alamatAsli?->kecamatan ?? '',
                $alamatAsli?->kelurahan ?? '',
                $alamatAsli?->rt ?? '',
                $alamatAsli?->rw ?? '',
                $alamatAsli?->kode_pos ?? '',
                $alamatAsli?->alamat_lengkap ?? '',
                $alamatAsli?->koordinat_y ?? '', // Latitude
                $alamatAsli?->koordinat_x ?? '', // Longitude
                '', // Separator

                // Alamat Domisili (10 columns + 1 separator)
                $alamatDomisili?->provinsi ?? '',
                $alamatDomisili?->kabupaten ?? '',
                $alamatDomisili?->kecamatan ?? '',
                $alamatDomisili?->kelurahan ?? '',
                $alamatDomisili?->rt ?? '',
                $alamatDomisili?->rw ?? '',
                $alamatDomisili?->kode_pos ?? '',
                $alamatDomisili?->alamat_lengkap ?? '',
                $alamatDomisili?->koordinat_y ?? '', // Latitude
                $alamatDomisili?->koordinat_x ?? '', // Longitude
                '', // Separator

                // Alamat Ayah (10 columns + 1 separator)
                $alamatAyah?->provinsi ?? '',
                $alamatAyah?->kabupaten ?? '',
                $alamatAyah?->kecamatan ?? '',
                $alamatAyah?->kelurahan ?? '',
                $alamatAyah?->rt ?? '',
                $alamatAyah?->rw ?? '',
                $alamatAyah?->kode_pos ?? '',
                $alamatAyah?->alamat_lengkap ?? '',
                $alamatAyah?->koordinat_y ?? '', // Latitude
                $alamatAyah?->koordinat_x ?? '', // Longitude
                '', // Separator

                // Alamat Ibu (10 columns)
                $alamatIbu?->provinsi ?? '',
                $alamatIbu?->kabupaten ?? '',
                $alamatIbu?->kecamatan ?? '',
                $alamatIbu?->kelurahan ?? '',
                $alamatIbu?->rt ?? '',
                $alamatIbu?->rw ?? '',
                $alamatIbu?->kode_pos ?? '',
                $alamatIbu?->alamat_lengkap ?? '',
                $alamatIbu?->koordinat_y ?? '', // Latitude
                $alamatIbu?->koordinat_x ?? '', // Longitude
            ];
        }

        return $data;
    }

    /**
     * Get group headers (row 1)
     */
    private function getGroupHeaders(): array
    {
        return [
            // Data Pribadi (8 columns + 1 separator)
            'Data Pribadi', '', '', '', '', '', '', '',
            '', // Separator

            // Data Sekolah dan Pendidikan (8 columns + 1 separator)
            'Data Sekolah dan Pendidikan', '', '', '', '', '', '', '',
            '', // Separator

            // Data Orang Tua (4 columns + 1 separator)
            'Data Orang Tua', '', '', '',
            '', // Separator

            // Alamat Asli (10 columns + 1 separator)
            'Alamat Asli', '', '', '', '', '', '', '', '', '',
            '', // Separator

            // Alamat Domisili (10 columns + 1 separator)
            'Alamat Domisili', '', '', '', '', '', '', '', '', '',
            '', // Separator

            // Alamat Ayah (10 columns + 1 separator)
            'Alamat Ayah', '', '', '', '', '', '', '', '', '',
            '', // Separator

            // Alamat Ibu (10 columns)
            'Alamat Ibu', '', '', '', '', '', '', '', '', '',
        ];
    }

    /**
     * Get column headers (row 2)
     */
    private function getColumnHeaders(): array
    {
        return [
            // Data Pribadi
            'NISN', 'Nama', 'Jenis Kelamin', 'NIK', 'Tempat Lahir', 'Tanggal Lahir', 'Whatsapp', 'Email',
            '', // Separator

            // Data Sekolah dan Pendidikan
            'Kelas', 'Tahun Masuk', 'Tahun Keluar', 'Status Kelulusan', 'Tahun Mutasi Masuk', 'Alasan Mutasi Masuk', 'Tahun Mutasi Keluar', 'Alasan Mutasi Keluar',
            '', // Separator

            // Data Orang Tua
            'Nama Ayah', 'Nomor HP Ayah', 'Nama Ibu', 'Nomor HP Ibu',
            '', // Separator

            // Alamat Asli
            'Provinsi', 'Kabupaten', 'Kecamatan', 'Kelurahan', 'RT', 'RW', 'Kode Pos', 'Alamat Lengkap', 'Latitude', 'Longitude',
            '', // Separator

            // Alamat Domisili
            'Provinsi', 'Kabupaten', 'Kecamatan', 'Kelurahan', 'RT', 'RW', 'Kode Pos', 'Alamat Lengkap', 'Latitude', 'Longitude',
            '', // Separator

            // Alamat Ayah
            'Provinsi', 'Kabupaten', 'Kecamatan', 'Kelurahan', 'RT', 'RW', 'Kode Pos', 'Alamat Lengkap', 'Latitude', 'Longitude',
            '', // Separator

            // Alamat Ibu
            'Provinsi', 'Kabupaten', 'Kecamatan', 'Kelurahan', 'RT', 'RW', 'Kode Pos', 'Alamat Lengkap', 'Latitude', 'Longitude',
        ];
    }

    /**
     * Style the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        $lastColumn = 'BP'; // Total 68 columns
        $lastRow = count($this->murid) + 2; // +2 for 2 header rows

        // Style row 1 (group headers)
        $sheet->getStyle('1:1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E40AF'], // Dark blue
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        // Style row 2 (column headers)
        $sheet->getStyle('2:2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3B82F6'], // Lighter blue
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        // Style body rows
        for ($i = 3; $i <= $lastRow; $i++) {
            $fillColor = ($i % 2 === 1) ? 'F9FAFB' : 'FFFFFF';

            $sheet->getStyle($i . ':' . $i)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $fillColor],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                ],
            ]);
        }

        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(30);

        // Style separator columns (make them narrower and with different background)
        $separatorColumns = ['I', 'R', 'W', 'AH', 'AS', 'BD', 'BO'];
        foreach ($separatorColumns as $col) {
            $sheet->getStyle($col . '1:' . $col . $lastRow)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E5E7EB'],
                ],
            ]);
        }

        return [];
    }

    /**
     * Define column widths
     */
    public function columnWidths(): array
    {
        return [
            // Data Pribadi
            'A' => 14, // NISN
            'B' => 25, // Nama
            'C' => 14, // Jenis Kelamin
            'D' => 18, // NIK
            'E' => 15, // Tempat Lahir
            'F' => 12, // Tanggal Lahir
            'G' => 15, // Whatsapp
            'H' => 20, // Email
            'I' => 2,  // Separator

            // Data Sekolah dan Pendidikan
            'J' => 10, // Kelas
            'K' => 12, // Tahun Masuk
            'L' => 12, // Tahun Keluar
            'M' => 15, // Status Kelulusan
            'N' => 18, // Tahun Mutasi Masuk
            'O' => 20, // Alasan Mutasi Masuk
            'P' => 18, // Tahun Mutasi Keluar
            'Q' => 20, // Alasan Mutasi Keluar
            'R' => 2,  // Separator

            // Data Orang Tua
            'S' => 20, // Nama Ayah
            'T' => 15, // Nomor HP Ayah
            'U' => 20, // Nama Ibu
            'V' => 15, // Nomor HP Ibu
            'W' => 2,  // Separator

            // Alamat Asli
            'X' => 15, // Provinsi
            'Y' => 15, // Kabupaten
            'Z' => 12, // Kecamatan
            'AA' => 12, // Kelurahan
            'AB' => 5,  // RT
            'AC' => 5,  // RW
            'AD' => 8,  // Kode Pos
            'AE' => 30, // Alamat Lengkap
            'AF' => 10, // Latitude
            'AG' => 10, // Longitude
            'AH' => 2,  // Separator

            // Alamat Domisili
            'AI' => 15, // Provinsi
            'AJ' => 15, // Kabupaten
            'AK' => 12, // Kecamatan
            'AL' => 12, // Kelurahan
            'AM' => 5,  // RT
            'AN' => 5,  // RW
            'AO' => 8,  // Kode Pos
            'AP' => 30, // Alamat Lengkap
            'AQ' => 10, // Latitude
            'AR' => 10, // Longitude
            'AS' => 2,  // Separator

            // Alamat Ayah
            'AT' => 15, // Provinsi
            'AU' => 15, // Kabupaten
            'AV' => 12, // Kecamatan
            'AW' => 12, // Kelurahan
            'AX' => 5,  // RT
            'AY' => 5,  // RW
            'AZ' => 8,  // Kode Pos
            'BA' => 30, // Alamat Lengkap
            'BB' => 10, // Latitude
            'BC' => 10, // Longitude
            'BD' => 2,  // Separator

            // Alamat Ibu
            'BE' => 15, // Provinsi
            'BF' => 15, // Kabupaten
            'BG' => 12, // Kecamatan
            'BH' => 12, // Kelurahan
            'BI' => 5,  // RT
            'BJ' => 5,  // RW
            'BK' => 8,  // Kode Pos
            'BL' => 30, // Alamat Lengkap
            'BM' => 10, // Latitude
            'BN' => 10, // Longitude
        ];
    }

    /**
     * Get the status kelulusan label (matching template format)
     */
    private function getStatusKelulusanLabel(?string $status): string
    {
        return match ($status) {
            'ya' => 'Lulus',
            'tidak' => 'Tidak Lulus',
            null => 'Belum Lulus',
            default => $status,
        };
    }
}
