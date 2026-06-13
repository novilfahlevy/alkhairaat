<?php

namespace App\Exports;

use App\Models\Alamat;
use App\Models\Guru;
use App\Models\Sekolah;
use App\Support\GuruSekolahQueryFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class GuruSekolahExport extends DefaultValueBinder implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents, WithCustomValueBinder
{
    private Sekolah $sekolah;

    private ?Request $request;

    private int $rowCount = 0;

    public function __construct(Sekolah $sekolah, ?Request $request = null)
    {
        $this->sekolah = $sekolah;
        $this->request = $request;
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($this->shouldBindAsString($value)) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    private function shouldBindAsString($value): bool
    {
        if (! is_scalar($value) || $value === '' || $value === null) {
            return false;
        }

        $stringValue = (string) $value;

        return ctype_digit($stringValue) && strlen($stringValue) >= 10;
    }

    public function query(): Builder
    {
        $query = Guru::query()
            ->join('jabatan_guru', 'guru.id', '=', 'jabatan_guru.id_guru')
            ->where('jabatan_guru.id_sekolah', $this->sekolah->id)
            ->select(
                'guru.*',
                'jabatan_guru.jenis_jabatan',
                'jabatan_guru.keterangan_jabatan'
            );

        if ($this->request) {
            GuruSekolahQueryFilters::applySearch($query, $this->request->input('search'));
        }

        $this->rowCount = (clone $query)->count();

        return $query;
    }

    public function map($guru): array
    {
        $alamatRecords = Alamat::where('id_guru', $guru->id)
            ->whereIn('jenis', [Alamat::JENIS_ASLI, Alamat::JENIS_DOMISILI])
            ->get()
            ->keyBy('jenis');

        $alamatAsli = $alamatRecords->get(Alamat::JENIS_ASLI);
        $alamatDomisili = $alamatRecords->get(Alamat::JENIS_DOMISILI);

        return [
            // Data Pribadi
            $guru->nama ?? '',
            $guru->nik ?? '',
            $guru->jenis_kelamin ?? '',
            $this->getStatusLabel($guru->status),
            $guru->status_kepegawaian ?? '',
            $this->getStatusPerkawinanLabel($guru->status_perkawinan),
            $guru->nama_gelar_depan ?? '',
            $guru->nama_gelar_belakang ?? '',
            $guru->tempat_lahir ?? '',
            $guru->tanggal_lahir ? $guru->tanggal_lahir->format('d/m/Y') : '',
            $guru->npk ?? '',
            $guru->nuptk ?? '',
            $guru->kontak_wa_hp ?? '',
            $guru->kontak_email ?? '',
            '', // Separator

            // Data Jabatan
            $guru->jenis_jabatan ?? '',
            $guru->keterangan_jabatan ?? '',
            '', // Separator

            // Data Rekening
            $guru->nomor_rekening ?? '',
            $guru->rekening_atas_nama ?? '',
            $guru->bank_rekening ?? '',
            '', // Separator

            // Alamat Asli
            $alamatAsli?->provinsi ?? '',
            $alamatAsli?->kabupaten ?? '',
            $alamatAsli?->kecamatan ?? '',
            $alamatAsli?->kelurahan ?? '',
            $alamatAsli?->rt ?? '',
            $alamatAsli?->rw ?? '',
            $alamatAsli?->kode_pos ?? '',
            $alamatAsli?->alamat_lengkap ?? '',
            '', // Separator

            // Alamat Domisili
            $alamatDomisili?->provinsi ?? '',
            $alamatDomisili?->kabupaten ?? '',
            $alamatDomisili?->kecamatan ?? '',
            $alamatDomisili?->kelurahan ?? '',
            $alamatDomisili?->rt ?? '',
            $alamatDomisili?->rw ?? '',
            $alamatDomisili?->kode_pos ?? '',
            $alamatDomisili?->alamat_lengkap ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nama', 'NIK', 'Jenis Kelamin', 'Status', 'Status Kepegawaian', 'Status Perkawinan',
            'Gelar Depan', 'Gelar Belakang', 'Tempat Lahir', 'Tanggal Lahir', 'NPK', 'NUPTK',
            'Kontak WA/HP', 'Email',
            '',

            'Jenis Jabatan', 'Keterangan Jabatan',
            '',

            'Nomor Rekening', 'Rekening Atas Nama', 'Bank Rekening',
            '',

            'Provinsi', 'Kabupaten', 'Kecamatan', 'Kelurahan', 'RT', 'RW', 'Kode Pos', 'Alamat Lengkap',
            '',

            'Provinsi', 'Kabupaten', 'Kecamatan', 'Kelurahan', 'RT', 'RW', 'Kode Pos', 'Alamat Lengkap',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 1);

                $groupHeaders = $this->getGroupHeaders();
                $col = 'A';
                foreach ($groupHeaders as $header) {
                    $sheet->setCellValue($col . '1', $header);
                    $col++;
                }

                $this->applyStyles($sheet);

                $textColumns = ['B', 'K', 'L', 'M', 'S', 'AA', 'AJ'];
                $lastRow = $this->rowCount + 2;

                foreach ($textColumns as $column) {
                    $sheet->getStyle("{$column}3:{$column}{$lastRow}")
                        ->getNumberFormat()
                        ->setFormatCode('@');
                }
            },
        ];
    }

    private function applyStyles(Worksheet $sheet): void
    {
        $lastRow = $this->rowCount + 2;

        $sheet->getStyle('1:1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E40AF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
        ]);

        $sheet->getStyle('2:2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3B82F6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(30);

        foreach (['O', 'R', 'V', 'AE'] as $col) {
            $sheet->getStyle($col . '1:' . $col . $lastRow)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']],
            ]);
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    private function getGroupHeaders(): array
    {
        return [
            'Data Pribadi', '', '', '', '', '', '', '', '', '', '', '', '', '',
            '',
            'Data Jabatan', '',
            '',
            'Data Rekening', '', '',
            '',
            'Alamat Asli', '', '', '', '', '', '', '',
            '',
            'Alamat Domisili', '', '', '', '', '', '', '',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25, 'B' => 18, 'C' => 14, 'D' => 12, 'E' => 18, 'F' => 16,
            'G' => 12, 'H' => 14, 'I' => 15, 'J' => 12, 'K' => 12, 'L' => 14,
            'M' => 15, 'N' => 22, 'O' => 2,
            'P' => 18, 'Q' => 20, 'R' => 2,
            'S' => 18, 'T' => 22, 'U' => 16, 'V' => 2,
            'W' => 15, 'X' => 15, 'Y' => 12, 'Z' => 12, 'AA' => 8, 'AB' => 5,
            'AC' => 5, 'AD' => 30, 'AE' => 2,
            'AF' => 15, 'AG' => 15, 'AH' => 12, 'AI' => 12, 'AJ' => 8, 'AK' => 5,
            'AL' => 5, 'AM' => 30,
        ];
    }

    private function getStatusLabel(?string $status): string
    {
        return match ($status) {
            Guru::STATUS_AKTIF => 'Aktif',
            Guru::STATUS_TIDAK => 'Tidak Aktif',
            default => $status ?? '',
        };
    }

    private function getStatusPerkawinanLabel(?string $status): string
    {
        if (! $status) {
            return '';
        }

        return Guru::STATUS_PERKAWINAN_OPTIONS[$status] ?? $status;
    }
}
