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
        return view('employee.reports.index');
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