# DailyCash — Sistem Catatan Keuangan Harian

**DailyCash** adalah aplikasi web pencatatan keuangan harian berbasis **Laravel** yang dirancang untuk membantu individu maupun pemilik usaha kecil mencatat pemasukan (*income*) dan pengeluaran (*expense*), memantau posisi saldo di berbagai rekening/dompet secara *real-time*, mengontrol anggaran bulanan, serta mengevaluasi kondisi keuangan melalui laporan periodik.

---

## Daftar Fitur

### Fitur Wajib
1. **CRUD Kategori Transaksi (`/categories`)**
   - Menampilkan daftar kategori beserta filter tipe (`income` / `expense`), jumlah transaksi, dan total akumulasi nominal.
   - Menambahkan, melihat detail, mengubah, dan menghapus kategori.
   - Perlindungan relasi: kategori yang sudah digunakan oleh transaksi tidak dapat diubah tipenya atau dihapus secara sembarangan agar histori tetap konsisten.
2. **CRUD Rekening / Dompet (`/accounts`)**
   - Menampilkan daftar rekening/dompet beserta saldo saat ini, total dana masuk, dan total dana keluar.
   - Menambahkan rekening/dompet baru (dengan nilai saldo awal bawaan `Rp0`), melihat detail mutasi rekening, mengubah nama rekening, dan menghapus rekening kosong.
   - Perlindungan relasi: rekening yang masih memiliki riwayat transaksi dilindungi dari penghapusan.
3. **CRUD Transaksi Terpadu (`/transactions`)**
   - Satu tabel terpadu (`transactions`) untuk mencatat pemasukan (`income`) dan pengeluaran (`expense`).
   - Formulir lengkap: Tanggal, Tipe Transaksi, Nominal Rupiah, Kategori (otomatis difilter sesuai tipe), Rekening/Dompet, Deskripsi opsional, dan **Upload Bukti Pembayaran (gambar opsional)**.
   - Manajemen file bukti pembayaran menggunakan Laravel Storage (`public` disk): validasi tipe gambar & ukuran (maks. 2MB), penghapusan file lama secara otomatis saat bukti diganti atau transaksi dihapus.
4. **Perhitungan & Konsistensi Saldo Otomatis (`DB::transaction()`)**
   - Pemasukan menambah saldo rekening; pengeluaran mengurangi saldo rekening.
   - Validasi ketat agar pengeluaran tidak melebihi saldo rekening yang dipilih (mencegah saldo negatif).
   - Perubahan (*edit*) transaksi secara atomik membalikkan dampak transaksi lama pada rekening asal dan menerapkan dampak transaksi baru pada rekening tujuan.
   - Penghapusan transaksi membalikkan dampak saldo secara konsisten.
5. **Filter Transaksi Multikriteria**
   - Filter berdasarkan rentang tanggal (`start_date` & `end_date`), tipe transaksi, kategori, dan rekening/dompet secara bersamaan.
   - Mendukung tombol **Terapkan Filter** dan **Hapus Filter**, serta mempertahankan query filter saat berpindah halaman *pagination*.
6. **Halaman Detail Transaksi dengan URI Dinamis (`/transactions/{id}`)**
   - Menggunakan *Route Model Binding* (mengembalikan HTTP `404` jika ID tidak ditemukan).
   - Menampilkan rincian lengkap transaksi beserta pratinjau gambar bukti pembayaran.
7. **Dashboard Ringkasan Keuangan (`/`)**
   - **Total Saldo Keseluruhan** (`SUM(accounts.balance)`).
   - **Total Pemasukan Bulan Ini** dan **Total Pengeluaran Bulan Ini**.
   - Ringkasan saldo per rekening/dompet, pengeluaran terbesar per kategori, dan daftar transaksi terbaru.
8. **Laporan Keuangan Periodik (`/reports`)**
   - Evaluasi keuangan berdasarkan periode **Bulanan**, **Tahunan**, atau **Rentang Tanggal Kustom**, serta filter per rekening.
   - Rekapitulasi pemasukan & pengeluaran per kategori beserta persentase proporsi.
9. **Wireframe UI Low-Fidelity (`/wireframes`)**
   - Dokumentasi interaktif rancangan *low-fidelity wireframe* untuk seluruh 8 halaman utama aplikasi sesuai spesifikasi tahap perancangan awal.

### Fitur Pengayaan Opsional
- **Grafik Arus Kas 6 Bulan Terakhir** pada halaman Dashboard.
- **Anggaran Bulanan (`/budgets`)**: pengaturan batas pengeluaran bulanan beserta indikator persentase realisasi vs anggaran.
- **Peringatan Anggaran (*Budget Alert*)**: banner peringatan otomatis di Dashboard ketika pengeluaran bulan berjalan melampaui (atau mendekati $\ge 80\%$) batas anggaran.
- **Ekspor Laporan CSV (`/reports/export`)**: unduh rincian transaksi sesuai periode yang dipilih ke dalam format CSV.

---

## Teknologi yang Digunakan

- **Backend:** PHP 8.3+ / 8.4, Laravel Framework ( pola arsitektur MVC, Eloquent ORM, Form Request Validation)
- **Frontend:** Laravel Blade Templating & Reusable Anonymous Blade Components
- **Styling:** Tailwind CSS v4 (`@tailwindcss/vite`)
- **Database:** SQLite (`database/database.sqlite`)
- **Pengujian:** Pest PHP / PHPUnit (`RefreshDatabase`, `Storage::fake`)

---

## Persyaratan Lingkungan

Pastikan perangkat Anda telah terpasang:
- **PHP** >= 8.3 (dengan ekstensi `pdo_sqlite`, `sqlite3`, `gd`, `fileinfo`, `mbstring`)
- **Composer** >= 2.x
- **Node.js** >= 20.x & **npm**

---

## Langkah Instalasi & Menjalankan Aplikasi

### 1. Pasang Dependensi PHP & Frontend
```bash
composer install
npm install
```

### 2. Konfigurasi File `.env`
Salin `.env.example` menjadi `.env`:
```powershell
# PowerShell (Windows)
Copy-Item .env.example .env
```
```bash
# Bash / macOS / Linux
cp .env.example .env
```

Pastikan konfigurasi utama di `.env` menggunakan SQLite:
```env
APP_NAME=DailyCash
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
FILESYSTEM_DISK=local
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Siapkan Database SQLite, Migration, dan Seeder
Buat file database SQLite (jika belum ada) dan jalankan migrasi beserta data awal (*seeder*):
```powershell
# PowerShell (Windows)
if (!(Test-Path database/database.sqlite)) { New-Item -ItemType File database/database.sqlite }
php artisan migrate:fresh --seed
```
```bash
# Bash / macOS / Linux
touch database/database.sqlite
php artisan migrate:fresh --seed
```

> **Catatan Konsistensi Saldo Data Awal (Seeder):**
> `AccountSeeder` menginisialisasi 4 rekening (`Rekening BCA`, `Dompet Utama`, `OVO`, `Tunai`) dengan saldo awal `Rp0`. Selanjutnya `TransactionSeeder` menambahkan 14 transaksi contoh dan memperbarui saldo masing-masing rekening secara atomik di dalam `DB::transaction()`, sehingga saldo akhir setiap rekening dijamin 100% konsisten dengan selisih seluruh transaksi pemasukan dan pengeluaran.

### 5. Build Asset Frontend & Hubungkan Storage
```bash
npm run build
php artisan storage:link
```

### 6. Jalankan Server Aplikasi
```bash
php artisan serve
```
Akses aplikasi melalui browser di: **http://127.0.0.1:8000**

---

## Struktur Database & Relasi

Aplikasi memiliki 3 tabel inti yang saling berelasi ditambah 1 tabel pendukung anggaran bulanan:

### 1. Tabel `categories`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | `BIGINT` (PK) | ID kategori |
| `name` | `VARCHAR(50)` | Nama kategori |
| `type` | `ENUM('income', 'expense')` | Tipe kategori pemasukan atau pengeluaran |
| `created_at` / `updated_at` | `TIMESTAMP` | Waktu pembuatan & pembaruan |

### 2. Tabel `accounts`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | `BIGINT` (PK) | ID rekening/dompet |
| `name` | `VARCHAR(50)` | Nama rekening/dompet |
| `balance` | `BIGINT` (Default `0`) | Saldo saat ini dalam satuan Rupiah penuh |
| `created_at` / `updated_at` | `TIMESTAMP` | Waktu pembuatan & pembaruan |

### 3. Tabel `transactions`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | `BIGINT` (PK) | ID transaksi |
| `account_id` | `BIGINT` (FK) | Relasi ke `accounts.id` (`RESTRICT ON DELETE`) |
| `category_id` | `BIGINT` (FK) | Relasi ke `categories.id` (`RESTRICT ON DELETE`) |
| `type` | `ENUM('income', 'expense')` | Tipe transaksi (`income` / `expense`) |
| `amount` | `BIGINT` | Nominal transaksi dalam Rupiah (`> 0`) |
| `date` | `DATE` | Tanggal transaksi |
| `description` | `VARCHAR(255)`, *nullable* | Deskripsi / keterangan opsional |
| `proof_image` | `VARCHAR`, *nullable* | Path file gambar bukti pembayaran di disk `public` |
| `created_at` / `updated_at` | `TIMESTAMP` | Waktu pembuatan & pembaruan |

### 4. Tabel `budgets` (Pengayaan Anggaran Bulanan)
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | `BIGINT` (PK) | ID anggaran |
| `month` | `VARCHAR(7)` (*unique*) | Periode bulan dalam format `YYYY-MM` |
| `amount` | `BIGINT` | Batas anggaran pengeluaran bulanan (Rupiah) |
| `created_at` / `updated_at` | `TIMESTAMP` | Waktu pembuatan & pembaruan |

### Relasi Eloquent
- `Account` **hasMany** `Transaction` (`$account->transactions()`)
- `Category` **hasMany** `Transaction` (`$category->transactions()`)
- `Transaction` **belongsTo** `Account` (`$transaction->account()`)
- `Transaction` **belongsTo** `Category` (`$transaction->category()`)

---

## Menjalankan Pengujian (Testing)

Proyek ini dilengkapi dengan **18 skenario pengujian otomatis** menggunakan **Pest PHP** (di dalam `tests/Feature/DailyCashTest.php`, `tests/Feature/ExampleTest.php`, dan `tests/Unit/ExampleTest.php`) yang berjalan menggunakan database SQLite `:memory:`.

Jalankan seluruh pengujian dengan perintah:
```bash
php artisan test --compact
```
atau langsung melalui runner Pest:
```bash
php vendor/bin/pest
```

### Daftar Skenario Pengujian di `tests/Feature/DailyCashTest.php`:
1. Kategori pemasukan berhasil dibuat.
2. Kategori pengeluaran berhasil dibuat.
3. Kategori berhasil diperbarui dan dihapus sesuai aturan relasi (ditolak jika masih memiliki transaksi, berhasil dihapus jika kosong).
4. Rekening/dompet berhasil dibuat dengan saldo awal default `0`.
5. Transaksi pemasukan berhasil disimpan dan menambah saldo rekening.
6. Transaksi pengeluaran berhasil disimpan dan mengurangi saldo rekening.
7. Pengeluaran melebihi saldo rekening ditolak dan tidak mengubah saldo.
8. Transaksi dengan kategori yang tidak sesuai tipe ditolak.
9. Mengubah transaksi memperbarui saldo lama dan saldo baru dengan benar (termasuk perpindahan antarrekening).
10. Menghapus transaksi mengembalikan saldo rekening dengan benar.
11. Filter transaksi berdasarkan tanggal dan kategori menghasilkan data yang sesuai.
12. Detail transaksi menampilkan data berdasarkan ID dan mengembalikan 404 jika tidak ditemukan.
13. Transaksi tanpa bukti gambar berhasil disimpan.
14. Transaksi dengan bukti gambar valid berhasil disimpan dan file dibersihkan saat transaksi dihapus.
15. Dashboard menampilkan metrik saldo, pemasukan, pengeluaran, dan peringatan anggaran.
16. Halaman laporan periodik dan ekspor CSV berfungsi dengan benar.
