<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $orders;
    protected $startDate;
    protected $endDate;
    protected $statuses;

    public function __construct($orders, $startDate, $endDate, $statuses)
    {
        $this->orders = $orders;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->statuses = $statuses;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'Order Number',
            'Customer Name',
            'Order Date',
            'Status',
            'Subtotal (RM)',
            'Delivery Fee (RM)',
            'Discount (RM)',
            'Total (RM)',
/*            'Payment Method',
            'Payment Status',*/
            'Item Count',
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_number,
            $order->user->name,
            $order->created_at->format('Y-m-d H:i:s'),
            ucfirst(str_replace('_', ' ', $order->status)),
            number_format($order->subtotal, 2),
            number_format($order->delivery_fee, 2),
            number_format($order->discount_amount ?? 0, 2),
            number_format($order->total, 2),
          /*  ucfirst($order->payment_method),
            ucfirst($order->payment_status),*/
            $order->items->sum('quantity'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}