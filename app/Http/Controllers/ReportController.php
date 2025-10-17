<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $salesData = Deal::where('status', 'won')
            ->whereBetween('actual_close_date', [$startDate, $endDate])
            ->selectRaw('DATE(actual_close_date) as date, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('reports.sales', compact('salesData', 'startDate', 'endDate'));
    }

    public function revenue(Request $request)
    {
        $year = $request->input('year', now()->year);

        $revenueData = Deal::where('status', 'won')
            ->whereYear('actual_close_date', $year)
            ->selectRaw('MONTH(actual_close_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('reports.revenue', compact('revenueData', 'year'));
    }

    public function customers(Request $request)
    {
        $customerStats = [
            'total' => Customer::count(),
            'active' => Customer::where('status', 'active')->count(),
            'new_this_month' => Customer::whereMonth('created_at', now()->month)->count(),
            'by_industry' => Customer::select('industry', DB::raw('count(*) as count'))
                ->groupBy('industry')
                ->get(),
        ];

        return view('reports.customers', compact('customerStats'));
    }
}
