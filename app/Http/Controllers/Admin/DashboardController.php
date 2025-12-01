<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cluster;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // Get real data from database
        $data = [
            'active_clusters' => Cluster::where('active_flag', 1)->count(),
            'pending_subscriptions' => 0, // Set to 0 as requested
            'total_revenue' => 0, // Set to 0 as requested
        ];

        return view('admin.dashboard', $data);
    }
}
