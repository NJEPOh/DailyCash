<x-layouts.app title="Ringkasan Modul Aplikasi">
    <x-page-header
        title="Modul & Fitur DailyCash"
        subtitle="Akses cepat ke seluruh modul pengelolaan keuangan harian Anda dengan antarmuka responsif."
    >
        <a href="{{ route('dashboard') }}" class="btn-primary text-xs lg:text-sm">
            Buka Dashboard Utama &rarr;
        </a>
    </x-page-header>

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-5 xs-single-col">
        <a href="{{ route('dashboard') }}" class="card flex flex-col justify-between space-y-3">
            <div class="space-y-2">
                <span class="badge badge-primary">Ringkasan Utama</span>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Dashboard Keuangan</h2>
                <p class="text-xs md:text-sm lg:text-base text-[#7E869E]">Pantau total saldo keseluruhan, arus kas 6 bulan terakhir, realisasi anggaran bulanan, dan transaksi terbaru.</p>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-[#5D56F3]">Buka Dashboard &rarr;</span>
        </a>

        <a href="{{ route('transactions.index') }}" class="card flex flex-col justify-between space-y-3">
            <div class="space-y-2">
                <span class="badge badge-green">Pencatatan Harian</span>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Daftar & Filter Transaksi</h2>
                <p class="text-xs md:text-sm lg:text-base text-[#7E869E]">Kelola pemasukan dan pengeluaran lengkap dengan filter tanggal, kategori, rekening, serta lampiran bukti pembayaran.</p>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-[#5D56F3]">Kelola Transaksi &rarr;</span>
        </a>

        <a href="{{ route('transactions.create') }}" class="card flex flex-col justify-between space-y-3">
            <div class="space-y-2">
                <span class="badge badge-green">Input Cepat</span>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Catat Transaksi Baru</h2>
                <p class="text-xs md:text-sm lg:text-base text-[#7E869E]">Tambahkan transaksi pemasukan atau pengeluaran dengan pembaruan saldo rekening otomatis secara real-time.</p>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-[#5D56F3]">+ Tambah Transaksi &rarr;</span>
        </a>

        <a href="{{ route('accounts.index') }}" class="card flex flex-col justify-between space-y-3">
            <div class="space-y-2">
                <span class="badge badge-primary">Sumber Dana</span>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Rekening & Dompet</h2>
                <p class="text-xs md:text-sm lg:text-base text-[#7E869E]">Kelola rekening bank, dompet digital (e-wallet), dan kas tunai beserta rincian mutasi dana masuk dan keluar.</p>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-[#5D56F3]">Kelola Rekening &rarr;</span>
        </a>

        <a href="{{ route('categories.index') }}" class="card flex flex-col justify-between space-y-3">
            <div class="space-y-2">
                <span class="badge badge-orange">Master Data</span>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Kategori Transaksi</h2>
                <p class="text-xs md:text-sm lg:text-base text-[#7E869E]">Atur kategori pemasukan dan pengeluaran pribadi Anda untuk pengelompokan laporan yang terstruktur.</p>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-[#5D56F3]">Kelola Kategori &rarr;</span>
        </a>

        <a href="{{ route('reports.index') }}" class="card flex flex-col justify-between space-y-3">
            <div class="space-y-2">
                <span class="badge badge-pink">Analitik & Ekspor</span>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Laporan Periodik</h2>
                <p class="text-xs md:text-sm lg:text-base text-[#7E869E]">Evaluasi rekapitulasi keuangan bulanan, tahunan, atau rentang tanggal khusus serta unduh laporan CSV.</p>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-[#5D56F3]">Lihat Laporan &rarr;</span>
        </a>
    </div>
</x-layouts.app>
