<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DetailedOrderReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'Product Name',
            'Product Option',
            'Quantity',
            'Unit Price (RM)',
            'Item Total (RM)',
        /*    'Subtotal (RM)',
            'Discount (RM)',*/
            'Order Total (RM)',
            'Delivery Address',
            'Postcode',
        ];
    }

    public function map($order): array
    {
        $rows = [];
        
        foreach ($order->items as $item) {
            $rows[] = [
                $order->order_number,
                $order->user->name,
                $order->created_at->format('Y-m-d H:i:s'),
                ucfirst(str_replace('_', ' ', $order->status)),
                $item->product->name ?? 'N/A',
                $item->option->option ?? 'Standard',
                $item->quantity,
                number_format($item->price, 2),
                number_format($item->price * $item->quantity, 2),
        /*        number_format($order->subtotal, 2),
                number_format($order->discount_amount ?? 0, 2),*/
                number_format($order->total, 2),
                $order->address->address ?? 'N/A',
                $order->address->postal_code ?? 'N/A',
            ];
        }
        
        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}