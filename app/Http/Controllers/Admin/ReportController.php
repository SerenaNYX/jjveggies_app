<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;
use App\Exports\DetailedOrderReportExport;

class ReportController extends Controller
{
    public function index()
    {
        // Get data for the last 60 days (2 months)
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        
        $dailyData = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['delivered', 'completed'])
            ->selectRaw('
                DATE(created_at) as date,
                MONTH(created_at) as month,
                SUM(total) as total_sales,
                COUNT(*) as order_count,
                SUM((SELECT SUM(quantity) FROM order_items WHERE order_items.order_id = orders.id)) as item_count
            ')
            ->groupBy('date', 'month')
            ->orderBy('date')
            ->get();
        
        // Prepare data for the chart
        $labels = [];
        $salesData = [];
        $orderCountData = [];
        $itemCountData = [];
        
        // Fill in missing days with zero values
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $found = $dailyData->firstWhere('date', $dateString);

            $labels[] = $currentDate->format('M j');
            $salesData[] = $found ? $found->total_sales : 0;
            $orderCountData[] = $found ? $found->order_count : 0;
            $itemCountData[] = $found ? $found->item_count : 0;
            
            $currentDate->addDay();
        }
        
        return view('employee.reports.index', compact('labels', 'salesData', 'orderCountData', 'itemCountData'));
    }
    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'statuses' => 'required|array',
            'statuses.*' => 'in:order_placed,preparing,packed,delivering,delivered,completed,cancelled,refunded',
            'report_type' => 'required|in:summary,detailed',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $statuses = $request->statuses;

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
                      ->whereIn('status', $statuses)
                      ->with(['user', 'items.product', 'items.option'])
                      ->get();

        if ($request->report_type === 'detailed') {
            return Excel::download(
                new DetailedOrderReportExport($orders, $startDate, $endDate, $statuses),
                'detailed_orders_report_' . now()->format('Ymd_His') . '.xlsx'
            );
        }

        return Excel::download(
            new SalesReportExport($orders, $startDate, $endDate, $statuses),
            'sales_report_' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}