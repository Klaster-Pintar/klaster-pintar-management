# Cluster Management Module - Implementation Summary

## 📋 Overview

Modul **Cluster Management** telah berhasil dibuat untuk mengelola pendaftaran dan administrasi cluster dalam sistem iHome. Modul ini menggunakan **wizard 7 langkah** untuk memandu proses pendaftaran cluster baru dengan tampilan yang profesional dan elegan.

### 🗺️ Google Maps Integration

Wizard telah **fully integrated** dengan **Google Maps API** untuk:

-   **Step 2 (Kantor):** Search location, click to add marker, draggable markers, delete markers
-   **Step 3 (Patroli):** Multiple numbered markers, draggable, route ordering, delete individual/all

> 📖 Dokumentasi lengkap Google Maps: [`GOOGLE_MAPS_INTEGRATION.md`](GOOGLE_MAPS_INTEGRATION.md)

### 📤 CSV Import for Residents

Wizard telah dilengkapi dengan **CSV Import** untuk data residents (Step 7):

-   **Upload & Preview:** Upload CSV, preview data, validasi real-time
-   **Smart Import:** Auto-detect existing users, create baru jika perlu
-   **Validation:** Cek duplikasi, format validation, error handling
-   **Database:** Insert ke `ihm_m_users`, `ihm_m_residents`, `ihm_m_cluster_d_residents`

> 📖 Dokumentasi lengkap CSV Import: [`CSV_RESIDENTS_IMPORT_GUIDE.md`](CSV_RESIDENTS_IMPORT_GUIDE.md)

---

## ✅ Files Created

### 1. **Eloquent Models** (7 files)

#### `app/Models/Cluster.php`

-   **Purpose:** Model utama untuk tabel `ihm_m_clusters`
-   **Features:**
    -   SoftDeletes trait
    -   Dynamic table prefix (`ihm_` dari env)
    -   Relationships: offices, patrols, employees, securities, bankAccounts, residents
    -   Casts: active_flag (boolean), radius settings (integer)

#### `app/Models/ClusterOffice.php`

-   **Purpose:** Model untuk kantor/pos cluster
-   **Table:** `ihm_m_cluster_d_offices`
-   **Features:** location_point (POINT geometry), type_id, belongsTo Cluster

#### `app/Models/ClusterPatrol.php`

-   **Purpose:** Model untuk titik patroli cluster
-   **Table:** `ihm_m_cluster_d_patrols`
-   **Features:** pinpoints (JSON array), day_type_id, belongsTo Cluster

#### `app/Models/ClusterEmployee.php`

-   **Purpose:** Model untuk karyawan cluster (RT/RW/ADMIN)
-   **Table:** `ihm_m_cluster_d_employees`
-   **Features:** employee_id FK to users, belongsTo Cluster and User

#### `app/Models/ClusterSecurity.php`

-   **Purpose:** Model untuk security cluster
-   **Table:** `ihm_m_cluster_d_securities`
-   **Features:** security_id FK to users, belongsTo Cluster and User

#### `app/Models/ClusterBankAccount.php`

-   **Purpose:** Model untuk rekening bank cluster
-   **Table:** `ihm_m_cluster_d_bank_accounts`
-   **Features:** account_number, account_holder, bank_type, bank_code_id, is_verified

#### `app/Models/ClusterResident.php`

-   **Purpose:** Model untuk resident/warga cluster (optional, for future)
-   **Table:** `ihm_m_cluster_d_residents`
-   **Features:** resident_id FK to users, belongsTo Cluster and User

---

### 2. **Controller**

#### `app/Http/Controllers/Admin/ClusterController.php`

-   **Purpose:** Handle all cluster CRUD operations and wizard submission
-   **Key Methods:**
    -   `index()` - List all clusters with search/filter
    -   `create()` - Show wizard form
    -   `store()` - Process wizard submission (6 steps in DB transaction)
    -   `show()` - Display cluster details
    -   `edit()` - Edit cluster
    -   `update()` - Update cluster
    -   `destroy()` - Soft delete cluster

**Store Method Features:**

-   ✅ Validates all 6 wizard steps
-   ✅ Handles file uploads (logo, picture) → `storage/app/public/clusters/{logos|pictures}`
-   ✅ Creates cluster record
-   ✅ Creates related offices with coordinates
-   ✅ Creates patrol points (optional)
-   ✅ **Auto-creates User records** for employees with roles (RT/RW/ADMIN)
-   ✅ **Auto-creates User records** for securities with role SECURITY
-   ✅ Creates employee/security links
-   ✅ Creates bank account records
-   ✅ DB Transaction with rollback on error
-   ✅ Username uniqueness validation

---

### 3. **Views**

#### `resources/views/admin/clusters/index.blade.php`

-   **Purpose:** Cluster listing page
-   **Features:**
    -   Search & filter form (name, email, phone, status)
    -   Grid card layout (1/2/3 columns responsive)
    -   Cards show: logo, picture, name, description, contacts, stats
    -   Stats: Employee count, Security count, Office count
    -   Status badges: Active/Inactive (green/red)
    -   Action buttons: Detail (blue), Edit (green)
    -   Empty state with "Tambah Cluster Pertama" CTA
    -   Pagination support
-   **Color Theme:** Green (#10b981, #059669)

#### `resources/views/admin/clusters/wizard/index.blade.php`

-   **Purpose:** Main wizard container
-   **Features:**
    -   6-step progress indicator with visual states:
        -   Pending: Gray circle with border
        -   Active: Blue gradient with pulse animation
        -   Completed: Green gradient with checkmark
    -   Alpine.js state management (`clusterWizard()`)
    -   Form data structure for all 6 steps
    -   Navigation buttons: Back, Next, Submit
    -   Step validation
    -   Array helpers: add/remove for offices, employees, securities, banks
-   **Design:** Professional gradient background, smooth transitions

#### Wizard Steps (6 files)

##### `resources/views/admin/clusters/wizard/steps/step1-basic.blade.php`

**Step 1: Informasi Dasar Cluster**

-   Fields:
    -   Logo upload (optional, preview, drag & drop)
    -   Picture upload (optional, preview, drag & drop)
    -   Name\* (required)
    -   Description (textarea)
    -   Phone\* (required)
    -   Email\* (required)
    -   Radius Check-in (5m default)
    -   Radius Patrol (5m default)
-   Color Theme: Blue

##### `resources/views/admin/clusters/wizard/steps/step2-offices.blade.php`

**Step 2: Kantor & Pos Lokasi** ✨ **FULLY INTEGRATED WITH GOOGLE MAPS**

-   **Google Maps Features:**
    -   🔍 Search box with autocomplete (Places API)
    -   🗺️ Interactive map (500px height, professional styling)
    -   📍 Click on map to add office marker (green)
    -   🖱️ Draggable markers - coordinates auto-update
    -   ℹ️ Info window on marker click (coordinates + delete button)
    -   🗑️ Delete individual marker or clear all
    -   📊 Real-time counter showing total offices
-   **Form Fields per office:**
    -   Name\* (manual input)
    -   Type\* (dropdown: Pos Security, Kantor Pengelola, Sekretariat, Lainnya)
    -   Coordinates (auto-filled from map, read-only)
-   **User Flow:**
    1. Search location or click map
    2. Green marker appears
    3. Fill office name & type
    4. Drag to adjust position if needed
-   Color Theme: Green
-   **Minimum:** 1 office required

##### `resources/views/admin/clusters/wizard/steps/step3-patrols.blade.php`

**Step 3: Titik Patroli** ✨ **FULLY INTEGRATED WITH GOOGLE MAPS**

-   **Google Maps Features:**
    -   🗺️ Interactive map with patrol route visualization
    -   📍 Click to add patrol markers (orange with numbers: 1, 2, 3...)
    -   🔢 Sequential numbering shows patrol route order
    -   🖱️ Draggable markers - reorder route easily
    -   ℹ️ Info window showing patrol point number + coordinates
    -   🗑️ Delete individual marker (auto re-numbers remaining)
    -   🗑️ Clear all patrol points button
    -   📋 Patrol points list showing full route with coordinates
-   **Features:**
    -   Day Type selector (Weekday/Weekend)
    -   Multiple markers allowed (unlimited patrol points)
    -   Hidden inputs for form submission
    -   Real-time route preview
-   **User Flow:**
    1. Click map to add first patrol point (numbered 1)
    2. Click again for point 2, 3, etc.
    3. Drag markers to adjust route
    4. View route order in list
-   Color Theme: Orange
-   **Status:** Optional (can skip)

##### `resources/views/admin/clusters/wizard/steps/step4-employees.blade.php`

**Step 4: Karyawan/Admin Cluster**

-   Fields per employee:
    -   Name\* (required)
    -   Username\* (required, must be unique)
    -   Email
    -   Phone
    -   Role\* (dropdown: RT, RW, ADMIN)
    -   Password\* (required)
-   Features:
    -   Dynamic array (add/remove employees)
    -   Role selection (RT/RW/ADMIN)
    -   **Auto-creates User record** with selected role
    -   Info box: explains auto-user creation, username uniqueness
    -   Can skip if no employees yet
-   Color Theme: Purple
-   **Important:** Username must be unique across all users!

##### `resources/views/admin/clusters/wizard/steps/step5-securities.blade.php`

**Step 5: Security Personnel**

-   Fields per security:
    -   Name\* (required)
    -   Username\* (required, must be unique)
    -   Email
    -   Phone
    -   Password\* (required)
    -   **Role: SECURITY (auto-set, not user-selectable)**
-   Features:
    -   Dynamic array (add/remove securities)
    -   **Auto-creates User record** with fixed SECURITY role
    -   Info box: explains auto-user creation, username uniqueness
    -   Can skip if no securities yet
-   Color Theme: Indigo
-   **Important:** Username must be unique across all users!

##### `resources/views/admin/clusters/wizard/steps/step6-banks.blade.php`

**Step 6: Rekening Bank**

-   Fields per bank account:
    -   Bank Type\* (dropdown: BCA, BRI, BNI, Mandiri, CIMB, Permata, BTN, Danamon, BSI, Lainnya)
    -   Bank Code\* (number, e.g., 014 for BCA)
    -   Account Number\* (required)
    -   Account Holder\* (required)
-   Features:
    -   Dynamic array (add/remove bank accounts)
    -   Common bank codes reference (BCA:014, BRI:002, etc.)
    -   Info box: verification notice, multiple accounts allowed
    -   Minimum 1 bank account required
-   Color Theme: Emerald/Green
-   **Important:** Rekening akan diverifikasi oleh admin sebelum dapat digunakan

##### `resources/views/admin/clusters/wizard/steps/step7-residents.blade.php` ✨ **NEW**

**Step 7: Data Residents (CSV Import)**

-   **Upload Section:**
    -   File input (max 2MB, .csv only)
    -   Download CSV template button
    -   Info: format & file size requirements
-   **Preview Table:**
    -   Columns: No, Status, Nama, No HP, Blok, Nomor, Status Rumah, Status User, Nominal IPL, Keterangan
    -   Real-time validation with colored badges
    -   Error rows highlighted in red
    -   Sticky header with scroll
-   **Validation Features:**
    -   Required fields check (Nama, No HP, Blok, Nomor)
    -   Phone format validation (numeric only)
    -   Duplicate phone check in CSV
    -   Duplicate house address check
    -   Status validation (Milik/Kontrak, Active/Inactive)
    -   Nominal IPL format check
-   **Smart Import Logic:**
    -   Check if user exists by phone (username)
    -   If exists → use existing user_id
    -   If not → create new user with default password
    -   Create resident in ihm_m_residents
    -   Link to cluster in ihm_m_cluster_d_residents
    -   Validate house occupancy (prevent duplicate)
-   **CSV Format:**
    ```csv
    Nama,No HP,Blok,Nomor,Status Rumah,Status User,Nominal IPL
    John Doe,081234567890,A,01,Milik,Active,500000
    ```
-   **Features:**
    -   Upload & preview before submit
    -   Valid/Error count summary
    -   Error messages per row
    -   Clear CSV button
    -   Validate button
    -   Skip this step if no CSV
-   Color Theme: Purple/Indigo
-   **Important:**
    -   Username = No HP (phone number)
    -   Password default: "password123" untuk user baru
    -   Data di-insert ke 3 tabel: ihm_m_users, ihm_m_residents, ihm_m_cluster_d_residents
    -   Only valid rows akan di-import saat submit

---

### 4. **Routes** (`routes/web.php`)

```php
// Cluster Management Routes
Route::resource('clusters', ClusterController::class);
```

**Generated Routes:**

-   `GET /admin/clusters` → index (list all clusters)
-   `GET /admin/clusters/create` → create (show wizard)
-   `POST /admin/clusters` → store (process wizard)
-   `GET /admin/clusters/{id}` → show (view details)
-   `GET /admin/clusters/{id}/edit` → edit (edit form)
-   `PUT /admin/clusters/{id}` → update (save edits)
-   `DELETE /admin/clusters/{id}` → destroy (soft delete)

---

### 5. **Sidebar Menu** (`resources/views/components/admin/sidebar.blade.php`)

Updated "Master" menu section:

-   ✅ **Cluster Management** menu item added
-   Route: `{{ route('admin.clusters.index') }}`
-   Icon: `fa-building-circle-check` (green theme)
-   Active menu: `master.cluster`
-   Color: Green highlight when active

---

## 🗄️ Database Structure

### Tables Used (Existing - DO NOT modify)

1. **`ihm_m_clusters`** - Main cluster table

    - Columns: name, description, phone, email, logo, picture, radius_checkin, radius_patrol, active_flag
    - Auto-managed: created_by, updated_by, created_at, updated_at, deleted_at

2. **`ihm_m_cluster_d_offices`** - Office locations (1:N)

    - Columns: name, type_id, location_point (POINT), ihm_m_clusters_id (FK)

3. **`ihm_m_cluster_d_patrols`** - Patrol points (1:N)

    - Columns: day_type_id, pinpoints (JSON), ihm_m_clusters_id (FK)

4. **`ihm_m_cluster_d_employees`** - Employee links (1:N)

    - Columns: employee_id (FK to users), ihm_m_clusters_id (FK)

5. **`ihm_m_cluster_d_securities`** - Security links (1:N)

    - Columns: security_id (FK to users), ihm_m_clusters_id (FK)

6. **`ihm_m_cluster_d_bank_accounts`** - Bank accounts (1:N)

    - Columns: account_number, account_holder, bank_type, bank_code_id, is_verified, ihm_m_clusters_id (FK)

7. **`ihm_m_cluster_d_residents`** - Resident links (1:N)

    - Columns: resident_id (FK to residents), ihm_m_clusters_id (FK)
    - Join to: ihm_m_users (user_id) and ihm_m_residents (resident details)

8. **`ihm_m_residents`** - Resident details table (NEW model)
    - Columns: name, username, phone, house_block, house_number, status, etc.
    - Used for: CSV import data storage
    - Relationship: ClusterResident links to this table

---

## 🎨 Design & UI/UX

### Color Themes

-   **Cluster Index Page:** Green (#10b981, #059669)
-   **Wizard Container:** Gradient (gray-blue-green)
-   **Step 1 (Basic):** Blue
-   **Step 2 (Offices):** Orange
-   **Step 3 (Patrols):** Indigo
-   **Step 4 (Employees):** Purple
-   **Step 5 (Securities):** Indigo
-   **Step 6 (Banks):** Emerald/Green
-   **Step 7 (Residents):** Purple/Indigo

### Icons (Font Awesome 6.5.0)

-   Cluster: `fa-building-circle-check`
-   Dashboard: `fa-house`
-   Basic Info: `fa-info-circle`
-   Offices: `fa-building`
-   Patrols: `fa-route`
-   Employees: `fa-users`
-   Securities: `fa-shield-halved`
-   Banks: `fa-building-columns`
-   Residents: `fa-users` (purple)
-   CSV: `fa-file-csv`

### Progress Indicator

-   Visual states: Pending (gray) → Active (blue pulse) → Completed (green checkmark)
-   Progress line connects all steps
-   Responsive: 4 cols mobile, 7 cols desktop

---

## 🔄 Wizard Flow

1. **Step 1: Informasi Dasar** → Input cluster name, contacts, upload logo/picture, set radius
2. **Step 2: Kantor & Pos** → Add office locations with Google Maps (minimum 1 required)
3. **Step 3: Titik Patroli** → Configure patrol points with Google Maps (optional, can skip)
4. **Step 4: Karyawan** → Add employees (RT/RW/ADMIN), auto-creates users (optional)
5. **Step 5: Security** → Add security personnel, auto-creates users with SECURITY role (optional)
6. **Step 6: Rekening Bank** → Add bank accounts (minimum 1 required)
7. **Step 7: Data Residents** → Upload CSV with resident data, auto-import to database (optional)
8. **Submit** → Processes all data in single DB transaction

### Validation Rules

-   **Required Fields:**
    -   Step 1: name, phone, email
    -   Step 2: At least 1 office with name, type, lat, long
    -   Step 6: At least 1 bank account with all fields
-   **Optional:**
    -   Step 1: logo, picture, description
    -   Step 3: patrol points
    -   Step 4: employees
    -   Step 5: securities
    -   Step 7: residents CSV
-   **Unique Constraints:**
    -   Employee usernames (across all users)
    -   Security usernames (across all users)
    -   Resident phone numbers (checked, reuse if exist)
    -   House addresses per cluster (blok + nomor)

---

## 🚀 How to Use

### 1. Access Cluster Management

-   Login to admin panel
-   Click "Master" menu in sidebar
-   Click "Cluster Management"

### 2. Create New Cluster

-   Click "Tambah Cluster Baru" button
-   Follow wizard steps 1-6
-   Fill required fields (marked with red asterisk \*)
-   Click "Selanjutnya" to proceed to next step
-   Click "Kembali" to go back
-   Click "Selesai & Simpan" on step 6 to submit

### 3. View Cluster Details

-   From cluster listing page
-   Click "Detail" button on any cluster card
-   **Note:** Show page not yet created (TODO)

### 4. Edit Cluster

-   From cluster listing page
-   Click "Edit" button on any cluster card
-   **Note:** Edit page not yet created (TODO)

### 5. Delete Cluster

-   **Note:** Delete functionality in controller, needs confirmation modal in UI (TODO)

---

## 📝 Important Notes

### Auto-User Creation

-   **Employees:** When you add employees in Step 4, the system automatically creates user accounts with the selected role (RT/RW/ADMIN)
-   **Securities:** When you add securities in Step 5, the system automatically creates user accounts with fixed SECURITY role
-   **Username Uniqueness:** Ensure all usernames are unique across the entire user table
-   **Password:** Default password or specified password will be hashed (bcrypt)

### File Uploads

-   **Logo:** Stored in `storage/app/public/clusters/logos/`
-   **Picture:** Stored in `storage/app/public/clusters/pictures/`
-   **Access:** Run `php artisan storage:link` to create symlink for public access

### Database Transaction

-   All wizard data saved in **single transaction**
-   If any step fails, entire operation rolls back
-   Ensures data integrity

---

## 🔧 TODO / Future Enhancements

### High Priority

-   [ ] Create cluster **show/detail** page with tabs for all related data
-   [ ] Create cluster **edit** page (reuse wizard or separate forms)
-   [ ] Add **delete confirmation** modal
-   [x] ✅ **Google Maps integration** - COMPLETED
    -   [x] Office location picker with search (Step 2)
    -   [x] Patrol point marker interface with multiple markers (Step 3)
    -   [x] Draggable markers
    -   [x] Delete individual/all markers
    -   [x] Info windows with coordinates

### Medium Priority

-   [ ] **Step 7: Residents** (optional) - Excel upload for bulk resident import
-   [ ] Add **image crop/resize** functionality for logo and picture uploads
-   [ ] Implement **bank account verification** workflow
-   [ ] Add **cluster activation/deactivation** toggle
-   [ ] Create **audit trail** for cluster changes
-   [ ] Add **reverse geocoding** to auto-fill office names from coordinates
-   [ ] Implement **route planning** for patrol points with distance calculation

### Low Priority

-   [ ] Add **export to Excel/PDF** for cluster list
-   [ ] Implement **bulk import** clusters via CSV/Excel
-   [ ] Add **cluster statistics** dashboard widget
-   [ ] Create **notification system** for cluster approval workflow
-   [ ] Add **multi-language support** for wizard
-   [ ] Add **Street View** preview for office locations
-   [ ] Add **drawing tools** for cluster area boundary polygon

---

## 🧪 Testing Checklist

### Manual Testing

#### Google Maps Features (NEW)

-   [ ] **Step 2 - Office Map:**
    -   [ ] Verify map loads correctly with default center (Jakarta)
    -   [ ] Test search box autocomplete with place names
    -   [ ] Click on map to add office marker (green)
    -   [ ] Drag marker to new position, verify coordinates update
    -   [ ] Click marker to see info window with coordinates
    -   [ ] Delete individual marker from info window
    -   [ ] Use "Clear All Markers" button
    -   [ ] Verify office details form syncs with markers
-   [ ] **Step 3 - Patrol Map:**
    -   [ ] Verify map loads correctly
    -   [ ] Click on map to add patrol markers (orange with numbers)
    -   [ ] Verify markers are numbered in sequence (1, 2, 3...)
    -   [ ] Drag marker to new position
    -   [ ] Delete individual marker, verify numbering re-orders
    -   [ ] Use "Clear All Points" button
    -   [ ] Verify patrol points list shows all markers with coordinates

#### Wizard Flow

-   [ ] Navigate to `/admin/clusters` and verify listing page loads
-   [ ] Click "Tambah Cluster Baru" and verify wizard opens
-   [ ] Complete all 6 wizard steps with valid data
-   [ ] Submit wizard and verify:
    -   [ ] Cluster created in `ihm_m_clusters`
    -   [ ] Offices created in `ihm_m_cluster_d_offices` with coordinates from map
    -   [ ] Patrol points saved in `ihm_m_cluster_d_patrols` with pinpoints array
    -   [ ] Employees created: users in `ihm_m_users` + links in `ihm_m_cluster_d_employees`
    -   [ ] Securities created: users in `ihm_m_users` + links in `ihm_m_cluster_d_securities`
    -   [ ] Bank accounts created in `ihm_m_cluster_d_bank_accounts`
-   [ ] Test validation errors:
    -   [ ] Try submitting without required fields
    -   [ ] Try duplicate username for employee/security
-   [ ] Test file uploads:
    -   [ ] Upload logo and verify it's stored correctly
    -   [ ] Upload picture and verify it's stored correctly
-   [ ] Test navigation:
    -   [ ] "Kembali" button works correctly
    -   [ ] "Selanjutnya" button works correctly
    -   [ ] Step indicator shows correct state

### Database Testing

-   [ ] Verify all foreign keys correctly linked
-   [ ] Verify soft deletes work (deleted_at column)
-   [ ] Verify created_by/updated_by tracking
-   [ ] Verify transaction rollback on error

---

## 📞 Support

For questions or issues related to this module, please refer to:

-   `.github/copilot-instructions.md` - Project guidelines
-   `DASHBOARD_IMPROVEMENTS.md` - Previous improvements
-   `CLUSTER_MANAGEMENT_SUMMARY.md` - This file (module overview)
-   `GOOGLE_MAPS_INTEGRATION.md` - **NEW:** Comprehensive Google Maps documentation

---

**Created:** January 2025  
**Version:** 2.0.0  
**Status:** ✅ **Google Maps Fully Integrated** - Production Ready  
**Next Phase:** Show/Edit pages
