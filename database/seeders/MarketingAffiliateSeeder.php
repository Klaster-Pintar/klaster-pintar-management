<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Marketing;
use App\Models\Cluster;
use App\Models\MarketingCluster;
use App\Models\CommissionSetting;
use App\Models\MarketingRevenue;
use Carbon\Carbon;

class MarketingAffiliateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marketings = [
            [
                'name' => 'Budi Santoso',
                'phone' => '081234567801',
                'cluster_affiliate_name' => 'Elite Marketing Group',
                'email' => 'budi.santoso@email.com',
                'address' => 'Jl. Sudirman No. 123, Jakarta',
                'id_card_number' => '3171051234560001',
                'join_date' => Carbon::now()->subMonths(12),
                'status' => 'Active',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'phone' => '081234567802',
                'cluster_affiliate_name' => 'Pro Marketing Indonesia',
                'email' => 'siti.nur@email.com',
                'address' => 'Jl. Gatot Subroto No. 45, Bandung',
                'id_card_number' => '3273041987650002',
                'join_date' => Carbon::now()->subMonths(10),
                'status' => 'Active',
            ],
            [
                'name' => 'Ahmad Hidayat',
                'phone' => '081234567803',
                'cluster_affiliate_name' => 'Smart Sales Network',
                'email' => 'ahmad.hidayat@email.com',
                'address' => 'Jl. Ahmad Yani No. 78, Surabaya',
                'id_card_number' => '3578121990450003',
                'join_date' => Carbon::now()->subMonths(8),
                'status' => 'Active',
            ],
            [
                'name' => 'Dewi Lestari',
                'phone' => '081234567804',
                'cluster_affiliate_name' => 'Golden Partners',
                'email' => 'dewi.lestari@email.com',
                'address' => 'Jl. Diponegoro No. 56, Semarang',
                'id_card_number' => '3374061985230004',
                'join_date' => Carbon::now()->subMonths(15),
                'status' => 'Active',
            ],
            [
                'name' => 'Rizky Firmansyah',
                'phone' => '081234567805',
                'cluster_affiliate_name' => 'Prime Marketing',
                'email' => 'rizky.firman@email.com',
                'address' => 'Jl. Pahlawan No. 90, Yogyakarta',
                'id_card_number' => '3471081992340005',
                'join_date' => Carbon::now()->subMonths(6),
                'status' => 'Active',
            ],
            [
                'name' => 'Rina Wijaya',
                'phone' => '081234567806',
                'cluster_affiliate_name' => 'Success Marketing Hub',
                'email' => 'rina.wijaya@email.com',
                'address' => 'Jl. Veteran No. 12, Malang',
                'id_card_number' => '3573021988120006',
                'join_date' => Carbon::now()->subMonths(9),
                'status' => 'Active',
            ],
            [
                'name' => 'Agus Prasetyo',
                'phone' => '081234567807',
                'cluster_affiliate_name' => 'Top Sales Partners',
                'email' => 'agus.prasetyo@email.com',
                'address' => 'Jl. Merdeka No. 34, Solo',
                'id_card_number' => '3372111991670007',
                'join_date' => Carbon::now()->subMonths(11),
                'status' => 'Active',
            ],
            [
                'name' => 'Linda Kusuma',
                'phone' => '081234567808',
                'cluster_affiliate_name' => 'Mega Affiliate Network',
                'email' => 'linda.kusuma@email.com',
                'address' => 'Jl. Pemuda No. 67, Denpasar',
                'id_card_number' => '5171031989450008',
                'join_date' => Carbon::now()->subMonths(7),
                'status' => 'Active',
            ],
            [
                'name' => 'Hendra Gunawan',
                'phone' => '081234567809',
                'cluster_affiliate_name' => 'Dynamic Marketing',
                'email' => 'hendra.gunawan@email.com',
                'address' => 'Jl. Asia Afrika No. 89, Medan',
                'id_card_number' => '1271091987560009',
                'join_date' => Carbon::now()->subMonths(5),
                'status' => 'Active',
            ],
            [
                'name' => 'Maya Sari',
                'phone' => '081234567810',
                'cluster_affiliate_name' => 'Victory Sales Group',
                'email' => 'maya.sari@email.com',
                'address' => 'Jl. Raya No. 123, Makassar',
                'id_card_number' => '7371051990120010',
                'join_date' => Carbon::now()->subMonths(14),
                'status' => 'Active',
            ],
            [
                'name' => 'Doni Irawan',
                'phone' => '081234567811',
                'cluster_affiliate_name' => 'Champion Marketing',
                'email' => 'doni.irawan@email.com',
                'address' => 'Jl. Kebon Jeruk No. 45, Palembang',
                'id_card_number' => '1671021986780011',
                'join_date' => Carbon::now()->subMonths(4),
                'status' => 'Inactive',
            ],
            [
                'name' => 'Fitri Handayani',
                'phone' => '081234567812',
                'cluster_affiliate_name' => 'Star Affiliate',
                'email' => 'fitri.handayani@email.com',
                'address' => 'Jl. Melati No. 78, Balikpapan',
                'id_card_number' => '6471031993450012',
                'join_date' => Carbon::now()->subMonths(13),
                'status' => 'Active',
            ],
            [
                'name' => 'Rudi Hartono',
                'phone' => '081234567813',
                'cluster_affiliate_name' => 'Platinum Partners',
                'email' => 'rudi.hartono@email.com',
                'address' => 'Jl. Mawar No. 90, Pontianak',
                'id_card_number' => '6171081988230013',
                'join_date' => Carbon::now()->subMonths(3),
                'status' => 'Active',
            ],
            [
                'name' => 'Yuni Astuti',
                'phone' => '081234567814',
                'cluster_affiliate_name' => 'Diamond Marketing',
                'email' => 'yuni.astuti@email.com',
                'address' => 'Jl. Anggrek No. 56, Manado',
                'id_card_number' => '7171021991120014',
                'join_date' => Carbon::now()->subMonths(2),
                'status' => 'Suspended',
            ],
            [
                'name' => 'Eko Prasetyo',
                'phone' => '081234567815',
                'cluster_affiliate_name' => 'Ultimate Sales Hub',
                'email' => 'eko.prasetyo@email.com',
                'address' => 'Jl. Kenanga No. 123, Banjarmasin',
                'id_card_number' => '6371041992560015',
                'join_date' => Carbon::now()->subMonths(16),
                'status' => 'Active',
            ],
        ];

        $createdMarketings = [];

        foreach ($marketings as $data) {
            $data['referral_code'] = Marketing::generateReferralCode();
            $data['active_flag'] = true;
            $data['created_id'] = 1;

            $createdMarketings[] = Marketing::create($data);
        }

        // Create some marketing-cluster mappings (simulating successful joins)
        $clusters = Cluster::where('active_flag', true)->limit(10)->get();

        if ($clusters->isNotEmpty()) {
            foreach ($createdMarketings as $index => $marketing) {
                // Skip inactive or suspended marketings
                if ($marketing->status !== 'Active')
                    continue;

                // Each active marketing gets 1-3 clusters
                $clusterCount = rand(1, min(3, $clusters->count()));
                $selectedClusters = $clusters->random($clusterCount);

                foreach ($selectedClusters as $cluster) {
                    // Check if mapping already exists
                    $exists = MarketingCluster::where('marketing_id', $marketing->id)
                        ->where('cluster_id', $cluster->id)
                        ->exists();

                    if (!$exists) {
                        MarketingCluster::create([
                            'marketing_id' => $marketing->id,
                            'cluster_id' => $cluster->id,
                            'join_date' => Carbon::now()->subDays(rand(30, 365)),
                            'commission_percentage' => rand(5, 15),
                            'commission_amount' => rand(1000000, 5000000),
                            'status' => 'Active',
                            'notes' => 'Berhasil join via referral ' . $marketing->referral_code,
                        ]);
                    }
                }
            }
        }

        // Create some commission settings
        CommissionSetting::create([
            'marketing_id' => null,
            'cluster_id' => null,
            'commission_percentage' => 10,
            'fixed_amount' => 0,
            'commission_type' => 'Percentage',
            'valid_from' => Carbon::now()->subMonths(6),
            'valid_until' => null,
            'is_active' => true,
            'description' => 'Global commission setting - 10% for all',
            'created_id' => 1,
        ]);

        // Create some revenue data for active marketings
        foreach ($createdMarketings as $marketing) {
            if ($marketing->status !== 'Active')
                continue;

            $mappings = MarketingCluster::where('marketing_id', $marketing->id)
                ->where('status', 'Active')
                ->get();

            foreach ($mappings as $mapping) {
                // Create 3-6 revenue records per mapping
                $revenueCount = rand(3, 6);

                for ($i = 0; $i < $revenueCount; $i++) {
                    $revenueAmount = rand(5000000, 20000000);
                    $commissionPercentage = $mapping->commission_percentage;
                    $commissionAmount = ($revenueAmount * $commissionPercentage) / 100;

                    MarketingRevenue::create([
                        'marketing_id' => $marketing->id,
                        'cluster_id' => $mapping->cluster_id,
                        'revenue_date' => Carbon::now()->subDays(rand(1, 90)),
                        'revenue_amount' => $revenueAmount,
                        'commission_percentage' => $commissionPercentage,
                        'commission_amount' => $commissionAmount,
                        'payment_status' => rand(0, 2) === 0 ? 'Pending' : 'Paid',
                        'payment_date' => rand(0, 2) === 0 ? null : Carbon::now()->subDays(rand(1, 30)),
                        'payment_method' => rand(0, 1) ? 'Transfer Bank' : 'Cash',
                        'notes' => 'Commission for ' . Carbon::now()->subDays(rand(1, 90))->format('F Y'),
                    ]);
                }
            }
        }

        $this->command->info('15 Marketing data seeded successfully with mappings, commissions, and revenues!');
    }
}
