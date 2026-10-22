# DailyCash

# Panduan Instalasi Proyek Laravel

Dokumen ini berisi panduan langkah demi langkah untuk menjalankan proyek Laravel di lingkungan lokal setelah melakukan clone repositori.

---

## Prasyarat

Sebelum memulai, pastikan perangkat Anda telah terpasang:
- **PHP** (sesuai versi yang dibutuhkan proyek)
- **Composer**
- **Node.js & npm** (opsional, jika menggunakan dependensi frontend)

---

## Langkah Instalasi

### Langkah 1 — Install Dependensi PHP
Unduh seluruh dependensi backend yang dibutuhkan oleh Laravel:

```bash
composer install
```

> **Penjelasan:** Composer akan membaca file `composer.json` serta `composer.lock`, lalu mengunduh semua library/dependensi Laravel ke dalam folder `vendor/`.

---

### Langkah 2 — Salin File Konfigurasi Environment (`.env`)
Salin file konfigurasi contoh menjadi file `.env` aktif.

- **PowerShell (Windows):**
  ```powershell
  Copy-Item .env.example .env
  ```
- **Bash / Command Prompt:**
  ```bash
  cp .env.example .env
  ```

> **Penjelasan:** File `.env` menyimpan pengaturan konfigurasi lokal proyek Anda, seperti nama database, kredensial akun, mail server, dan konfigurasi lingkungan aplikasi lainnya. Sesuaikan nilai di dalamnya jika diperlukan.

---

### Langkah 3 — Generate Application Key
Buat kunci enkripsi aplikasi:

```bash
php artisan key:generate
```

> **Penjelasan:** Perintah ini akan menghasilkan string acak dan otomatis mengisikannya ke variabel `APP_KEY` pada file `.env`. Kunci ini digunakan Laravel untuk mengamankan data sesi dan data terenkripsi lainnya.

---

### Langkah 4 — Install Dependensi Frontend *(Opsional)*
Jika proyek menggunakan build tool/asset frontend (memiliki file `package.json`):

```bash
npm install
```

*(Opsional: Jalankan server build development jika menggunakan Vite/Mix)*:
```bash
npm run dev
```

> **Penjelasan:** Perintah ini memasang paket-paket JavaScript dan CSS ke dalam folder `node_modules/`.

---

### Langkah 5 — Jalankan Server Lokal
Nyalakan server development internal Laravel:

```bash
php artisan serve
```

Setelah server aktif, buka browser dan akses alamat berikut:
👉 [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Catatan Tambahan (Database Migration)

Jika proyek Anda menggunakan database, pastikan konfigurasi DB pada `.env` sudah benar, lalu jalankan migrasi database:

```bash
php artisan migrate
```