<x-layouts.app title="Wireframe UI (Low-Fidelity)">
    <x-page-header
        title="Rancangan Wireframe Antarmuka (Low-Fidelity)"
        subtitle="Dokumentasi struktur tata letak compact, grid mobile 2 kolom, dan alur navigasi 8 halaman utama DailyCash (Bagian 8.1)."
    >
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 transition">
            Lihat Implementasi Final &rarr;
        </a>
    </x-page-header>

    {{-- Penjelasan Arsitektur Navigasi & Responsivitas --}}
    <div class="rounded-xl border border-dashed border-neutral-400 bg-neutral-100 p-3.5 sm:p-4 text-neutral-800 space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 border-b border-neutral-300 pb-2">
            <div>
                <span class="text-[11px] font-mono uppercase px-2 py-0.5 bg-neutral-300 text-neutral-800 rounded">SPESIFIKASI STRUKTUR GLOBAL</span>
                <h2 class="text-sm sm:text-base font-bold mt-1">Pola Layout Mobile 2-Kolom, Tipografi Compact, dan Responsivitas</h2>
            </div>
            <span class="text-[11px] font-mono text-neutral-600">Low-Fidelity Neutral Palette</span>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-3 gap-2.5 text-[11px] font-mono xs-single-col">
            <div class="border border-neutral-400 bg-white p-2.5 space-y-1">
                <p class="font-bold text-neutral-900">[A] Desktop (&ge; 1024px)</p>
                <p class="text-neutral-600">Sidebar Kiri (224px) + Header + Konten Utama. Kartu statistik 4 kolom, akun 3-4 kolom.</p>
            </div>
            <div class="border border-neutral-400 bg-white p-2.5 space-y-1">
                <p class="font-bold text-neutral-900">[B] Mobile 2-Kolom (&lt; 1024px)</p>
                <p class="text-neutral-600">Kartu statistik, saldo rekening, kategori, dan field form berpasangan tampil 2 kolom.</p>
            </div>
            <div class="col-span-2 lg:col-span-1 border border-neutral-400 bg-white p-2.5 space-y-1">
                <p class="font-bold text-neutral-900">[C] Hierarki Tipografi</p>
                <p class="text-neutral-600">Teks 12px, Label 11px, Subheading 14-16px, Heading 18-20px, Angka Statistik 20-24px.</p>
            </div>
        </div>
    </div>

    {{-- Grid 8 Wireframe Halaman Utama --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3.5 font-mono text-[11px] text-neutral-700">
        {{-- 1. Wireframe Dashboard --}}
        <div class="border border-neutral-400 bg-neutral-50 p-3.5 rounded-xl space-y-2">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-1.5">
                <span class="font-bold text-xs text-neutral-900">1. WIREFRAME: DASHBOARD (/)</span>
                <a href="{{ route('dashboard') }}" class="underline text-neutral-900">Buka</a>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="border border-neutral-400 bg-white p-2">[KARTU 1] Total Saldo</div>
                <div class="border border-neutral-400 bg-white p-2">[KARTU 2] Pemasukan</div>
                <div class="border border-neutral-400 bg-white p-2">[KARTU 3] Pengeluaran</div>
                <div class="border border-neutral-400 bg-white p-2">[KARTU 4] Selisih Kas</div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="border border-neutral-400 bg-white p-2">[GRAFIK ARUS KAS 6 BULAN]</div>
                <div class="border border-neutral-400 bg-white p-2">[ANGGARAN BULANAN]</div>
            </div>
            <div class="border border-neutral-400 bg-white p-2">[GRID 2 KOLOM SALDO REKENING] + [TABEL TRANSAKSI TERBARU]</div>
        </div>

        {{-- 2. Wireframe Daftar & Filter Transaksi --}}
        <div class="border border-neutral-400 bg-neutral-50 p-3.5 rounded-xl space-y-2">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-1.5">
                <span class="font-bold text-xs text-neutral-900">2. WIREFRAME: DAFTAR TRANSAKSI (/transactions)</span>
                <a href="{{ route('transactions.index') }}" class="underline text-neutral-900">Buka</a>
            </div>
            <div class="border border-neutral-400 bg-white p-2 space-y-1.5">
                <span>[PANEL FILTER 2 KOLOM MOBILE]</span>
                <div class="grid grid-cols-2 gap-1.5">
                    <div class="border border-neutral-300 p-1">[Tgl Awal]</div>
                    <div class="border border-neutral-300 p-1">[Tgl Akhir]</div>
                    <div class="border border-neutral-300 p-1">[Tipe]</div>
                    <div class="border border-neutral-300 p-1">[Kategori]</div>
                </div>
            </div>
            <div class="border border-neutral-400 bg-white p-2">
                [TABEL COMPACT TRANSAKSI + PAGINATION]
            </div>
        </div>

        {{-- 3. Wireframe Form Tambah Transaksi --}}
        <div class="border border-neutral-400 bg-neutral-50 p-3.5 rounded-xl space-y-2">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-1.5">
                <span class="font-bold text-xs text-neutral-900">3. WIREFRAME: FORM TAMBAH (/transactions/create)</span>
                <a href="{{ route('transactions.create') }}" class="underline text-neutral-900">Buka</a>
            </div>
            <div class="border border-neutral-400 bg-white p-2.5 space-y-1.5">
                <div class="grid grid-cols-2 gap-1.5">
                    <div class="border border-neutral-700 bg-neutral-200 p-1.5 text-center">[O] Pemasukan</div>
                    <div class="border border-neutral-400 p-1.5 text-center">[ ] Pengeluaran</div>
                </div>
                <div class="grid grid-cols-2 gap-1.5">
                    <div class="border border-neutral-400 p-1.5">[Input Tanggal]</div>
                    <div class="border border-neutral-400 p-1.5">[Input Nominal Rp]</div>
                </div>
                <div class="grid grid-cols-2 gap-1.5">
                    <div class="border border-neutral-400 p-1.5">[Select Kategori]</div>
                    <div class="border border-neutral-400 p-1.5">[Select Rekening]</div>
                </div>
                <div class="border border-neutral-400 p-1.5">[Deskripsi Lebar Penuh &amp; Upload Bukti Opsional]</div>
            </div>
        </div>

        {{-- 4. Wireframe Form Edit Transaksi --}}
        <div class="border border-neutral-400 bg-neutral-50 p-3.5 rounded-xl space-y-2">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-1.5">
                <span class="font-bold text-xs text-neutral-900">4. WIREFRAME: FORM EDIT (/transactions/{id}/edit)</span>
                <a href="{{ route('transactions.index') }}" class="underline text-neutral-900">Pilih</a>
            </div>
            <div class="border border-neutral-400 bg-white p-2.5 space-y-1.5">
                <div class="grid grid-cols-2 gap-1.5">
                    <div class="border border-neutral-400 p-1.5">[Tipe Transaksi]</div>
                    <div class="border border-neutral-400 p-1.5">[Tanggal &amp; Nominal]</div>
                </div>
                <div class="grid grid-cols-2 gap-1.5">
                    <div class="border border-neutral-400 p-1.5">[Kategori Terpilih]</div>
                    <div class="border border-neutral-400 p-1.5">[Rekening Terpilih]</div>
                </div>
                <div class="border border-neutral-400 p-1.5">[Preview Bukti Gambar &amp; Tombol Simpan Perubahan]</div>
            </div>
        </div>

        {{-- 5. Wireframe Detail Transaksi Dinamis --}}
        <div class="border border-neutral-400 bg-neutral-50 p-3.5 rounded-xl space-y-2">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-1.5">
                <span class="font-bold text-xs text-neutral-900">5. WIREFRAME: DETAIL TRANSAKSI (/transactions/{id})</span>
                <a href="{{ route('transactions.index') }}" class="underline text-neutral-900">Pilih</a>
            </div>
            <div class="border border-neutral-400 bg-white p-2.5 space-y-1.5">
                <div class="grid grid-cols-2 gap-1.5">
                    <div class="border border-neutral-300 p-1.5">[Tanggal]</div>
                    <div class="border border-neutral-300 p-1.5">[Kategori]</div>
                    <div class="border border-neutral-300 p-1.5">[Rekening &amp; Saldo]</div>
                    <div class="border border-neutral-300 p-1.5">[Timestamp]</div>
                </div>
                <div class="border border-dashed border-neutral-400 p-2 text-center">[PREVIEW GAMBAR BUKTI PEMBAYARAN]</div>
            </div>
        </div>

        {{-- 6. Wireframe Pengelolaan Kategori --}}
        <div class="border border-neutral-400 bg-neutral-50 p-3.5 rounded-xl space-y-2">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-1.5">
                <span class="font-bold text-xs text-neutral-900">6. WIREFRAME: CRUD KATEGORI (/categories)</span>
                <a href="{{ route('categories.index') }}" class="underline text-neutral-900">Buka</a>
            </div>
            <div class="border border-neutral-400 bg-white p-2.5 space-y-1.5">
                <div class="grid grid-cols-2 gap-1.5">
                    <div class="border border-neutral-300 p-1.5">[Input Nama Kategori]</div>
                    <div class="border border-neutral-300 p-1.5">[Select Tipe]</div>
                </div>
                <div class="grid grid-cols-2 gap-1.5">
                    <div class="border border-neutral-400 p-1.5">[Kartu Kategori 1]</div>
                    <div class="border border-neutral-400 p-1.5">[Kartu Kategori 2]</div>
                </div>
            </div>
        </div>

        {{-- 7. Wireframe Pengelolaan Rekening/Dompet --}}
        <div class="border border-neutral-400 bg-neutral-50 p-3.5 rounded-xl space-y-2">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-1.5">
                <span class="font-bold text-xs text-neutral-900">7. WIREFRAME: REKENING &amp; DOMPET (/accounts)</span>
                <a href="{{ route('accounts.index') }}" class="underline text-neutral-900">Buka</a>
            </div>
            <div class="grid grid-cols-2 gap-1.5">
                <div class="border border-neutral-400 bg-white p-2">[Rekening BCA]<br><strong>Rp11.295.000</strong></div>
                <div class="border border-neutral-400 bg-white p-2">[Dompet Utama]<br><strong>Rp3.080.000</strong></div>
                <div class="border border-neutral-400 bg-white p-2">[OVO]<br><strong>Rp925.000</strong></div>
                <div class="border border-neutral-400 bg-white p-2">[Tunai]<br><strong>Rp770.000</strong></div>
            </div>
        </div>

        {{-- 8. Wireframe Laporan Periodik --}}
        <div class="border border-neutral-400 bg-neutral-50 p-3.5 rounded-xl space-y-2">
            <div class="flex items-center justify-between border-b border-neutral-300 pb-1.5">
                <span class="font-bold text-xs text-neutral-900">8. WIREFRAME: LAPORAN PERIODIK (/reports)</span>
                <a href="{{ route('reports.index') }}" class="underline text-neutral-900">Buka</a>
            </div>
            <div class="grid grid-cols-2 gap-1.5">
                <div class="border border-neutral-400 bg-white p-2">[Total Pemasukan]</div>
                <div class="border border-neutral-400 bg-white p-2">[Total Pengeluaran]</div>
                <div class="border border-neutral-400 bg-white p-2">[Selisih Bersih]</div>
                <div class="border border-neutral-400 bg-white p-2">[Total Transaksi]</div>
            </div>
            <div class="border border-neutral-400 bg-white p-2">[REKAP KATEGORI 2 KOLOM + EKSPOR CSV]</div>
        </div>
    </div>
</x-layouts.app>
