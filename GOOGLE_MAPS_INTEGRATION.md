# Google Maps Integration - Cluster Management

## 📍 Overview

Modul Cluster Management telah diintegrasikan dengan **Google Maps** untuk memberikan pengalaman profesional dalam menandai lokasi kantor dan titik patroli security. Integrasi ini menggunakan **Google Maps JavaScript API** dan **Places API**.

---

## 🔑 Setup Google Maps API Key

### 1. Buat Google Cloud Project

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru atau pilih project yang sudah ada
3. Nama project: `Klaster Pintar Management` (atau sesuai kebutuhan)

### 2. Enable Required APIs

Aktifkan 2 API berikut:

#### A. Maps JavaScript API

1. Di Google Cloud Console, navigasi ke **APIs & Services** → **Library**
2. Cari **"Maps JavaScript API"**
3. Klik **Enable**

#### B. Places API

1. Masih di **Library**, cari **"Places API"**
2. Klik **Enable**

### 3. Buat API Key

1. Navigasi ke **APIs & Services** → **Credentials**
2. Klik **Create Credentials** → **API Key**
3. Copy API Key yang dihasilkan
4. **(RECOMMENDED)** Klik **Edit API Key** untuk membatasi penggunaan:
    - **Application restrictions**:
        - HTTP referrers (websites)
        - Tambahkan domain Anda: `localhost:*`, `yourdomain.com/*`
    - **API restrictions**:
        - Pilih **Restrict key**
        - Centang:
            - Maps JavaScript API
            - Places API
5. **Save** perubahan

### 4. Setup di Laravel

Tambahkan API Key ke file `.env`:

```env
# Google Maps API Key
GOOGLE_MAPS_API_KEY=AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
```

> ⚠️ **IMPORTANT:** Jangan commit `.env` ke Git! API Key harus tetap rahasia.

### 5. Billing (Jika Diperlukan)

Google Maps memiliki **free tier** dengan quota:

-   Maps JavaScript API: $200 free credit/month (~28,000 map loads)
-   Places API: $200 free credit/month (~30,000 requests)

Jika penggunaan melebihi quota, Anda perlu mengaktifkan billing di Google Cloud Console.

---

## 🗺️ Features

### Step 2: Office/Kantor Location (Google Maps)

#### Fitur yang Tersedia:

1. **Search Location**

    - Search box dengan autocomplete
    - Cari lokasi dengan nama tempat (contoh: "Monas, Jakarta")
    - Hasil search langsung menambah marker di peta

2. **Click to Add Marker**

    - Klik di peta untuk menambah marker kantor
    - Marker berwarna hijau (green dot)
    - Koordinat otomatis tersimpan

3. **Draggable Markers**

    - Semua marker bisa di-drag untuk menyesuaikan posisi
    - Koordinat otomatis update saat marker di-drag

4. **Marker Info Window**

    - Klik marker untuk melihat info:
        - Nomor office
        - Koordinat (Latitude, Longitude)
        - Tombol "Hapus"

5. **Delete Individual Marker**

    - Klik marker → klik tombol "Hapus" di info window
    - Atau klik tombol hapus di list office di bawah peta

6. **Clear All Markers**

    - Tombol "Clear All Markers" menghapus semua marker sekaligus
    - Muncul konfirmasi sebelum menghapus

7. **Office Details Form**
    - Setiap marker memiliki form detail:
        - Nama Kantor (input manual)
        - Tipe Kantor (dropdown: Pos Security, Kantor Pengelola, Sekretariat, Lainnya)
        - Koordinat (auto-filled dari marker, read-only)

#### User Flow:

```
1. User search lokasi ATAU klik di peta
2. Marker hijau muncul di posisi yang dipilih
3. Koordinat otomatis tersimpan
4. User mengisi nama kantor dan tipe di form
5. Ulangi untuk kantor lainnya
6. Klik "Selanjutnya" untuk lanjut ke Step 3
```

---

### Step 3: Patrol Points (Google Maps)

#### Fitur yang Tersedia:

1. **Multiple Markers with Numbers**

    - Marker berwarna orange dengan label nomor (1, 2, 3, ...)
    - Nomor menunjukkan urutan rute patroli
    - Bisa menambah sebanyak mungkin titik patroli

2. **Click to Add Marker**

    - Klik di peta untuk menambah titik patroli
    - Marker otomatis diberi nomor urut

3. **Draggable Markers**

    - Semua marker bisa di-drag
    - Koordinat otomatis update

4. **Marker Info Window**

    - Klik marker untuk melihat info:
        - Nomor patrol point
        - Koordinat
        - Tombol "Hapus"

5. **Delete Individual Marker**

    - Klik marker → klik "Hapus"
    - Atau klik tombol hapus di patrol points list
    - Nomor marker otomatis re-order

6. **Clear All Markers**

    - Tombol "Clear All Points" menghapus semua titik patroli
    - Muncul konfirmasi

7. **Patrol Points List**

    - Menampilkan semua titik patroli dalam urutan
    - Setiap item menunjukkan: nomor, koordinat
    - Bisa hapus individual dari list

8. **Day Type Selection**
    - Dropdown untuk memilih Weekday/Weekend
    - (Future: bisa menambah patrol berbeda untuk hari berbeda)

#### User Flow:

```
1. Pilih tipe hari (Weekday/Weekend) - optional
2. Klik di peta untuk menambah titik patroli pertama (nomor 1)
3. Klik lagi untuk titik kedua (nomor 2), dst
4. Drag marker untuk menyesuaikan posisi jika perlu
5. Lihat urutan rute di "Rute Patroli (Urutan)" list
6. Klik "Selanjutnya" atau skip jika tidak ada patroli
```

---

## 🎨 UI/UX Design

### Map Container

-   Tinggi: 500px
-   Border radius: 12px
-   Box shadow untuk depth
-   Responsive design

### Marker Colors

-   **Office (Step 2):** Green marker (hijau)
-   **Patrol (Step 3):** Orange marker dengan nomor

### Search Box

-   Autocomplete Places API
-   Positioned di atas peta
-   Placeholder: "🔍 Cari lokasi kantor..."

### Map Controls

-   **Clear All** button - warna merah, posisi kiri atas
-   **Counter** - menampilkan total markers, posisi kanan atas

### Info Window (Pop-up)

-   Font: Poppins (matching aplikasi)
-   Konten: Nomor, koordinat, tombol hapus
-   Tombol hapus: merah, dengan icon trash

---

## 🔧 Technical Implementation

### File Structure

```
resources/views/admin/clusters/wizard/
├── index.blade.php                    # Main wizard container (updated)
├── steps/
│   ├── step2-offices.blade.php        # Office map (fully updated)
│   └── step3-patrols.blade.php        # Patrol map (fully updated)
```

### JavaScript Functions (Alpine.js)

#### Step 2 - Office Functions:

```javascript
initOfficeMap(); // Initialize map
addOfficeMarker(lat, lng); // Add marker
deleteOfficeMarker(index); // Delete single marker
clearAllOfficeMarkers(); // Clear all markers
getOfficeInfoWindowContent(); // Generate info window HTML
```

#### Step 3 - Patrol Functions:

```javascript
initPatrolMap(); // Initialize map
addPatrolMarker(lat, lng); // Add numbered marker
deletePatrolMarker(index); // Delete single marker
clearAllPatrolMarkers(); // Clear all markers
updatePatrolPinpoints(); // Update formData with markers
getPatrolInfoWindowContent(); // Generate info window HTML
```

### Data Flow

#### Office Data:

```javascript
formData.offices = [
    {
        name: "Pos Security Utama",
        type_id: 1,
        latitude: "-6.2000000",
        longitude: "106.8166660",
    },
    // ... more offices
];
```

#### Patrol Data:

```javascript
formData.patrols = [
    {
        day_type_id: 1,
        pinpoints: [
            { lat: "-6.2000000", lng: "106.8166660" },
            { lat: "-6.2010000", lng: "106.8176660" },
            // ... more points
        ],
    },
];
```

### Form Submission

Data dikirim ke backend via normal form POST:

```html
<!-- Office data -->
<input type="hidden" name="offices[0][name]" value="..." />
<input type="hidden" name="offices[0][type_id]" value="1" />
<input type="hidden" name="offices[0][latitude]" value="-6.200000" />
<input type="hidden" name="offices[0][longitude]" value="106.816666" />

<!-- Patrol data -->
<input type="hidden" name="patrols[0][day_type_id]" value="1" />
<input type="hidden" name="patrols[0][pinpoints][0][lat]" value="-6.200000" />
<input type="hidden" name="patrols[0][pinpoints][0][lng]" value="106.816666" />
```

---

## 🐛 Troubleshooting

### Map tidak muncul / blank

**Problem:** Peta tampil putih/abu-abu saja

**Solution:**

1. Pastikan `GOOGLE_MAPS_API_KEY` sudah diisi di `.env`
2. Pastikan Maps JavaScript API sudah di-enable
3. Check browser console untuk error
4. Pastikan API Key tidak dibatasi untuk domain localhost

### Marker tidak bisa di-drag

**Problem:** Marker tidak bisa dipindahkan

**Solution:**

-   Pastikan property `draggable: true` ada di marker options
-   Check apakah ada error JavaScript di console

### Search box tidak ada hasil

**Problem:** Autocomplete tidak muncul/tidak ada hasil

**Solution:**

1. Pastikan Places API sudah di-enable
2. Pastikan API Key tidak dibatasi hanya untuk Maps JavaScript API
3. Clear browser cache

### Info window button tidak bekerja

**Problem:** Tombol "Hapus" di info window tidak berfungsi

**Solution:**

-   Fungsi onclick menggunakan `window.clusterWizardInstance` yang diinisialisasi saat DOM ready
-   Pastikan Alpine.js sudah loaded
-   Check console untuk error

### Koordinat tidak tersimpan

**Problem:** Data koordinat tidak ter-submit

**Solution:**

-   Pastikan hidden input fields sudah ter-generate dengan benar
-   Check `name` attribute format: `offices[0][latitude]`, `offices[0][longitude]`
-   Pastikan formData di-bind dengan benar ke Alpine.js

---

## 📱 Browser Compatibility

Tested & Working:

-   ✅ Google Chrome (latest)
-   ✅ Mozilla Firefox (latest)
-   ✅ Microsoft Edge (latest)
-   ✅ Safari (latest)
-   ✅ Mobile browsers (responsive)

---

## 🔒 Security Best Practices

### API Key Protection

1. **Never commit API Key to Git**

    - Add `.env` to `.gitignore` ✅ (already done)
    - Use environment variables

2. **Restrict API Key**

    - Set HTTP referrer restrictions
    - Limit to specific APIs only
    - Monitor usage di Google Cloud Console

3. **Rotate Keys Regularly**
    - Regenerate API Key setiap 3-6 bulan
    - Revoke old keys setelah migration

### Rate Limiting

-   Google Maps API memiliki default rate limiting
-   Monitor usage di [Google Cloud Console](https://console.cloud.google.com/google/maps-apis/metrics)
-   Set alerts jika mendekati quota

---

## 💰 Cost Estimation

### Free Tier (Monthly):

-   **Maps JavaScript API:** 28,000 map loads (~ $200 credit)
-   **Places API:** 30,000 autocomplete requests (~ $200 credit)

### Estimated Usage:

Asumsi:

-   100 admin users
-   Setiap user membuat 5 cluster/month
-   Setiap cluster creation load map 2 kali (office + patrol)

**Total:** 100 × 5 × 2 = **1,000 map loads/month**

**Cost:** **$0** (masih di free tier)

### If Exceeding Free Tier:

-   Maps JavaScript API: $7/1,000 map loads (after free $200)
-   Places API: $17/1,000 autocomplete requests (after free $200)

---

## 🚀 Future Enhancements

### Planned Features:

-   [ ] **Geocoding Reverse:** Auto-fill nama kantor dari koordinat
-   [ ] **Drawing Tools:** Gambar polygon/radius untuk area cluster
-   [ ] **Route Planning:** Hitung jarak & waktu tempuh antar patrol points
-   [ ] **Street View:** Preview lokasi dengan Street View
-   [ ] **Multiple Day Types:** Patrol berbeda untuk Weekday/Weekend
-   [ ] **Export/Import:** Import patrol points dari KML/GeoJSON
-   [ ] **Heat Map:** Visualisasi aktivitas patroli

### Optional APIs:

-   **Directions API:** Untuk routing patrol points
-   **Distance Matrix API:** Hitung jarak antar titik
-   **Geocoding API:** Convert address ↔ coordinates

---

## 📞 Support

### Documentation Links:

-   [Google Maps JavaScript API Docs](https://developers.google.com/maps/documentation/javascript)
-   [Places API Docs](https://developers.google.com/maps/documentation/places/web-service)
-   [API Key Best Practices](https://developers.google.com/maps/api-security-best-practices)

### Common Issues:

-   [Google Maps API Errors](https://developers.google.com/maps/documentation/javascript/error-messages)
-   [Billing & Pricing](https://cloud.google.com/maps-platform/pricing)

---

**Last Updated:** November 23, 2025  
**Version:** 1.0.0  
**Status:** ✅ Fully Implemented & Production Ready
