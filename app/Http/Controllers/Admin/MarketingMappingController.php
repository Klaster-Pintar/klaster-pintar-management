<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marketing;
use App\Models\Cluster;
use App\Models\MarketingCluster;
use Illuminate\Http\Request;

class MarketingMappingController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketingCluster::with(['marketing', 'cluster']);

        // Filter by marketing
        if ($request->filled('marketing_id')) {
            $query->where('marketing_id', $request->marketing_id);
        }

        // Filter by cluster
        if ($request->filled('cluster_id')) {
            $query->where('cluster_id', $request->cluster_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $mappings = $query->orderBy('join_date', 'desc')->paginate(15);

        // Get all marketings and clusters for filter dropdowns
        $marketings = Marketing::where('active_flag', true)->orderBy('name')->get();
        $clusters = Cluster::where('active_flag', true)->orderBy('name')->get();

        // Statistics
        $totalMappings = MarketingCluster::count();
        $activeMappings = MarketingCluster::where('status', 'Active')->count();
        $completedMappings = MarketingCluster::where('status', 'Completed')->count();
        $cancelledMappings = MarketingCluster::where('status', 'Cancelled')->count();

        return view('admin.affiliate.mapping.index', compact(
            'mappings',
            'marketings',
            'clusters',
            'totalMappings',
            'activeMappings',
            'completedMappings',
            'cancelledMappings'
        ));
    }

    public function create()
    {
        $marketings = Marketing::where('active_flag', true)->where('status', 'Active')->orderBy('name')->get();
        $clusters = Cluster::where('active_flag', true)->orderBy('name')->get();

        return view('admin.affiliate.mapping.create', compact('marketings', 'clusters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'marketing_id' => 'required|exists:ihm_m_marketings,id',
            'cluster_id' => 'required|exists:ihm_m_clusters,id',
            'join_date' => 'required|date',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'commission_amount' => 'required|numeric|min:0',
            'status' => 'required|in:Active,Completed,Cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if mapping already exists
        $exists = MarketingCluster::where('marketing_id', $validated['marketing_id'])
            ->where('cluster_id', $validated['cluster_id'])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['cluster_id' => 'Mapping ini sudah ada!']);
        }

        MarketingCluster::create($validated);

        return redirect()->route('admin.affiliate.mapping.index')
            ->with('success', 'Mapping berhasil ditambahkan!');
    }

    public function edit(MarketingCluster $mapping)
    {
        $marketings = Marketing::where('active_flag', true)->orderBy('name')->get();
        $clusters = Cluster::where('active_flag', true)->orderBy('name')->get();

        return view('admin.affiliate.mapping.edit', compact('mapping', 'marketings', 'clusters'));
    }

    public function update(Request $request, MarketingCluster $mapping)
    {
        $validated = $request->validate([
            'join_date' => 'required|date',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'commission_amount' => 'required|numeric|min:0',
            'status' => 'required|in:Active,Completed,Cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $mapping->update($validated);

        return redirect()->route('admin.affiliate.mapping.index')
            ->with('success', 'Mapping berhasil diperbarui!');
    }

    public function destroy(MarketingCluster $mapping)
    {
        $mapping->delete();

        return redirect()->route('admin.affiliate.mapping.index')
            ->with('success', 'Mapping berhasil dihapus!');
    }
}
