<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionSetting;
use App\Models\Marketing;
use App\Models\Cluster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommissionSettingController extends Controller
{
    public function index(Request $request)
    {
        $query = CommissionSetting::with(['marketing', 'cluster', 'creator']);

        // Filter by type
        if ($request->filled('commission_type')) {
            $query->where('commission_type', $request->commission_type);
        }

        // Filter by active
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        // Show only active or all
        if ($request->filled('show_inactive')) {
            // Show all
        } else {
            $query->where('is_active', true);
        }

        $settings = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $totalSettings = CommissionSetting::count();
        $activeSettings = CommissionSetting::where('is_active', true)->count();
        $globalSettings = CommissionSetting::whereNull('marketing_id')->whereNull('cluster_id')->count();
        $specificSettings = CommissionSetting::where(function ($q) {
            $q->whereNotNull('marketing_id')->orWhereNotNull('cluster_id');
        })->count();

        return view('admin.affiliate.commission.index', compact(
            'settings',
            'totalSettings',
            'activeSettings',
            'globalSettings',
            'specificSettings'
        ));
    }

    public function create()
    {
        $marketings = Marketing::where('active_flag', true)->orderBy('name')->get();
        $clusters = Cluster::where('active_flag', true)->orderBy('name')->get();

        return view('admin.affiliate.commission.create', compact('marketings', 'clusters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'marketing_id' => 'nullable|exists:ihm_m_marketings,id',
            'cluster_id' => 'nullable|exists:ihm_m_clusters,id',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'fixed_amount' => 'required|numeric|min:0',
            'commission_type' => 'required|in:Percentage,Fixed,Both',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['created_id'] = Auth::id();

        CommissionSetting::create($validated);

        return redirect()->route('admin.affiliate.commission.index')
            ->with('success', 'Setting komisi berhasil ditambahkan!');
    }

    public function edit(CommissionSetting $commission)
    {
        $marketings = Marketing::where('active_flag', true)->orderBy('name')->get();
        $clusters = Cluster::where('active_flag', true)->orderBy('name')->get();

        return view('admin.affiliate.commission.edit', compact('commission', 'marketings', 'clusters'));
    }

    public function update(Request $request, CommissionSetting $commission)
    {
        $validated = $request->validate([
            'marketing_id' => 'nullable|exists:ihm_m_marketings,id',
            'cluster_id' => 'nullable|exists:ihm_m_clusters,id',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'fixed_amount' => 'required|numeric|min:0',
            'commission_type' => 'required|in:Percentage,Fixed,Both',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['updated_id'] = Auth::id();

        $commission->update($validated);

        return redirect()->route('admin.affiliate.commission.index')
            ->with('success', 'Setting komisi berhasil diperbarui!');
    }

    public function destroy(CommissionSetting $commission)
    {
        $commission->delete();

        return redirect()->route('admin.affiliate.commission.index')
            ->with('success', 'Setting komisi berhasil dihapus!');
    }
}
