# 🗺️ Google Maps Setup - Quick Guide

## Step 1: Dapatkan API Key

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru (atau pilih existing)
3. Enable 2 APIs:
    - **Maps JavaScript API**
    - **Places API**
4. Buat **API Key** di Credentials
5. (Optional) Restrict API Key:
    - HTTP referrers: `localhost:*`, `yourdomain.com/*`
    - API restrictions: Maps JavaScript API + Places API

## Step 2: Setup di Laravel

Edit file `.env`:

```env
GOOGLE_MAPS_API_KEY=AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
```

> ⚠️ Replace dengan API Key Anda!

## Step 3: Test

1. Jalankan server: `php artisan serve`
2. Login ke admin panel
3. Navigasi ke: **Master** → **Cluster Management**
4. Klik **"Tambah Cluster Baru"**
5. Di Step 2 (Kantor), peta Google Maps akan muncul
6. Test fitur:
    - Search location
    - Click map to add marker
    - Drag marker
    - Delete marker

## ✅ Success!

Jika peta muncul dan interaktif → Setup berhasil! 🎉

## 🐛 Troubleshooting

### Peta tidak muncul (blank/gray)

-   Check API Key di `.env` sudah benar
-   Check Maps JavaScript API sudah di-enable
-   Check browser console untuk error

### Search tidak ada hasil

-   Check Places API sudah di-enable
-   Check API Key restriction tidak terlalu ketat

### Marker tidak bisa di-drag

-   Clear browser cache
-   Check console untuk JavaScript error

---

📖 **Dokumentasi Lengkap:** [`GOOGLE_MAPS_INTEGRATION.md`](GOOGLE_MAPS_INTEGRATION.md)
