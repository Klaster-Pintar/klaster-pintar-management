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
            'total_clusters' => 125,
            'clusters_in_repair' => 9,
            'total_visitors' => 1126,
            'total_workers' => 1071,
            'total_patrols' => 219,
            'total_reports' => 281,
        ];

        return view('admin.dashboard', $data);
    }
}
