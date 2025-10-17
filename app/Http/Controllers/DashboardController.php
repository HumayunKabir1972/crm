<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Activity;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('dashboard_stats', 60, function () {
            $monthlyRevenue = Deal::where('status', 'won')
                ->whereMonth('actual_close_date', now()->month)
                ->sum('amount');

            $lastMonthRevenue = Deal::where('status', 'won')
                ->whereMonth('actual_close_date', now()->subMonth()->month)
                ->sum('amount');

            if ($lastMonthRevenue > 0) {
                $revenueGrowth = (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
            } else {
                $revenueGrowth = 0;
            }

            $totalLeads = Lead::whereMonth('created_at', now()->month)->count();
            $convertedLeads = Lead::where('status', 'converted')
                ->whereMonth('converted_at', now()->month)
                ->count();

            $dealConversionRate = $totalLeads > 0 ? ($convertedLeads / $totalLeads) * 100 : 0;

            return [
                'total_customers' => Customer::count(),
                'active_deals' => Deal::where('status', 'open')->count(),
                'total_leads' => Lead::count(),
                'open_tickets' => Ticket::whereIn('status', ['open', 'in_progress'])->count(),
                'monthly_revenue' => $monthlyRevenue,
                'deals_won_this_month' => Deal::where('status', 'won')
                    ->whereMonth('actual_close_date', now()->month)
                    ->count(),
                'revenueGrowth' => $revenueGrowth,
                'dealConversionRate' => $dealConversionRate,
                'newLeadsThisMonth' => Lead::whereMonth('created_at', now()->month)->count(),
                'dealsInPipeline' => Deal::where('status', 'open')->sum('amount'),
                'topPerformingProducts' => Product::withCount('deals')
                    ->orderBy('deals_count', 'desc')
                    ->take(5)
                    ->get(),
                'recentActivities' => Activity::latest()->take(5)->get(),
                'activity_feed' => Activity::with(['user', 'activityable'])->latest('activity_date')->take(10)->get(),
            ];
        });

        return view('dashboard.index', compact('stats'));
    }
}