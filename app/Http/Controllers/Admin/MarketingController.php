<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MarketingController extends Controller
{
    public function index(Request $request)
    {
        $query = Marketing::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%")
                    ->orWhere('cluster_affiliate_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $marketings = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistics
        $totalMarketing = Marketing::count();
        $activeMarketing = Marketing::where('status', 'Active')->count();
        $suspendedMarketing = Marketing::where('status', 'Suspended')->count();
        $inactiveMarketing = Marketing::where('status', 'Inactive')->count();

        // Calculate total revenue from all marketings
        $totalRevenue = Marketing::all()->sum(function ($marketing) {
            return $marketing->getTotalRevenue();
        });

        $totalCommission = Marketing::all()->sum(function ($marketing) {
            return $marketing->getTotalCommission();
        });

        return view('admin.affiliate.marketing.index', compact(
            'marketings',
            'totalMarketing',
            'activeMarketing',
            'suspendedMarketing',
            'inactiveMarketing',
            'totalRevenue',
            'totalCommission'
        ));
    }

    public function create()
    {
        return view('admin.affiliate.marketing.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:15|unique:ihm_m_marketings,phone',
            'cluster_affiliate_name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:ihm_m_marketings,email',
            'address' => 'nullable|string|max:255',
            'id_card_number' => 'nullable|string|max:20|unique:ihm_m_marketings,id_card_number',
            'join_date' => 'required|date',
            'status' => 'required|in:Active,Inactive,Suspended',
        ]);

        // Auto-generate unique referral code
        $validated['referral_code'] = Marketing::generateReferralCode();
        $validated['active_flag'] = true;
        $validated['created_id'] = Auth::id();

        Marketing::create($validated);

        return redirect()->route('admin.affiliate.marketing.index')
            ->with('success', 'Marketing berhasil ditambahkan dengan kode referral: ' . $validated['referral_code']);
    }

    public function show(Marketing $marketing)
    {
        $marketing->load(['marketingClusters.cluster', 'revenues']);

        return view('admin.affiliate.marketing.show', compact('marketing'));
    }

    public function edit(Marketing $marketing)
    {
        return view('admin.affiliate.marketing.edit', compact('marketing'));
    }

    public function update(Request $request, Marketing $marketing)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => ['required', 'string', 'max:15', Rule::unique('ihm_m_marketings')->ignore($marketing->id)],
            'cluster_affiliate_name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:100', Rule::unique('ihm_m_marketings')->ignore($marketing->id)],
            'address' => 'nullable|string|max:255',
            'id_card_number' => ['nullable', 'string', 'max:20', Rule::unique('ihm_m_marketings')->ignore($marketing->id)],
            'join_date' => 'required|date',
            'status' => 'required|in:Active,Inactive,Suspended',
        ]);

        $validated['updated_id'] = Auth::id();

        $marketing->update($validated);

        return redirect()->route('admin.affiliate.marketing.index')
            ->with('success', 'Data marketing berhasil diperbarui!');
    }

    public function destroy(Marketing $marketing)
    {
        $marketing->delete();

        return redirect()->route('admin.affiliate.marketing.index')
            ->with('success', 'Data marketing berhasil dihapus!');
    }
}
