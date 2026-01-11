<?php

namespace App\Exports;

use App\Models\Sekolah;
use App\Models\Alamat;
use App\Models\Murid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MuridSekolahExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    private Sekolah $sekolah;
    private ?Request $request;
    private int $rowCount = 0;

    /**
     * Chunk size for processing - adjust based on your server's memory
     */
    private const CHUNK_SIZE = 500;

    public function __construct(Sekolah $sekolah, ?Request $request = null)
    {
        $this->sekolah = $sekolah;
        $this->request = $request;
    }

    /**
     * Build the query with eager loading and filters
     */
    public function query(): Builder
    {
        // Build query directly with join to sekolah_murid
        $query = Murid::query()
            ->join('sekolah_murid', 'murid.id', '=', 'sekolah_murid.id_murid')
            ->where('sekolah_murid.id_sekolah', $this->sekolah->id)
            ->select('murid.*', 
                'sekolah_murid.kelas',
                'sekolah_murid.tahun_masuk',
                'sekolah_murid.tahun_keluar',
                'sekolah_murid.status_kelulusan',
                'sekolah_murid.tahun_mutasi_masuk',
                'sekolah_murid.alasan_mutasi_masuk',
                'sekolah_murid.tahun_mutasi_keluar',
                'sekolah_murid.alasan_mutasi_keluar'
            );

        // Apply filters if request is provided
        if ($this->request) {
            // Apply search filter
            if ($this->request->filled('search')) {
                $search = $this->request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('murid.nama', 'like', "%{$search}%")
                      ->orWhere('murid.nisn', 'like', "%{$search}%")
                      ->orWhere('murid.nik', 'like', "%{$search}%");
                });
            }

            // Apply jenis kelamin filter
            if ($this->request->filled('jenis_kelamin')) {
                $query->where('murid.jenis_kelamin', $this->request->input('jenis_kelamin'));
            }

            // Apply status kelulusan filter
            if ($this->request->filled('status_kelulusan')) {
                $status = $this->request->input('status_kelulusan');
                if ($status === 'belum') {
                    $query->whereNull('sekolah_murid.status_kelulusan');
                } else {
                    $query->where('sekolah_murid.status_kelulusan', $status);
                }
            }

            // Apply tahun masuk filter
            if ($this->request->filled('tahun_masuk')) {
                $query->where('sekolah_murid.tahun_masuk', $this->request->input('tahun_masuk'));
            }
        }

        // Count rows for styling (use a separate count query to avoid memory issues)
        $this->rowCount = (clone $query)->count();

        return $query;
    }

    /**
     * Map each row - this is called per chunk, so memory efficient
     */
    public function map($murid): array
    {
        // Fetch alamat in a single query with indexing by jenis
        $alamatRecords = Alamat::where('id_murid', $murid->id)
            ->whereIn('jenis', [Alamat::JENIS_ASLI, Alamat::JENIS_DOMISILI, Alamat::JENIS_AYAH, Alamat::JENIS_IBU])
            ->get()
            ->keyBy('jenis');

        $alamatAsli = $alamatRecords->get(Alamat::JENIS_ASLI);
        $alamatDomisili = $alamatRecords->get(Alamat::JENIS_DOMISILI);
        $alamatAyah = $alamatRecords->get(Alamat::JENIS_AYAH);
        $alamatIbu = $alamatRecords->get(Alamat::JENIS_IBU);

        return [
            // Data Pribadi (8 columns + 1 separator)
            $murid->nisn ?? '',
            $murid->nama ?? '',
            $murid->jenis_kelamin ?? '',
            $murid->nik ?? '',
            $murid->tempat_lahir ?? '',
            $murid->tanggal_lahir ? $murid->tanggal_lahir->format('d/m/Y') : '',
            $murid->kontak_wa_hp ?? '',
            $murid->kontak_email ?? '',
            '', // Separator

            // Data Sekolah dan Pendidikan (8 columns + 1 separator)
            // Data from joined sekolah_murid table
            $murid->kelas ?? '',
            $murid->tahun_masuk ?? '',
            $murid->tahun_keluar ?? '',
            $this->getStatusKelulusanLabel($murid->status_kelulusan),
            $murid->tahun_mutasi_masuk ?? '',
            $murid->alasan_mutasi_masuk ?? '',
            $murid->tahun_mutasi_keluar ?? '',
            $murid->alasan_mutasi_keluar ?? '',
            '', // Separator

            // Data Orang Tua (4 columns + 1 separator)
            $murid->nama_ayah ?? '',
            $murid->nomor_hp_ayah ?? '',
            $murid->nama_ibu ?? '',
            $murid->nomor_hp_ibu ?? '',
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

    /**
     * Column headings (row 2 in final output)
     */
    public function headings(): array
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
     * Register events for adding group header row and applying styles
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Insert a new row at the top for group headers
                $sheet->insertNewRowBefore(1, 1);

                // Add group headers to row 1
                $groupHeaders = $this->getGroupHeaders();
                $col = 'A';
                foreach ($groupHeaders as $header) {
                    $sheet->setCellValue($col . '1', $header);
                    $col++;
                }

                // Apply all styles after row insertion
                $this->applyStyles($sheet);
            },
        ];
    }

    /**
     * Apply styles to the worksheet (called from AfterSheet event)
     */
    private function applyStyles(Worksheet $sheet): void
    {
        $lastRow = $this->rowCount + 2; // +2 for 2 header rows

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
    }

    /**
     * Style the worksheet - empty since we apply styles in AfterSheet event
     */
    public function styles(Worksheet $sheet)
    {
        // Styles are applied in AfterSheet event after row insertion
        return [];
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
