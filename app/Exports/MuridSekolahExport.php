<?php

namespace App\Exports;

use App\Models\Sekolah;
use App\Models\Alamat;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\PatternFill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MuridSekolahExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    private Collection $murid;
    private Sekolah $sekolah;

    public function __construct(Sekolah $sekolah, Collection $murid)
    {
        $this->sekolah = $sekolah;
        $this->murid = $murid;
    }

    /**
     * Get the data to export
     */
    public function collection()
    {
        return $this->murid->map(function ($item, $index) {
            // Get alamat for this murid
            $alamatAsli = Alamat::where('id_murid', $item->id)->where('jenis', Alamat::JENIS_ASLI)->first();
            $alamatDomisili = Alamat::where('id_murid', $item->id)->where('jenis', Alamat::JENIS_DOMISILI)->first();
            $alamatAyah = Alamat::where('id_murid', $item->id)->where('jenis', Alamat::JENIS_AYAH)->first();
            $alamatIbu = Alamat::where('id_murid', $item->id)->where('jenis', Alamat::JENIS_IBU)->first();

            return [
                'No.' => $index + 1,
                'Nama Lengkap' => $item->nama ?? '-',
                'NISN' => $item->nisn ?? '-',
                'NIK' => $item->nik ?? '-',
                'Jenis Kelamin' => $item->jenis_kelamin_label ?? '-',
                'Tempat Lahir' => $item->tempat_lahir ?? '-',
                'Tanggal Lahir' => $item->tanggal_lahir ? $item->tanggal_lahir->format('d/m/Y') : '-',
                'Kontak WA/HP' => $item->kontak_wa_hp ?? '-',
                'Email' => $item->kontak_email ?? '-',
                'Nama Ayah' => $item->nama_ayah ?? '-',
                'Nomor HP Ayah' => $item->nomor_hp_ayah ?? '-',
                'Nama Ibu' => $item->nama_ibu ?? '-',
                'Nomor HP Ibu' => $item->nomor_hp_ibu ?? '-',
                'Alamat Asli' => $this->formatAlamatLengkap($alamatAsli),
                'Alamat Domisili' => $this->formatAlamatLengkap($alamatDomisili),
                'Alamat Ayah' => $this->formatAlamatLengkap($alamatAyah),
                'Alamat Ibu' => $this->formatAlamatLengkap($alamatIbu),
                'Tahun Masuk' => $item->pivot?->tahun_masuk ?? '-',
                'Tahun Keluar' => $item->pivot?->tahun_keluar ?? '-',
                'Kelas' => $item->pivot?->kelas ?? '-',
                'Status Kelulusan' => $this->getStatusKelulusanLabel($item->pivot?->status_kelulusan),
                'Tahun Mutasi Masuk' => $item->pivot?->tahun_mutasi_masuk ?? '-',
                'Alasan Mutasi Masuk' => $item->pivot?->alasan_mutasi_masuk ?? '-',
                'Tahun Mutasi Keluar' => $item->pivot?->tahun_mutasi_keluar ?? '-',
                'Alasan Mutasi Keluar' => $item->pivot?->alasan_mutasi_keluar ?? '-',
            ];
        });
    }

    /**
     * Get the column headings
     */
    public function headings(): array
    {
        return [
            'No.',
            'Nama Lengkap',
            'NISN',
            'NIK',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Kontak WA/HP',
            'Email',
            'Nama Ayah',
            'Nomor HP Ayah',
            'Nama Ibu',
            'Nomor HP Ibu',
            'Alamat Asli',
            'Alamat Domisili',
            'Alamat Ayah',
            'Alamat Ibu',
            'Tahun Masuk',
            'Tahun Keluar',
            'Kelas',
            'Status Kelulusan',
            'Tahun Mutasi Masuk',
            'Alasan Mutasi Masuk',
            'Tahun Mutasi Keluar',
            'Alasan Mutasi Keluar',
        ];
    }

    /**
     * Style the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
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
            'border' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        // Style body rows
        $lastRow = count($this->murid) + 1;
        for ($i = 2; $i <= $lastRow; $i++) {
            $fillColor = ($i % 2 === 0) ? 'F9FAFB' : 'FFFFFF'; // Alternating row colors

            $sheet->getStyle($i . ':' . $i)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $fillColor],
                ],
                'border' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                    'wrapText' => true,
                ],
            ]);
        }

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(40);

        // Set text alignment for all cells
        $sheet->getStyle('A1:Y' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        return [];
    }

    /**
     * Define column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,  // No.
            'B' => 25, // Nama Lengkap
            'C' => 16, // NISN
            'D' => 16, // NIK
            'E' => 16, // Jenis Kelamin
            'F' => 18, // Tempat Lahir
            'G' => 15, // Tanggal Lahir
            'H' => 18, // Kontak WA/HP
            'I' => 20, // Email
            'J' => 18, // Nama Ayah
            'K' => 16, // Nomor HP Ayah
            'L' => 18, // Nama Ibu
            'M' => 16, // Nomor HP Ibu
            'N' => 35, // Alamat Asli
            'O' => 35, // Alamat Domisili
            'P' => 35, // Alamat Ayah
            'Q' => 35, // Alamat Ibu
            'R' => 13, // Tahun Masuk
            'S' => 13, // Tahun Keluar
            'T' => 12, // Kelas
            'U' => 18, // Status Kelulusan
            'V' => 18, // Tahun Mutasi Masuk
            'W' => 20, // Alasan Mutasi Masuk
            'X' => 18, // Tahun Mutasi Keluar
            'Y' => 20, // Alasan Mutasi Keluar
        ];
    }

    /**
     * Get the status kelulusan label
     */
    private function getStatusKelulusanLabel(?string $status): string
    {
        return match ($status) {
            'ya' => 'Sudah Lulus',
            'tidak' => 'Tidak Lulus',
            null => 'Belum Lulus',
            default => $status,
        };
    }

    /**
     * Format alamat lengkap from Alamat model
     */
    private function formatAlamatLengkap(?Alamat $alamat): string
    {
        if (!$alamat) {
            return '-';
        }

        $parts = [];

        if ($alamat->alamat_lengkap) {
            $parts[] = $alamat->alamat_lengkap;
        }

        if ($alamat->kelurahan) {
            $parts[] = 'Kel. ' . $alamat->kelurahan;
        }

        if ($alamat->kecamatan) {
            $parts[] = 'Kec. ' . $alamat->kecamatan;
        }

        if ($alamat->kabupaten) {
            $parts[] = $alamat->kabupaten;
        }

        if ($alamat->provinsi) {
            $parts[] = $alamat->provinsi;
        }

        if ($alamat->kode_pos) {
            $parts[] = $alamat->kode_pos;
        }

        return !empty($parts) ? implode(', ', $parts) : '-';
    }
}
