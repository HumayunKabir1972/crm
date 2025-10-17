<?php
namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Activity;
use App\Models\Ticket;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DashboardStats extends Component
{
    public $totalCustomers;
    public $activeDeals;
    public $monthlyRevenue;
    public $openTickets;
    public $revenueGrowth;
    public $dealConversionRate;
    public $newLeadsThisMonth;
    public $dealsInPipeline;
    public $topPerformingProducts;
    public $recentActivities;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalCustomers = Customer::where('status', 'active')->count();
        $this->activeDeals = Deal::where('status', 'open')->count();
        $this->monthlyRevenue = Deal::where('status', 'won')
            ->whereMonth('actual_close_date', now()->month)
            ->sum('amount');
        $this->openTickets = Ticket::whereIn('status', ['open', 'in_progress'])->count();

        // Calculate growth
        $lastMonthRevenue = Deal::where('status', 'won')
            ->whereMonth('actual_close_date', now()->subMonth()->month)
            ->sum('amount');

        if ($lastMonthRevenue > 0) {
            $this->revenueGrowth = (($this->monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        } else {
            $this->revenueGrowth = 0;
        }

        // Calculate conversion rate
        $totalLeads = Lead::whereMonth('created_at', now()->month)->count();
        $convertedLeads = Lead::where('status', 'converted')
            ->whereMonth('converted_at', now()->month)
            ->count();

        $this->dealConversionRate = $totalLeads > 0 ? ($convertedLeads / $totalLeads) * 100 : 0;

        $this->newLeadsThisMonth = Lead::whereMonth('created_at', now()->month)->count();
        $this->dealsInPipeline = Deal::where('status', 'open')->sum('amount');
        $this->topPerformingProducts = Product::withCount('deals')
            ->orderBy('deals_count', 'desc')
            ->take(5)
            ->get();
        $this->recentActivities = Activity::latest()->take(5)->get();
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}
