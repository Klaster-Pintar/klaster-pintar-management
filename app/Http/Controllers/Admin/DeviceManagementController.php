<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IoTDevice;
use App\Models\Cluster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceManagementController extends Controller
{
    /**
     * Display a listing of the IoT devices
     */
    public function index(Request $request)
    {
        $query = IoTDevice::with(['cluster', 'creator'])
            ->where('active_flag', true);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // Filter by hardware status
        if ($request->filled('hardware_status')) {
            $query->where('hardware_status', $request->hardware_status);
        }

        // Filter by network status
        if ($request->filled('network_status')) {
            $query->where('network_status', $request->network_status);
        }

        // Filter by cluster
        if ($request->filled('cluster_id')) {
            $query->where('cluster_id', $request->cluster_id);
        }

        $devices = $query->latest()->paginate(10);
        $clusters = Cluster::where('active_flag', true)->get();

        // Statistics
        $stats = [
            'total' => IoTDevice::where('active_flag', true)->count(),
            'active' => IoTDevice::where('active_flag', true)->where('hardware_status', 'Active')->count(),
            'inactive' => IoTDevice::where('active_flag', true)->where('hardware_status', 'Inactive')->count(),
            'rusak' => IoTDevice::where('active_flag', true)->where('hardware_status', 'Rusak')->count(),
            'connected' => IoTDevice::where('active_flag', true)->where('network_status', 'Connected')->count(),
        ];

        return view('admin.iot.device-management.index', compact('devices', 'clusters', 'stats'));
    }

    /**
     * Show the form for creating a new device
     */
    public function create()
    {
        $clusters = Cluster::where('active_flag', true)->get();
        return view('admin.iot.device-management.create', compact('clusters'));
    }

    /**
     * Store a newly created device
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:ihm_m_iot_devices,code',
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'cluster_id' => 'nullable|exists:ihm_m_clusters,id',
            'hardware_status' => 'required|in:Active,Inactive,Rusak',
            'network_status' => 'required|in:Connected,Not Connected',
            'ip_address' => 'nullable|ip',
            'signal_strength' => 'nullable|integer|min:0|max:100',
            'firmware_version' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:200',
        ]);

        $validated['created_id'] = Auth::id();
        $validated['active_flag'] = true;

        // Set last_connected_at if network status is Connected
        if ($validated['network_status'] === 'Connected') {
            $validated['last_connected_at'] = now();
        }

        IoTDevice::create($validated);

        return redirect()->route('admin.iot.device-management.index')
            ->with('success', 'IoT Device berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified device
     */
    public function edit(IoTDevice $device)
    {
        $clusters = Cluster::where('active_flag', true)->get();
        return view('admin.iot.device-management.edit', compact('device', 'clusters'));
    }

    /**
     * Update the specified device
     */
    public function update(Request $request, IoTDevice $device)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:ihm_m_iot_devices,code,' . $device->id,
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'cluster_id' => 'nullable|exists:ihm_m_clusters,id',
            'hardware_status' => 'required|in:Active,Inactive,Rusak',
            'network_status' => 'required|in:Connected,Not Connected',
            'ip_address' => 'nullable|ip',
            'signal_strength' => 'nullable|integer|min:0|max:100',
            'firmware_version' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:200',
        ]);

        $validated['updated_id'] = Auth::id();

        // Update last_connected_at if network status changed to Connected
        if ($validated['network_status'] === 'Connected' && $device->network_status !== 'Connected') {
            $validated['last_connected_at'] = now();
        }

        $device->update($validated);

        return redirect()->route('admin.iot.device-management.index')
            ->with('success', 'IoT Device berhasil diupdate!');
    }

    /**
     * Remove the specified device (soft delete)
     */
    public function destroy(IoTDevice $device)
    {
        $device->update([
            'active_flag' => false,
            'deleted_id' => Auth::id(),
        ]);

        $device->delete();

        return redirect()->route('admin.iot.device-management.index')
            ->with('success', 'IoT Device berhasil dihapus!');
    }
}
