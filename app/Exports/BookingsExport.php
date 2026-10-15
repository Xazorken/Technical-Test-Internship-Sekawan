<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BookingsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected array $filters;
    protected int $row = 1;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection(): Collection
    {
        $query = Booking::with(['user', 'vehicle', 'driver', 'approvals.approver'])
            ->orderBy('created_at', 'desc');

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('start_datetime', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('start_datetime', '<=', $this->filters['date_to']);
        }
        if (!empty($this->filters['vehicle_id'])) {
            $query->where('vehicle_id', $this->filters['vehicle_id']);
        }

        return $query->get();
    }

    public function title(): string
    {
        return 'Laporan Pemesanan Kendaraan';
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nomor Pemesanan',
            'Tanggal Buat',
            'Pemohon',
            'Kendaraan',
            'Plat Nomor',
            'Driver',
            'Tujuan',
            'Keperluan',
            'Tgl Berangkat',
            'Tgl Kembali',
            'Penumpang',
            'Status',
            'Approver L1',
            'Status L1',
            'Approver L2',
            'Status L2',
            'KM Awal',
            'KM Akhir',
            'BBM (L)',
            'Keterangan',
        ];
    }

    public function map($booking): array
    {
        $approvalL1 = $booking->approvals->where('level', 1)->first();
        $approvalL2 = $booking->approvals->where('level', 2)->first();

        return [
            $this->row++,
            $booking->booking_number,
            $booking->created_at->format('d/m/Y'),
            $booking->user->name ?? '-',
            $booking->vehicle->name ?? '-',
            $booking->vehicle->plate_number ?? '-',
            $booking->driver->name ?? '-',
            $booking->destination,
            $booking->purpose,
            $booking->start_datetime->format('d/m/Y H:i'),
            $booking->end_datetime->format('d/m/Y H:i'),
            $booking->passenger_count,
            $booking->status_label,
            $approvalL1?->approver->name ?? '-',
            $approvalL1?->status_label ?? '-',
            $approvalL2?->approver->name ?? '-',
            $approvalL2?->status_label ?? '-',
            $booking->odometer_start ?? '-',
            $booking->odometer_end ?? '-',
            $booking->fuel_used ?? '-',
            $booking->notes ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E3A5F'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}
