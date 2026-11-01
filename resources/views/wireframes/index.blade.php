<x-layouts.app title="Ringkasan Modul Aplikasi">
    <x-page-header
        title="Modul & Fitur DailyCash"
        subtitle="Akses cepat ke seluruh modul pengelolaan keuangan harian Anda dengan antarmuka responsif."
    >
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 lg:px-4 lg:py-2 rounded-lg lg:rounded-xl bg-slate-900 text-white text-xs lg:text-sm font-semibold hover:bg-slate-800 transition">
            Buka Dashboard Utama &rarr;
        </a>
    </x-page-header>

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-5 xs-single-col">
        <a href="{{ route('dashboard') }}" class="bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-4 lg:p-6 shadow-2xs hover:border-emerald-300 hover:shadow-xs transition flex flex-col justify-between space-y-3">
            <div class="space-y-1.5">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] lg:text-sm font-semibold bg-emerald-50 text-emerald-700">Ringkasan Utama</span>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-slate-900">Dashboard Keuangan</h2>
                <p class="text-xs md:text-sm lg:text-base text-slate-600">Pantau total saldo keseluruhan, arus kas 6 bulan terakhir, realisasi anggaran bulanan, dan transaksi terbaru.</p>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-emerald-600">Buka Dashboard &rarr;</span>
        </a>

        <a href="{{ route('transactions.index') }}" class="bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-4 lg:p-6 shadow-2xs hover:border-emerald-300 hover:shadow-xs transition flex flex-col justify-between space-y-3">
            <div class="space-y-1.5">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] lg:text-sm font-semibold bg-sky-50 text-sky-700">Pencatatan Harian</span>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-slate-900">Daftar & Filter Transaksi</h2>
                <p class="text-xs md:text-sm lg:text-base text-slate-600">Kelola pemasukan dan pengeluaran lengkap dengan filter tanggal, kategori, rekening, serta lampiran bukti pembayaran.</p>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-emerald-600">Kelola Transaksi &rarr;</span>
        </a>

        <a href="{{ route('transactions.create') }}" class="bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-4 lg:p-6 shadow-2xs hover:border-emerald-300 hover:shadow-xs transition flex flex-col justify-between space-y-3">
            <div class="space-y-1.5">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] lg:text-sm font-semibold bg-emerald-50 text-emerald-700">Input Cepat</span>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-slate-900">Catat Transaksi Baru</h2>
                <p class="text-xs md:text-sm lg:text-base text-slate-600">Tambahkan transaksi pemasukan atau pengeluaran dengan pembaruan saldo rekening otomatis secara real-time.</p>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-emerald-600">+ Tambah Transaksi &rarr;</span>
        </a>

        <a href="{{ route('accounts.index') }}" class="bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-4 lg:p-6 shadow-2xs hover:border-emerald-300 hover:shadow-xs transition flex flex-col justify-between space-y-3">
            <div class="space-y-1.5">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] lg:text-sm font-semibold bg-indigo-50 text-indigo-700">Sumber Dana</span>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-slate-900">Rekening & Dompet</h2>
                <p class="text-xs md:text-sm lg:text-base text-slate-600">Kelola rekening bank, dompet digital (e-wallet), dan kas tunai beserta rincian mutasi dana masuk dan keluar.</p>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-emerald-600">Kelola Rekening &rarr;</span>
        </a>

        <a href="{{ route('categories.index') }}" class="bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-4 lg:p-6 shadow-2xs hover:border-emerald-300 hover:shadow-xs transition flex flex-col justify-between space-y-3">
            <div class="space-y-1.5">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] lg:text-sm font-semibold bg-amber-50 text-amber-700">Master Data</span>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-slate-900">Kategori Transaksi</h2>
                <p class="text-xs md:text-sm lg:text-base text-slate-600">Atur kategori pemasukan dan pengeluaran pribadi Anda untuk pengelompokan laporan yang terstruktur.</p>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-emerald-600">Kelola Kategori &rarr;</span>
        </a>

        <a href="{{ route('reports.index') }}" class="bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-4 lg:p-6 shadow-2xs hover:border-emerald-300 hover:shadow-xs transition flex flex-col justify-between space-y-3">
            <div class="space-y-1.5">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] lg:text-sm font-semibold bg-rose-50 text-rose-700">Analitik & Ekspor</span>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-slate-900">Laporan Periodik</h2>
                <p class="text-xs md:text-sm lg:text-base text-slate-600">Evaluasi rekapitulasi keuangan bulanan, tahunan, atau rentang tanggal khusus serta unduh laporan CSV.</p>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-emerald-600">Lihat Laporan &rarr;</span>
        </a>
    </div>
</x-layouts.app>
