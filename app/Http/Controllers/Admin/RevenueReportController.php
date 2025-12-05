<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marketing;
use App\Models\MarketingRevenue;
use App\Models\Cluster;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RevenueReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $marketingId = $request->get('marketing_id');
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        $paymentStatus = $request->get('payment_status');

        // Get all marketings for dropdown
        $marketings = Marketing::where('active_flag', true)->orderBy('name')->get();

        // Build revenue query
        $revenueQuery = MarketingRevenue::with(['marketing', 'cluster'])
            ->whereYear('revenue_date', $year)
            ->whereMonth('revenue_date', $month);

        if ($marketingId) {
            $revenueQuery->where('marketing_id', $marketingId);
        }

        if ($paymentStatus) {
            $revenueQuery->where('payment_status', $paymentStatus);
        }

        $revenues = $revenueQuery->orderBy('revenue_date', 'desc')->get();

        // Calculate statistics per marketing
        $marketingStats = [];

        if ($marketingId) {
            // Single marketing stats
            $marketing = Marketing::find($marketingId);
            if ($marketing) {
                $totalRevenue = $revenues->sum('revenue_amount');
                $totalCommission = $revenues->sum('commission_amount');
                $paidCommission = $revenues->where('payment_status', 'Paid')->sum('commission_amount');
                $pendingCommission = $revenues->where('payment_status', 'Pending')->sum('commission_amount');
                $totalClusters = $marketing->getTotalClusters();

                $marketingStats[] = [
                    'marketing' => $marketing,
                    'total_revenue' => $totalRevenue,
                    'total_commission' => $totalCommission,
                    'paid_commission' => $paidCommission,
                    'pending_commission' => $pendingCommission,
                    'total_clusters' => $totalClusters,
                ];
            }
        } else {
            // All marketings stats
            foreach ($marketings as $marketing) {
                $marketingRevenues = $revenues->where('marketing_id', $marketing->id);

                if ($marketingRevenues->count() > 0) {
                    $totalRevenue = $marketingRevenues->sum('revenue_amount');
                    $totalCommission = $marketingRevenues->sum('commission_amount');
                    $paidCommission = $marketingRevenues->where('payment_status', 'Paid')->sum('commission_amount');
                    $pendingCommission = $marketingRevenues->where('payment_status', 'Pending')->sum('commission_amount');
                    $totalClusters = $marketing->getTotalClusters();

                    $marketingStats[] = [
                        'marketing' => $marketing,
                        'total_revenue' => $totalRevenue,
                        'total_commission' => $totalCommission,
                        'paid_commission' => $paidCommission,
                        'pending_commission' => $pendingCommission,
                        'total_clusters' => $totalClusters,
                    ];
                }
            }
        }

        // Sort by total revenue descending
        usort($marketingStats, function ($a, $b) {
            return $b['total_revenue'] <=> $a['total_revenue'];
        });

        // Overall statistics
        $totalRevenue = $revenues->sum('revenue_amount');
        $totalCommission = $revenues->sum('commission_amount');
        $paidCommission = $revenues->where('payment_status', 'Paid')->sum('commission_amount');
        $pendingCommission = $revenues->where('payment_status', 'Pending')->sum('commission_amount');

        // Prepare chart data
        $chartLabels = [];
        $chartRevenues = [];
        $chartCommissions = [];

        foreach ($marketingStats as $stat) {
            $chartLabels[] = $stat['marketing']->name;
            $chartRevenues[] = $stat['total_revenue'];
            $chartCommissions[] = $stat['total_commission'];
        }

        return view('admin.affiliate.revenue.index', compact(
            'marketings',
            'marketingStats',
            'revenues',
            'month',
            'year',
            'marketingId',
            'paymentStatus',
            'totalRevenue',
            'totalCommission',
            'paidCommission',
            'pendingCommission',
            'chartLabels',
            'chartRevenues',
            'chartCommissions'
        ));
    }
}
