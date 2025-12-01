<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IoTDevice;
use App\Models\Cluster;
use Illuminate\Http\Request;

class DeviceTrackingController extends Controller
{
    /**
     * Display device tracking dashboard
     */
    public function index(Request $request)
    {
        $query = IoTDevice::with(['cluster'])
            ->where('active_flag', true);

        // Filter by cluster
        if ($request->filled('cluster_id')) {
            $query->where('cluster_id', $request->cluster_id);
        }

        // Filter by hardware status
        if ($request->filled('hardware_status')) {
            $query->where('hardware_status', $request->hardware_status);
        }

        // Filter by network status
        if ($request->filled('network_status')) {
            $query->where('network_status', $request->network_status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $devices = $query->latest('last_connected_at')->paginate(12);
        $clusters = Cluster::where('active_flag', true)->get();

        // Statistics for dashboard cards
        $stats = [
            'total_devices' => IoTDevice::where('active_flag', true)->count(),
            'hardware_active' => IoTDevice::where('active_flag', true)->where('hardware_status', 'Active')->count(),
            'hardware_inactive' => IoTDevice::where('active_flag', true)->where('hardware_status', 'Inactive')->count(),
            'hardware_rusak' => IoTDevice::where('active_flag', true)->where('hardware_status', 'Rusak')->count(),
            'network_connected' => IoTDevice::where('active_flag', true)->where('network_status', 'Connected')->count(),
            'network_disconnected' => IoTDevice::where('active_flag', true)->where('network_status', 'Not Connected')->count(),
        ];

        return view('admin.iot.device-tracking.index', compact('devices', 'clusters', 'stats'));
    }

    /**
     * Get device status for AJAX refresh
     */
    public function getDeviceStatus(IoTDevice $device)
    {
        $device->load('cluster');

        return response()->json([
            'success' => true,
            'device' => [
                'id' => $device->id,
                'code' => $device->code,
                'name' => $device->name,
                'type' => $device->type,
                'hardware_status' => $device->hardware_status,
                'network_status' => $device->network_status,
                'last_connected_at' => $device->last_connected_at?->format('Y-m-d H:i:s'),
                'last_connected_human' => $device->getLastConnectedHuman(),
                'signal_strength' => $device->signal_strength,
                'cluster_name' => $device->cluster?->name,
                'ip_address' => $device->ip_address,
            ]
        ]);
    }
}
