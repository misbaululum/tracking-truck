# 🚚 Tracking Bongkar Muat – PT CBA Chemical Industry

Aplikasi **Tracking Bongkar/Muat & Security Gate Monitoring** berbasis **Laravel + Livewire**, dibuat untuk memantau proses keluar-masuk kendaraan logistik secara digital di area pabrik.  
Dirancang agar setiap peran (**Admin, Security, Officer TTB/SJ, Petugas Bongkar/Muat, dan Supir/Vendor**) dapat berinteraksi sesuai alur kerja masing-masing.

---

## 🔧 Fitur Utama

### 👥 Multi-Role User
- **Admin**: Melihat semua data, ekspor laporan Excel, mengelola password user lain, dan melakukan pembatalan transaksi.
- **Security**: Input kendaraan masuk & keluar.
- **Officer TTB/SJ**: Mencatat proses surat jalan & verifikasi TTB.
- **Petugas Bongkar/Muat**: Input waktu mulai & selesai aktivitas bongkar atau muat.
- **Supir/Vendor**: Mengisi form publik menggunakan barcode link tanpa login.

### 📄 Form & Alur Proses Otomatis
- Supir/vendor melakukan input mandiri (public form).
- Security melakukan verifikasi waktu masuk & keluar.
- Officer dan petugas lapangan melanjutkan proses hingga selesai.
- Field tambahan (mis. nomor surat jalan, nama barang, jumlah barang) **otomatis muncul hanya saat BONGKAR**.

### 📊 Dashboard & DataTable
- Data ditampilkan dengan pagination, search, dan filter tanggal.
- Skeleton loading & update realtime Livewire.
- Status kendaraan tampil dinamis berdasarkan tahap proses.

### 📦 Export Data Excel
- Ekspor laporan sesuai pencarian & rentang tanggal.
- Format header lengkap (identitas kendaraan, waktu proses, officer, status).
- Output otomatis menyesuaikan hasil filter aktif.

### 🔐 Manajemen Password
- Setiap user dapat mengganti password sendiri.
- Admin dapat mengganti password user lain langsung dari dashboard.
- **Tautan menu**:
  - **Ganti Password** (selalu tampil)
  - **Kelola Password** (khusus admin)

### 🌐 Public Input via QR/Barcode
- Link publik (tanpa login) disediakan untuk supir/vendor mengisi data kendaraan.
- Data otomatis masuk ke alur proses Security & Officer.

---

⚙️ Instalasi
1. **Clone Repository**
```bash
git clone https://github.com/username/tracking-truck.git  
cd tracking-truck
```
2. **Instal Dependency**
```bash
composer install
npm install && npm run build
```
3. **Konfigurasi .env**
Salin file contoh lalu ubah pengaturan database & app:
```bash
cp .env.example .env
```

**Edit bagian:**
```bash
APP_NAME="Tracking Bongkar Muat"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tracking_truck
DB_USERNAME=root
DB_PASSWORD=
```
4. **Generate Key & Migrasi Database**
```bash
php artisan key:generate
php artisan migrate --seed
```

**Seeder otomatis menambahkan akun admin dan contoh role lainnya.**

5. **Jalankan Server**
```bash
php artisan serve
```

Akses: http://localhost:8000
----

### 💡 Password dapat diubah melalui menu Ganti Password setelah login.

### 💾 Fitur Teknis Tambahan

Livewire Realtime Binding (wire:model.live) untuk input tanpa reload.

Optimistic UI Update untuk aksi simpan & ubah status.

Tailwind CSS + inline style hybrid agar cepat diterapkan di environment aapanel / shared hosting.

Export Excel via Maatwebsite/Excel.

Autentikasi bawaan Laravel Breeze/Fortify (dapat disesuaikan).

### 🧰 Teknologi yang Digunakan
Komponen	Versi	Keterangan
Laravel	10.x / 11.x	Framework utama
Livewire	3.x	Frontend reaktif tanpa JS manual
PHP	≥ 8.1	Backend runtime
MySQL / MariaDB	≥ 10.x	Database
TailwindCSS	3.x	Styling
Maatwebsite/Excel	3.x	Export Excel
Alpine.js	3.x	Jam realtime & interaksi ringan
📦 Deployment

### Upload ke hosting (aaPanel / VPS / shared hosting).
```
Jalankan composer install --no-dev dan php artisan migrate --force.

Atur .env production.

Pastikan permission folder storage dan bootstrap/cache:

chmod -R 775 storage bootstrap/cache

Jalankan queue dan scheduler jika diperlukan.
```

###🧑‍💻 Kontributor

Developer: Misbaul Ulum

Instansi: PT CBA Chemical Industry

Fokus: Digitalisasi proses logistik & keamanan pabrik

📜 Lisensi

MIT License © 2025 – PT CBA Chemical Industry
Boleh digunakan, dimodifikasi, dan dikembangkan ulang dengan mencantumkan kredit.
