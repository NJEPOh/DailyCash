# DailyCash — Sistem Catatan Keuangan Harian

**DailyCash** adalah aplikasi web pencatatan keuangan harian berbasis **Laravel** yang dirancang untuk membantu individu maupun pemilik usaha kecil mencatat pemasukan (*income*) dan pengeluaran (*expense*), memantau posisi saldo di berbagai rekening/dompet secara *real-time*, mengontrol anggaran bulanan, serta mengevaluasi kondisi keuangan melalui laporan periodik.

---

## Daftar Fitur

### Autentikasi & Isolasi Data Multi-User
- **Halaman Login sebagai Pintu Masuk Utama (`/`, `/login`)**: Pengguna yang belum login diarahkan otomatis ke halaman login saat membuka `/`, sedangkan pengguna yang sudah login diarahkan langsung ke `/dashboard`.
- **Halaman Registrasi (`/register`)**: Pendaftaran akun pribadi dengan validasi Bahasa Indonesia. Di dalam satu `DB::transaction()` atomik, sistem membuat akun pengguna dengan saldo `Rp0` (rekening & transaksi kosong) serta menyiapkan **7 kategori pemasukan default** dan **10 kategori pengeluaran default** milik pribadi pengguna tersebut melalui `DefaultCategoryService`.
- **Isolasi Kepemilikan Data (`user_id`)**: Pendekatan *shared database with user ownership* di mana setiap kategori, rekening/dompet, transaksi, bukti pembayaran, anggaran, dashboard, dan laporan difilter ketat berdasarkan `auth()->id()` serta dilindungi dari akses lintas pengguna (`403 Forbidden`).
- **Tipografi Responsif (Desktop vs Mobile) & UI Final**: Seluruh tampilan wireframe telah diganti dengan UI produksi final yang mengadaptasi ukuran font **12px pada mobile** dan **16–18px pada desktop** secara proporsional.

### Fitur Wajib
1. **CRUD Kategori Transaksi (`/categories`)**
   - Menampilkan daftar kategori milik pengguna beserta filter tipe (`income` / `expense`), jumlah transaksi, dan total akumulasi nominal.
   - Menambahkan, melihat detail, mengubah, dan menghapus kategori.
   - Perlindungan relasi: kategori yang sudah digunakan oleh transaksi tidak dapat diubah tipenya atau dihapus secara sembarangan agar histori tetap konsisten.
2. **CRUD Rekening / Dompet (`/accounts`)**
   - Menampilkan daftar rekening/dompet milik pengguna beserta saldo saat ini, total dana masuk, dan total dana keluar.
   - Menambahkan rekening/dompet baru (dengan nilai saldo awal bawaan `Rp0`), melihat detail mutasi rekening, mengubah nama rekening, dan menghapus rekening kosong.
   - Perlindungan relasi: rekening yang masih memiliki riwayat transaksi dilindungi dari penghapusan.
3. **CRUD Transaksi Terpadu (`/transactions`)**
   - Satu tabel terpadu (`transactions`) untuk mencatat pemasukan (`income`) dan pengeluaran (`expense`).
   - Formulir lengkap: Tanggal, Tipe Transaksi, Nominal Rupiah, Kategori (otomatis difilter sesuai tipe), Rekening/Dompet, Deskripsi opsional, dan **Upload Bukti Pembayaran (gambar opsional)**.
   - Validasi kepemilikan *foreign key* memastikan `category_id` dan `account_id` yang dipilih benar-benar milik pengguna yang sedang login.
   - Manajemen file bukti pembayaran menggunakan Laravel Storage (`public` disk) dan route terproteksi (`/transactions/{transaction}/proof`): validasi tipe gambar & ukuran (maks. 2MB), penghapusan file lama secara otomatis saat bukti diganti atau transaksi dihapus.
4. **Perhitungan & Konsistensi Saldo Otomatis (`DB::transaction()`)**
   - Pemasukan menambah saldo rekening; pengeluaran mengurangi saldo rekening.
   - Validasi ketat agar pengeluaran tidak melebihi saldo rekening yang dipilih (mencegah saldo negatif).
   - Perubahan (*edit*) transaksi secara atomik membalikkan dampak transaksi lama pada rekening asal dan menerapkan dampak transaksi baru pada rekening tujuan.
   - Penghapusan transaksi membalikkan dampak saldo secara konsisten.
5. **Filter Transaksi Multikriteria**
   - Filter berdasarkan rentang tanggal (`start_date` & `end_date`), tipe transaksi, kategori, dan rekening/dompet secara bersamaan.
   - Mendukung tombol **Terapkan Filter** dan **Hapus Filter**, serta mempertahankan query filter saat berpindah halaman *pagination*.
6. **Halaman Detail Transaksi dengan URI Dinamis (`/transactions/{id}`)**
   - Menggunakan *Route Model Binding* (mengembalikan HTTP `404` jika ID tidak ditemukan dan `403` jika milik pengguna lain).
   - Menampilkan rincian lengkap transaksi beserta pratinjau gambar bukti pembayaran.
7. **Dashboard Ringkasan Keuangan (`/dashboard`)**
   - **Total Saldo Keseluruhan** (`SUM(accounts.balance)` milik pengguna).
   - **Total Pemasukan Bulan Ini** dan **Total Pengeluaran Bulan Ini**.
   - Ringkasan saldo per rekening/dompet, pengeluaran terbesar per kategori, daftar transaksi terbaru, serta *empty state* informatif untuk pengguna baru (`Rp0`).
8. **Laporan Keuangan Periodik (`/reports`)**
   - Evaluasi keuangan berdasarkan periode **Bulanan**, **Tahunan**, atau **Rentang Tanggal Kustom**, serta filter per rekening.
   - Rekapitulasi pemasukan & pengeluaran per kategori beserta persentase proporsi.

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

> **Akun Demo Seeder & Konsistensi Saldo:**
> - **Email Demo:** `demo@dailycash.test`
> - **Password Demo:** `password123`
> - Pengguna baru yang mendaftar melalui `/register` akan mendapatkan ruang data keuangan pribadi yang **kosong (`Rp0`)** tanpa data contoh.
> - Untuk akun demo hasil seeder, `AccountSeeder` menginisialisasi 4 rekening (`Rekening BCA`, `Dompet Utama`, `OVO`, `Tunai`) dengan saldo awal `Rp0`, lalu `TransactionSeeder` menambahkan 14 transaksi contoh dan memperbarui saldo masing-masing rekening secara atomik di dalam `DB::transaction()`.

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

Aplikasi menggunakan pendekatan **shared database dengan user ownership (`user_id`)** pada seluruh tabel keuangan:

### 1. Tabel `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | `BIGINT` (PK) | ID pengguna |
| `name` | `VARCHAR(100)` | Nama lengkap pengguna |
| `email` | `VARCHAR(255)` (*unique*) | Alamat email login |
| `password` | `VARCHAR` | Password ter-hash (bcrypt) |
| `created_at` / `updated_at` | `TIMESTAMP` | Waktu pembuatan & pembaruan |

### 2. Tabel `categories`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | `BIGINT` (PK) | ID kategori |
| `user_id` | `BIGINT` (FK) | Relasi ke `users.id` (`CASCADE ON DELETE`) |
| `name` | `VARCHAR(50)` | Nama kategori (*unique* gabungan `[user_id, name, type]`) |
| `type` | `ENUM('income', 'expense')` | Tipe kategori pemasukan atau pengeluaran |
| `created_at` / `updated_at` | `TIMESTAMP` | Waktu pembuatan & pembaruan |

### 3. Tabel `accounts`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | `BIGINT` (PK) | ID rekening/dompet |
| `user_id` | `BIGINT` (FK) | Relasi ke `users.id` (`CASCADE ON DELETE`) |
| `name` | `VARCHAR(50)` | Nama rekening/dompet (*unique* gabungan `[user_id, name]`) |
| `balance` | `BIGINT` (Default `0`) | Saldo saat ini dalam satuan Rupiah penuh |
| `created_at` / `updated_at` | `TIMESTAMP` | Waktu pembuatan & pembaruan |

### 4. Tabel `transactions`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | `BIGINT` (PK) | ID transaksi |
| `user_id` | `BIGINT` (FK) | Relasi ke `users.id` (`CASCADE ON DELETE`) |
| `account_id` | `BIGINT` (FK) | Relasi ke `accounts.id` (`RESTRICT ON DELETE`) |
| `category_id` | `BIGINT` (FK) | Relasi ke `categories.id` (`RESTRICT ON DELETE`) |
| `type` | `ENUM('income', 'expense')` | Tipe transaksi (`income` / `expense`) |
| `amount` | `BIGINT` | Nominal transaksi dalam Rupiah (`> 0`) |
| `date` | `DATE` | Tanggal transaksi |
| `description` | `VARCHAR(255)`, *nullable* | Deskripsi / keterangan opsional |
| `proof_image` | `VARCHAR`, *nullable* | Path file gambar bukti pembayaran di disk `public` |
| `created_at` / `updated_at` | `TIMESTAMP` | Waktu pembuatan & pembaruan |

### 5. Tabel `budgets` (Pengayaan Anggaran Bulanan)
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | `BIGINT` (PK) | ID anggaran |
| `user_id` | `BIGINT` (FK) | Relasi ke `users.id` (`CASCADE ON DELETE`) |
| `month` | `VARCHAR(7)` | Periode bulan dalam format `YYYY-MM` (*unique* gabungan `[user_id, month]`) |
| `amount` | `BIGINT` | Batas anggaran pengeluaran bulanan (Rupiah) |
| `created_at` / `updated_at` | `TIMESTAMP` | Waktu pembuatan & pembaruan |

### Relasi Eloquent
- `User` **hasMany** `Category`, `Account`, `Transaction`, `Budget`
- `Account` **belongsTo** `User` & **hasMany** `Transaction`
- `Category` **belongsTo** `User` & **hasMany** `Transaction`
- `Transaction` **belongsTo** `User`, `Account`, `Category`
- `Budget` **belongsTo** `User`

---

## Menjalankan Pengujian (Testing)

Proyek ini dilengkapi dengan **35 skenario pengujian otomatis** menggunakan **Pest PHP** (di dalam `tests/Feature/AuthAndMultiUserIsolationTest.php`, `tests/Feature/DailyCashTest.php`, `tests/Feature/ExampleTest.php`, dan `tests/Unit/ExampleTest.php`) yang berjalan menggunakan database SQLite `:memory:`.

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
17. Seluruh halaman menggunakan UI final tanpa wireframe dan menerapkan tipografi responsif desktop 16–18px serta mobile 12px secara konsisten.

### Daftar Skenario Pengujian di `tests/Feature/AuthAndMultiUserIsolationTest.php`:
1. Pengguna yang belum login diarahkan ke halaman login ketika membuka aplikasi.
2. Pengguna yang sudah login diarahkan ke dashboard.
3. Registrasi pengguna baru otomatis membuat 7 kategori pemasukan dan 10 kategori pengeluaran default dengan rekening serta transaksi kosong.
4. Email yang sudah digunakan tidak dapat didaftarkan kembali.
5. Login dengan kredensial benar berhasil dan mengarahkan ke dashboard.
6. Login dengan kredensial salah ditolak dengan pesan kesalahan Bahasa Indonesia.
7. Logout mengakhiri session pengguna dan mengarahkan ke halaman login.
8. Pengguna baru mendapatkan kondisi awal saldo `Rp0`, rekening kosong, transaksi kosong, dan 17 kategori default siap pakai.
9. Pengguna A tidak dapat melihat, mengubah, atau menghapus kategori milik Pengguna B (`403 Forbidden`).
10. Pengguna A tidak dapat mengakses, mengubah, atau menghapus rekening milik Pengguna B (`403 Forbidden`).
11. Pengguna A tidak dapat melihat, mengubah, atau menghapus transaksi maupun bukti pembayaran milik Pengguna B (`403 Forbidden`).
12. Transaksi tidak dapat dibuat menggunakan kategori atau rekening milik pengguna lain.
13. Perhitungan dashboard dan laporan hanya menggunakan data pengguna yang sedang login.
14. Seluruh proses CRUD kategori, rekening, dan transaksi tetap berfungsi dengan pembatasan kepemilikan data.
15. Login, logout, dan membuka dashboard berulang kali tidak menduplikasi kategori default, serta kategori default dapat diubah, dihapus, dan ditambah.
16. Kegagalan pembuatan kategori default saat registrasi membatalkan transaksi pembuatan akun (*atomic rollback*).
