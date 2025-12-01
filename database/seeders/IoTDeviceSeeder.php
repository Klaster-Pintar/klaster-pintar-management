<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IoTDevice;
use App\Models\Cluster;
use Carbon\Carbon;

class IoTDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get random clusters for assignment
        $clusters = Cluster::where('active_flag', true)->limit(5)->get();

        $devices = [
            [
                'code' => 'IOT-001',
                'name' => 'Motion Sensor Gate A',
                'type' => 'Motion Sensor',
                'description' => 'Sensor gerak di pintu gerbang utama A untuk mendeteksi pergerakan',
                'cluster_id' => $clusters->isNotEmpty() ? $clusters->random()->id : null,
                'hardware_status' => 'Active',
                'network_status' => 'Connected',
                'last_connected_at' => now(),
                'ip_address' => '192.168.1.101',
                'signal_strength' => 95,
                'firmware_version' => 'v1.2.3',
                'location' => 'Gate A, Lantai 1',
            ],
            [
                'code' => 'IOT-002',
                'name' => 'CCTV Camera Main Entrance',
                'type' => 'CCTV Camera',
                'description' => 'Kamera CCTV 4K di pintu masuk utama dengan night vision',
                'cluster_id' => $clusters->isNotEmpty() ? $clusters->random()->id : null,
                'hardware_status' => 'Active',
                'network_status' => 'Connected',
                'last_connected_at' => now()->subMinutes(5),
                'ip_address' => '192.168.1.102',
                'signal_strength' => 88,
                'firmware_version' => 'v2.0.1',
                'location' => 'Main Entrance',
            ],
            [
                'code' => 'IOT-003',
                'name' => 'Door Sensor Building B',
                'type' => 'Door Sensor',
                'description' => 'Sensor pintu magnetic untuk gedung B',
                'cluster_id' => $clusters->isNotEmpty() ? $clusters->random()->id : null,
                'hardware_status' => 'Active',
                'network_status' => 'Not Connected',
                'last_connected_at' => now()->subHours(2),
                'ip_address' => '192.168.1.103',
                'signal_strength' => 45,
                'firmware_version' => 'v1.1.0',
                'location' => 'Building B, Main Door',
            ],
            [
                'code' => 'IOT-004',
                'name' => 'Fire Detector Floor 2',
                'type' => 'Fire Detector',
                'description' => 'Detektor asap dan panas di lantai 2',
                'cluster_id' => $clusters->isNotEmpty() ? $clusters->random()->id : null,
                'hardware_status' => 'Active',
                'network_status' => 'Connected',
                'last_connected_at' => now()->subMinutes(10),
                'ip_address' => '192.168.1.104',
                'signal_strength' => 92,
                'firmware_version' => 'v1.5.2',
                'location' => 'Floor 2, Corridor',
            ],
            [
                'code' => 'IOT-005',
                'name' => 'Smart Lock Office A',
                'type' => 'Smart Lock',
                'description' => 'Smart lock dengan fingerprint dan RFID untuk Office A',
                'cluster_id' => $clusters->isNotEmpty() ? $clusters->random()->id : null,
                'hardware_status' => 'Inactive',
                'network_status' => 'Not Connected',
                'last_connected_at' => now()->subDays(2),
                'ip_address' => '192.168.1.105',
                'signal_strength' => 0,
                'firmware_version' => 'v1.0.5',
                'location' => 'Office A',
            ],
            [
                'code' => 'IOT-006',
                'name' => 'Alarm System Parking',
                'type' => 'Alarm System',
                'description' => 'Sistem alarm untuk area parkir',
                'cluster_id' => $clusters->isNotEmpty() ? $clusters->random()->id : null,
                'hardware_status' => 'Active',
                'network_status' => 'Connected',
                'last_connected_at' => now()->subMinutes(1),
                'ip_address' => '192.168.1.106',
                'signal_strength' => 75,
                'firmware_version' => 'v2.1.0',
                'location' => 'Parking Area',
            ],
            [
                'code' => 'IOT-007',
                'name' => 'Access Control Gate B',
                'type' => 'Access Control',
                'description' => 'Kontrol akses dengan kartu RFID di gerbang B',
                'cluster_id' => $clusters->isNotEmpty() ? $clusters->random()->id : null,
                'hardware_status' => 'Rusak',
                'network_status' => 'Not Connected',
                'last_connected_at' => now()->subDays(5),
                'ip_address' => '192.168.1.107',
                'signal_strength' => 0,
                'firmware_version' => 'v1.3.1',
                'location' => 'Gate B',
            ],
            [
                'code' => 'IOT-008',
                'name' => 'Motion Sensor Basement',
                'type' => 'Motion Sensor',
                'description' => 'Sensor gerak infrared di basement',
                'cluster_id' => $clusters->isNotEmpty() ? $clusters->random()->id : null,
                'hardware_status' => 'Active',
                'network_status' => 'Connected',
                'last_connected_at' => now()->subSeconds(30),
                'ip_address' => '192.168.1.108',
                'signal_strength' => 82,
                'firmware_version' => 'v1.2.0',
                'location' => 'Basement Level -1',
            ],
            [
                'code' => 'IOT-009',
                'name' => 'CCTV Camera Rooftop',
                'type' => 'CCTV Camera',
                'description' => 'Kamera panorama 360 derajat di rooftop',
                'cluster_id' => null, // Not assigned yet
                'hardware_status' => 'Active',
                'network_status' => 'Not Connected',
                'last_connected_at' => now()->subHours(12),
                'ip_address' => '192.168.1.109',
                'signal_strength' => 35,
                'firmware_version' => 'v2.2.5',
                'location' => 'Rooftop',
            ],
            [
                'code' => 'IOT-010',
                'name' => 'Door Sensor Emergency Exit',
                'type' => 'Door Sensor',
                'description' => 'Sensor pintu darurat dengan alarm otomatis',
                'cluster_id' => null, // Not assigned yet
                'hardware_status' => 'Inactive',
                'network_status' => 'Not Connected',
                'last_connected_at' => null,
                'ip_address' => null,
                'signal_strength' => null,
                'firmware_version' => 'v1.0.0',
                'location' => 'Emergency Exit',
            ],
        ];

        foreach ($devices as $device) {
            IoTDevice::create([
                ...$device,
                'active_flag' => true,
                'created_id' => 1, // Assuming user ID 1 exists
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('10 IoT devices seeded successfully!');
    }
}
