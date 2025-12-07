# December 2024-2025 Migrations Guide

Dokumentasi untuk menjalankan migration khusus yang dibuat di bulan Desember 2024 dan 2025 untuk table IoT Devices, Affiliate Marketing, dan Stakeholders.

---

## 📋 Daftar Migration Files

### 1. **IoT Devices Table** (December 2, 2024)
**File:** `2024_12_02_000001_create_ihm_m_iot_devices_table.php`

**Table Created:** `ihm_m_iot_devices`

**Purpose:** Master data untuk perangkat IoT (sensor, camera, dll) yang terhubung dengan cluster.

**Columns:**
- `id` - Primary key
- `code` - Kode unik device (unique)
- `name` - Nama device
- `type` - Tipe device (sensor, camera, etc)
- `description` - Deskripsi device
- `cluster_id` - Foreign key ke `ihm_m_clusters`
- `hardware_status` - ENUM: Active, Inactive, Rusak
- `network_status` - ENUM: Connected, Not Connected
- `last_connected_at` - Timestamp koneksi terakhir
- `ip_address` - IP address device
- `signal_strength` - Kekuatan sinyal (0-100%)
- `firmware_version` - Versi firmware
- `location` - Lokasi fisik device
- `active_flag` - Status aktif
- `created_id`, `updated_id`, `deleted_id` - Audit trail
- `timestamps`, `softDeletes`

**Indexes:**
- `cluster_id`
- `hardware_status`
- `network_status`

---

### 2. **Affiliate Marketing Tables** (December 2, 2024)
**File:** `2024_12_02_000002_create_affiliate_marketing_tables.php`

**Tables Created:**
1. `ihm_m_marketings` - Master marketing/affiliate
2. `ihm_t_marketing_clusters` - Mapping marketing dengan cluster
3. `ihm_m_commission_settings` - Setting komisi
4. `ihm_t_marketing_revenues` - Tracking revenue/komisi

**Details:**

#### Table: `ihm_m_marketings`
**Purpose:** Data marketing/affiliate yang merekrut cluster

**Columns:**
- `id`
- `name` - Nama marketing
- `phone` - Nomor telepon
- `cluster_affiliate_name` - Nama cluster affiliate (DEPRECATED - akan dihapus)
- `referral_code` - Kode referral unik
- `email`
- `address`
- `id_card_number` - No KTP
- `join_date` - Tanggal bergabung
- `status` - ENUM: Active, Inactive, Suspended
- `active_flag`, audit fields
- `timestamps`, `softDeletes`

**Indexes:**
- `referral_code`
- `status`

#### Table: `ihm_t_marketing_clusters`
**Purpose:** Mapping antara marketing dengan cluster yang berhasil direkrut

**Columns:**
- `id`
- `marketing_id` - FK ke `ihm_m_marketings`
- `cluster_id` - FK ke `ihm_m_clusters`
- `join_date` - Tanggal cluster join
- `commission_percentage` - Persentase komisi
- `commission_amount` - Total komisi
- `status` - ENUM: Active, Completed, Cancelled
- `notes`
- `timestamps`

**Unique Constraint:** `[marketing_id, cluster_id]`

**Indexes:**
- `marketing_id`
- `cluster_id`

#### Table: `ihm_m_commission_settings`
**Purpose:** Pengaturan komisi (per marketing, per cluster, atau global)

**Columns:**
- `id`
- `marketing_id` - FK ke `ihm_m_marketings` (NULL = global)
- `cluster_id` - FK ke `ihm_m_clusters` (NULL = all clusters)
- `commission_percentage` - Persentase komisi
- `fixed_amount` - Nominal komisi tetap
- `commission_type` - ENUM: Percentage, Fixed, Both
- `valid_from`, `valid_until` - Periode berlaku
- `is_active` - Status aktif
- `description`
- `created_id`, `updated_id`
- `timestamps`

**Indexes:**
- `marketing_id`
- `cluster_id`
- `is_active`

#### Table: `ihm_t_marketing_revenues`
**Purpose:** Tracking revenue dan komisi marketing

**Columns:**
- `id`
- `marketing_id` - FK ke `ihm_m_marketings`
- `cluster_id` - FK ke `ihm_m_clusters`
- `revenue_date` - Tanggal revenue
- `subscription_amount` - Total subscription
- `commission_amount` - Komisi yang didapat
- `payment_status` - ENUM: Pending, Paid, Cancelled
- `payment_date` - Tanggal pembayaran
- `notes`
- `timestamps`

**Indexes:**
- `marketing_id`
- `cluster_id`
- `revenue_date`
- `payment_status`

---

### 3. **Stakeholders Columns** (December 6, 2025)
**File:** `2025_12_06_092713_add_stakeholders_columns_to_ihm_m_clusters_table.php`

**Table Modified:** `ihm_m_clusters`

**Purpose:** Menambahkan kolom counter untuk stakeholder di setiap cluster.

**Columns Added:**
- `total_employees` - Total karyawan (default: 0)
- `total_securities` - Total security (default: 0)
- `total_residents` - Total resident (default: 0)

**Position:** After `active_flag` column

---

## 🚀 Cara Menjalankan Migration

### Option 1: Jalankan SEMUA Migration Baru (Recommended)
```bash
# Run semua migration yang belum dijalankan
php artisan migrate

# Dengan output verbose
php artisan migrate --verbose
```

### Option 2: Jalankan Migration Spesifik (Per File)

#### 1️⃣ IoT Devices Table
```bash
php artisan migrate --path=database/migrations/2024_12_02_000001_create_ihm_m_iot_devices_table.php
```

#### 2️⃣ Affiliate Marketing Tables
```bash
php artisan migrate --path=database/migrations/2024_12_02_000002_create_affiliate_marketing_tables.php
```

#### 3️⃣ Stakeholders Columns
```bash
php artisan migrate --path=database/migrations/2025_12_06_092713_add_stakeholders_columns_to_ihm_m_clusters_table.php
```

### Option 3: Jalankan Ketiga Migration Sekaligus (Batch)
```bash
php artisan migrate --path=database/migrations/2024_12_02_000001_create_ihm_m_iot_devices_table.php
php artisan migrate --path=database/migrations/2024_12_02_000002_create_affiliate_marketing_tables.php
php artisan migrate --path=database/migrations/2025_12_06_092713_add_stakeholders_columns_to_ihm_m_clusters_table.php
```

### Option 4: Dengan Seeder (Jika Ada)
```bash
# Run migration + seeder
php artisan migrate --seed

# Atau run seeder terpisah setelah migration
php artisan db:seed --class=IoTDeviceSeeder
php artisan db:seed --class=MarketingSeeder
```

---

## 🔄 Rollback Migration

### Rollback Terakhir Batch
```bash
php artisan migrate:rollback
```

### Rollback Spesifik File

#### Rollback IoT Devices
```bash
php artisan migrate:rollback --path=database/migrations/2024_12_02_000001_create_ihm_m_iot_devices_table.php
```

#### Rollback Affiliate Marketing
```bash
php artisan migrate:rollback --path=database/migrations/2024_12_02_000002_create_affiliate_marketing_tables.php
```

#### Rollback Stakeholders
```bash
php artisan migrate:rollback --path=database/migrations/2025_12_06_092713_add_stakeholders_columns_to_ihm_m_clusters_table.php
```

### Rollback Semua Migration (DANGER!)
```bash
# Rollback SEMUA migration - HATI-HATI!
php artisan migrate:reset

# Fresh migration (drop all + migrate from scratch)
php artisan migrate:fresh

# Fresh + seeder
php artisan migrate:fresh --seed
```

---

## ✅ Verifikasi Migration

### Cek Status Migration
```bash
# Lihat migration yang sudah dijalankan
php artisan migrate:status
```

**Expected Output:**
```
Migration name                                                           Batch / Status
------------------------------------------------------------------------
...
2024_12_02_000001_create_ihm_m_iot_devices_table ...................... [1] Ran
2024_12_02_000002_create_affiliate_marketing_tables ................ [1] Ran
2025_12_06_092713_add_stakeholders_columns_to_ihm_m_clusters_table .. [2] Ran
```

### Verifikasi Table di Database

#### MySQL/MariaDB CLI:
```sql
-- Cek table IoT Devices
SHOW TABLES LIKE 'ihm_m_iot_devices';
DESCRIBE ihm_m_iot_devices;

-- Cek table Marketing
SHOW TABLES LIKE 'ihm_m_marketings';
SHOW TABLES LIKE 'ihm_t_marketing_clusters';
SHOW TABLES LIKE 'ihm_m_commission_settings';
SHOW TABLES LIKE 'ihm_t_marketing_revenues';

-- Cek kolom stakeholders di clusters
DESCRIBE ihm_m_clusters;
SELECT column_name FROM information_schema.columns 
WHERE table_name = 'ihm_m_clusters' 
AND column_name IN ('total_employees', 'total_securities', 'total_residents');
```

#### Laravel Tinker:
```bash
php artisan tinker
```

```php
// Cek schema IoT Devices
Schema::hasTable('ihm_m_iot_devices');
Schema::getColumnListing('ihm_m_iot_devices');

// Cek schema Marketing
Schema::hasTable('ihm_m_marketings');
Schema::hasTable('ihm_t_marketing_clusters');

// Cek kolom stakeholders
Schema::hasColumns('ihm_m_clusters', ['total_employees', 'total_securities', 'total_residents']);

// Test insert IoT Device
App\Models\IoTDevice::create([
    'code' => 'IOT-TEST-001',
    'name' => 'Test Device',
    'type' => 'sensor',
    'hardware_status' => 'Active',
    'network_status' => 'Connected'
]);

// Test insert Marketing
App\Models\Marketing::create([
    'name' => 'Test Marketing',
    'phone' => '081234567890',
    'referral_code' => 'REF-TEST-001',
    'status' => 'Active'
]);
```

---

## 📝 Notes & Best Practices

### ⚠️ Important Notes:

1. **Backup Database First!**
   ```bash
   # Backup database sebelum migrate
   mysqldump -u root -p klaster_pintar_management > backup_before_migration.sql
   ```

2. **Check Environment**
   - Pastikan `.env` sudah benar (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
   - Pastikan database sudah dibuat
   - Pastikan koneksi database berjalan

3. **Foreign Key Dependencies**
   - Migration IoT Devices requires: `ihm_m_clusters`, `ihm_m_users`
   - Migration Marketing requires: `ihm_m_clusters`, `ihm_m_users`
   - Migration Stakeholders requires: `ihm_m_clusters` (table must exist)

4. **Running Order** (Jika manual):
   1. IoT Devices (tidak ada dependency antar December migrations)
   2. Affiliate Marketing (tidak ada dependency antar December migrations)
   3. Stakeholders (requires `ihm_m_clusters`)

### 🔧 Troubleshooting:

**Error: "Table already exists"**
```bash
# Cek apakah migration sudah pernah dijalankan
php artisan migrate:status

# Jika sudah, skip atau rollback dulu
php artisan migrate:rollback --step=1
```

**Error: "Foreign key constraint fails"**
```bash
# Pastikan table parent sudah ada
# Cek di database apakah ihm_m_clusters dan ihm_m_users exist
```

**Error: "Class not found"**
```bash
# Clear cache dan regenerate autoload
php artisan clear-compiled
composer dump-autoload
php artisan config:clear
```

---

## 🎯 Quick Reference

### Single Command untuk Semua December Migrations:
```bash
# PowerShell (Windows)
php artisan migrate --path=database/migrations/2024_12_02_000001_create_ihm_m_iot_devices_table.php; `
php artisan migrate --path=database/migrations/2024_12_02_000002_create_affiliate_marketing_tables.php; `
php artisan migrate --path=database/migrations/2025_12_06_092713_add_stakeholders_columns_to_ihm_m_clusters_table.php

# Bash (Linux/Mac)
php artisan migrate --path=database/migrations/2024_12_02_000001_create_ihm_m_iot_devices_table.php && \
php artisan migrate --path=database/migrations/2024_12_02_000002_create_affiliate_marketing_tables.php && \
php artisan migrate --path=database/migrations/2025_12_06_092713_add_stakeholders_columns_to_ihm_m_clusters_table.php
```

### Check if Tables Created:
```bash
php artisan tinker
```
```php
collect(['ihm_m_iot_devices', 'ihm_m_marketings', 'ihm_t_marketing_clusters', 'ihm_m_commission_settings', 'ihm_t_marketing_revenues'])
    ->each(fn($table) => dump("$table: " . (Schema::hasTable($table) ? '✅ EXISTS' : '❌ NOT FOUND')));
```

---

## 📚 Related Documentation

- [IoT Monitoring Summary](./IOT_MONITORING_SUMMARY.md)
- [Affiliate Module Summary](./AFFILIATE_MODULE_SUMMARY.md)
- [Cluster Detail CRUD Implementation](./CLUSTER_DETAIL_CRUD_IMPLEMENTATION.md)

---

**Last Updated:** December 7, 2025  
**Author:** Klaster Pintar Development Team  
**Version:** 1.0.0
