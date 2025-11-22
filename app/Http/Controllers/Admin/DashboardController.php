<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // Sample data for dashboard - replace with actual queries later
        $data = [
            'active_clusters' => 128,
            'pending_subscriptions' => 12,
            'total_revenue' => 125000000,
        ];

        return view('admin.dashboard', $data);
    }
}
