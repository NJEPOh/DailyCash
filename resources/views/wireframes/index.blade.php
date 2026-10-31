<x-layouts.app title="Wireframe UI (Low-Fidelity)">
    <x-page-header
        title="Rancangan Wireframe Antarmuka (Low-Fidelity)"
        subtitle="Dokumentasi struktur tata letak, hierarki informasi, dan alur navigasi 8 halaman utama DailyCash (Bagian 8.1)."
    >
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
            Lihat Implementasi Final &rarr;
        </a>
    </x-page-header>

    {{-- Penjelasan Arsitektur Navigasi & Responsivitas --}}
    <div class="rounded-2xl border-2 border-dashed border-neutral-400 bg-neutral-100 p-6 text-neutral-800 space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 border-b border-neutral-300 pb-3">
            <div>
                <span class="text-[11px] font-mono uppercase px-2 py-0.5 bg-neutral-300 text-neutral-800 rounded">SPESIFIKASI STRUKTUR GLOBAL</span>
                <h2 class="text-lg font-bold mt-1">Pola Layout, Navigasi, dan Responsivitas (Desktop & Mobile)</h2>
            </div>
            <span class="text-xs font-mono text-neutral-600">Low-Fidelity Neutral Palette (#F5F5F5 / #D4D4D4 / #404040)</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 text-xs font-mono">
            <div class="border border-neutral-400 bg-white p-4 space-y-2">
                <p class="font-bold text-neutral-900">[A] Desktop Layout (&ge; 1024px)</p>
                <p class="text-neutral-600">Sidebar Kiri Tetap (256px) + Header Konten + Area Kerja Utama (Max 1280px) + Footer. Kartu ringkasan dalam grid 3 kolom.</p>
            </div>
            <div class="border border-neutral-400 bg-white p-4 space-y-2">
                <p class="font-bold text-neutral-900">[B] Mobile Layout (&lt; 1024px)</p>
                <p class="text-neutral-600">Top Navbar dengan tombol Hamburger Drawer. Grid kartu bertumpuk 1 kolom, tabel mendukung scroll horizontal.</p>
            </div>
            <div class="border border-neutral-400 bg-white p-4 space-y-2">
                <p class="font-bold text-neutral-900">[C] Alur Pengguna (User Flow)</p>
                <p class="text-neutral-600">Master Kategori &amp; Rekening &rarr; Form Transaksi (Validasi Saldo &amp; Tipe) &rarr; Daftar/Filter/Detail &rarr; Dashboard &amp; Laporan Periodik.</p>
            </div>
        </div>
    </div>

    {{-- Grid 8 Wireframe Halaman Utama --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 font-mono text-xs text-neutral-700">
        {{-- 1. Wireframe Dashboard --}}
        <div class="border-2 border-neutral-400 bg-neutral-50 p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-2">
                <span class="font-bold text-sm text-neutral-900">1. WIREFRAME: DASHBOARD (/)</span>
                <a href="{{ route('dashboard') }}" class="underline text-neutral-900">Buka Halaman</a>
            </div>
            <div class="border border-dashed border-neutral-400 p-2 bg-neutral-200 text-center">[BANNER PERINGATAN ANGGARAN (Kondisional jika Pengeluaran &gt; Anggaran)]</div>
            <div class="grid grid-cols-3 gap-2">
                <div class="border border-neutral-400 bg-white p-3">[KARTU 1]<br>Total Saldo<br><strong>Rp14.370.000</strong></div>
                <div class="border border-neutral-400 bg-white p-3">[KARTU 2]<br>Pemasukan Bulan Ini<br><strong>Rp11.900.000</strong></div>
                <div class="border border-neutral-400 bg-white p-3">[KARTU 3]<br>Pengeluaran Bulan Ini<br><strong>Rp3.230.000</strong></div>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div class="col-span-2 border border-neutral-400 bg-white p-3 h-28 flex flex-col justify-between">
                    <span>[GRAFIK BATANG ARUS KAS 6 BULAN]</span>
                    <div class="flex items-end gap-2 h-12 border-b border-neutral-300 px-2">
                        <div class="w-4 h-6 bg-neutral-400"></div><div class="w-4 h-4 bg-neutral-300"></div>
                        <div class="w-4 h-10 bg-neutral-400"></div><div class="w-4 h-7 bg-neutral-300"></div>
                        <div class="w-4 h-12 bg-neutral-500"></div><div class="w-4 h-5 bg-neutral-300"></div>
                    </div>
                </div>
                <div class="border border-neutral-400 bg-white p-3 space-y-2">
                    <span>[ANGGARAN BULANAN]</span>
                    <div class="w-full h-3 bg-neutral-200 border border-neutral-400"><div class="w-2/3 h-full bg-neutral-500"></div></div>
                    <div class="border border-neutral-300 p-1 text-center">[Input &amp; Tombol Simpan]</div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div class="border border-neutral-400 bg-white p-3">[RINGKASAN SALDO PER REKENING]<br>- Rekening BCA<br>- Dompet Utama<br>- OVO / Tunai</div>
                <div class="col-span-2 border border-neutral-400 bg-white p-3">[TABEL TRANSAKSI TERBARU]<br>Tanggal | Kategori | Rekening | Tipe | Nominal</div>
            </div>
        </div>

        {{-- 2. Wireframe Daftar & Filter Transaksi --}}
        <div class="border-2 border-neutral-400 bg-neutral-50 p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-2">
                <span class="font-bold text-sm text-neutral-900">2. WIREFRAME: DAFTAR TRANSAKSI (/transactions)</span>
                <a href="{{ route('transactions.index') }}" class="underline text-neutral-900">Buka Halaman</a>
            </div>
            <div class="flex justify-between items-center border border-neutral-400 bg-white p-2.5">
                <span>[JUDUL: Daftar Transaksi]</span>
                <span class="border border-neutral-600 px-2 py-1 bg-neutral-200">[+ Tambah Transaksi]</span>
            </div>
            <div class="border border-neutral-400 bg-white p-3 space-y-2">
                <span>[PANEL FILTER MULTIKRITERIA]</span>
                <div class="grid grid-cols-5 gap-1.5">
                    <div class="border border-neutral-300 p-1.5">[Tgl Awal]</div>
                    <div class="border border-neutral-300 p-1.5">[Tgl Akhir]</div>
                    <div class="border border-neutral-300 p-1.5">[Tipe]</div>
                    <div class="border border-neutral-300 p-1.5">[Kategori]</div>
                    <div class="border border-neutral-300 p-1.5">[Rekening]</div>
                </div>
                <div class="flex gap-2 justify-end">
                    <span class="border border-neutral-400 px-2 py-0.5">[Reset Filter]</span>
                    <span class="border border-neutral-700 bg-neutral-300 px-2 py-0.5">[Terapkan Filter]</span>
                </div>
            </div>
            <div class="border border-neutral-400 bg-white p-3 space-y-1.5">
                <div>[TABEL DATA TRANSAKSI]</div>
                <div class="border-b border-neutral-300 py-1">Tgl | Kategori &amp; Deskripsi | Rekening | Label Tipe | Nominal Rp | Bukti | [Detail][Edit][Hapus]</div>
                <div class="border-b border-neutral-200 py-1 text-neutral-500">12/10/2026 | Makan &amp; Minum | Dompet Utama | [Pengeluaran] | -Rp420.000 | [Lihat]</div>
                <div class="pt-2 flex justify-between"><span>[Info Halaman 1 dari N]</span><span>[&laquo; Prev] [1] [2] [Next &raquo;]</span></div>
            </div>
        </div>

        {{-- 3. Wireframe Form Tambah Transaksi --}}
        <div class="border-2 border-neutral-400 bg-neutral-50 p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-2">
                <span class="font-bold text-sm text-neutral-900">3. WIREFRAME: FORM TAMBAH TRANSAKSI (/transactions/create)</span>
                <a href="{{ route('transactions.create') }}" class="underline text-neutral-900">Buka Halaman</a>
            </div>
            <div class="border border-neutral-400 bg-white p-4 space-y-2.5">
                <div class="grid grid-cols-2 gap-2">
                    <div class="border-2 border-neutral-700 bg-neutral-200 p-2 text-center">[O] Pemasukan (Income)</div>
                    <div class="border border-neutral-400 p-2 text-center">[ ] Pengeluaran (Expense)</div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div class="border border-neutral-400 p-2">[Input Tanggal: YYYY-MM-DD]</div>
                    <div class="border border-neutral-400 p-2">[Input Nominal (Rp): &gt; 0]</div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div class="border border-neutral-400 p-2">[Select Kategori (Sesuai Tipe)]</div>
                    <div class="border border-neutral-400 p-2">[Select Rekening + Info Saldo]</div>
                </div>
                <div class="border border-neutral-400 p-2">[Textarea Deskripsi (Opsional, Maks 255 karakter)]</div>
                <div class="border border-dashed border-neutral-400 p-3 text-center">[Input File Upload Bukti Gambar (Opsional, JPG/PNG/WEBP Maks 2MB)]</div>
                <div class="flex justify-end gap-2 pt-1">
                    <span class="border border-neutral-400 px-3 py-1">[Batal]</span>
                    <span class="border border-neutral-800 bg-neutral-300 px-3 py-1 font-bold">[Simpan Transaksi]</span>
                </div>
            </div>
        </div>

        {{-- 4. Wireframe Form Edit Transaksi --}}
        <div class="border-2 border-neutral-400 bg-neutral-50 p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-2">
                <span class="font-bold text-sm text-neutral-900">4. WIREFRAME: FORM EDIT TRANSAKSI (/transactions/{id}/edit)</span>
                <a href="{{ route('transactions.index') }}" class="underline text-neutral-900">Pilih dari Daftar</a>
            </div>
            <div class="border border-neutral-400 bg-white p-4 space-y-2.5">
                <div class="border border-dashed border-neutral-400 p-2 bg-neutral-100">
                    [INFO SALDO: Perubahan nominal/rekening otomatis membalikkan dampak transaksi lama dalam DB::transaction()]
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div class="border border-neutral-400 p-2">[Radio Tipe: Income / Expense]</div>
                    <div class="border border-neutral-400 p-2">[Input Tanggal &amp; Nominal Lama]</div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div class="border border-neutral-400 p-2">[Dropdown Kategori Terpilih]</div>
                    <div class="border border-neutral-400 p-2">[Dropdown Rekening Terpilih]</div>
                </div>
                <div class="border border-neutral-400 p-2 flex items-center justify-between">
                    <span>[Preview Bukti Gambar Saat Ini (Jika Ada)]</span>
                    <span>[x] Hapus / Ganti File Bukti</span>
                </div>
                <div class="flex justify-end gap-2 pt-1">
                    <span class="border border-neutral-400 px-3 py-1">[Kembali]</span>
                    <span class="border border-neutral-800 bg-neutral-300 px-3 py-1 font-bold">[Perbarui Transaksi]</span>
                </div>
            </div>
        </div>

        {{-- 5. Wireframe Detail Transaksi Dinamis --}}
        <div class="border-2 border-neutral-400 bg-neutral-50 p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-2">
                <span class="font-bold text-sm text-neutral-900">5. WIREFRAME: DETAIL TRANSAKSI (/transactions/{id})</span>
                <a href="{{ route('transactions.index') }}" class="underline text-neutral-900">Pilih Transaksi</a>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div class="col-span-2 border border-neutral-400 bg-white p-3 space-y-2">
                    <div class="border-b border-neutral-300 pb-2 flex justify-between">
                        <span>[Badge Tipe: Pemasukan/Pengeluaran]</span>
                        <strong>[Nominal Besar: Rp1.450.000]</strong>
                    </div>
                    <div>- Tanggal Transaksi: DD Month YYYY</div>
                    <div>- Kategori: Nama Kategori</div>
                    <div>- Rekening/Dompet: Nama Rekening (Saldo Saat Ini)</div>
                    <div>- Deskripsi: Catatan lengkap transaksi</div>
                    <div class="flex gap-2 pt-2">
                        <span class="border border-neutral-500 px-2 py-1">[Edit Transaksi]</span>
                        <span class="border border-neutral-500 px-2 py-1">[Hapus (Konfirmasi)]</span>
                    </div>
                </div>
                <div class="border border-neutral-400 bg-white p-3 flex flex-col items-center justify-center text-center">
                    <div class="w-full h-28 border border-dashed border-neutral-400 flex items-center justify-center bg-neutral-100">
                        [AREA PREVIEW GAMBAR BUKTI PEMBAYARAN / STATUS KOSONG]
                    </div>
                </div>
            </div>
        </div>

        {{-- 6. Wireframe Pengelolaan Kategori --}}
        <div class="border-2 border-neutral-400 bg-neutral-50 p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-2">
                <span class="font-bold text-sm text-neutral-900">6. WIREFRAME: CRUD KATEGORI (/categories)</span>
                <a href="{{ route('categories.index') }}" class="underline text-neutral-900">Buka Halaman</a>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div class="border border-neutral-400 bg-white p-3 space-y-2">
                    <span class="font-bold">[FORM CEPAT TAMBAH KATEGORI]</span>
                    <div class="border border-neutral-300 p-1.5">[Input Nama Kategori]</div>
                    <div class="border border-neutral-300 p-1.5">[Select Tipe: Income/Expense]</div>
                    <div class="border border-neutral-700 bg-neutral-300 p-1.5 text-center">[+ Simpan Kategori]</div>
                </div>
                <div class="col-span-2 border border-neutral-400 bg-white p-3 space-y-2">
                    <div class="flex justify-between">
                        <span>[TABEL DAFTAR KATEGORI]</span>
                        <span>[Filter: Semua | Pemasukan | Pengeluaran]</span>
                    </div>
                    <div class="border-t border-neutral-300 pt-1">Nama | Tipe | Jml Transaksi | Total Rp | [Detail][Edit][Hapus*]</div>
                    <div class="text-[11px] text-neutral-500">*Penghapusan ditolak aman jika kategori masih memiliki transaksi.</div>
                </div>
            </div>
        </div>

        {{-- 7. Wireframe Pengelolaan Rekening/Dompet --}}
        <div class="border-2 border-neutral-400 bg-neutral-50 p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-2">
                <span class="font-bold text-sm text-neutral-900">7. WIREFRAME: CRUD REKENING &amp; DOMPET (/accounts)</span>
                <a href="{{ route('accounts.index') }}" class="underline text-neutral-900">Buka Halaman</a>
            </div>
            <div class="border border-neutral-400 bg-white p-2.5 flex justify-between items-center">
                <span>[TOTAL SALDO SELURUH REKENING: RpXX.XXX.XXX]</span>
                <span class="border border-neutral-700 bg-neutral-200 px-2 py-1">[+ Tambah Rekening]</span>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="border border-neutral-400 bg-white p-3 space-y-1">
                    <div class="font-bold">[KARTU REKENING: Rekening BCA]</div>
                    <div>Saldo Saat Ini: <strong>Rp11.295.000</strong></div>
                    <div class="text-neutral-500">+Pemasukan | -Pengeluaran | N Transaksi</div>
                    <div class="pt-1 flex gap-1.5"><span>[Detail]</span><span>[Ubah Nama]</span><span>[Hapus]</span></div>
                </div>
                <div class="border border-neutral-400 bg-white p-3 space-y-1">
                    <div class="font-bold">[KARTU REKENING: Dompet Utama]</div>
                    <div>Saldo Saat Ini: <strong>Rp3.080.000</strong></div>
                    <div class="text-neutral-500">+Pemasukan | -Pengeluaran | N Transaksi</div>
                    <div class="pt-1 flex gap-1.5"><span>[Detail]</span><span>[Ubah Nama]</span><span>[Hapus]</span></div>
                </div>
            </div>
        </div>

        {{-- 8. Wireframe Laporan Periodik --}}
        <div class="border-2 border-neutral-400 bg-neutral-50 p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-2">
                <span class="font-bold text-sm text-neutral-900">8. WIREFRAME: LAPORAN PERIODIK (/reports)</span>
                <a href="{{ route('reports.index') }}" class="underline text-neutral-900">Buka Halaman</a>
            </div>
            <div class="border border-neutral-400 bg-white p-2.5 flex flex-wrap justify-between items-center gap-2">
                <span>[FILTER PERIODE: Bulanan / Tahunan / Rentang Tanggal + Rekening]</span>
                <span class="border border-neutral-700 bg-neutral-200 px-2 py-1">[Ekspor CSV]</span>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div class="border border-neutral-400 bg-white p-2.5">[Total Pemasukan Periode]</div>
                <div class="border border-neutral-400 bg-white p-2.5">[Total Pengeluaran Periode]</div>
                <div class="border border-neutral-400 bg-white p-2.5">[Arus Kas Bersih (Selisih)]</div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="border border-neutral-400 bg-white p-2.5">[REKAP PER KATEGORI PEMASUKAN &amp; %]</div>
                <div class="border border-neutral-400 bg-white p-2.5">[REKAP PER KATEGORI PENGELUARAN &amp; %]</div>
            </div>
            <div class="border border-neutral-400 bg-white p-2.5">[TABEL RINCIAN TRANSAKSI PERIODE + PAGINATION]</div>
        </div>
    </div>
</x-layouts.app>
