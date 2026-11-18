<x-layouts.app title="Ringkasan Modul Aplikasi">
    <x-page-header
        title="Modul & Fitur"
        accent="DailyCash"
        subtitle="Akses cepat ke seluruh modul pengelolaan keuangan harian Anda dengan tampilan Soft Modern Bento."
    >
        <a href="{{ route('dashboard') }}" class="btn-bento-dark">
            Buka Dashboard Utama &rarr;
        </a>
    </x-page-header>

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 xs-single-col">
        <a href="{{ route('dashboard') }}" class="bento-card bento-card-hover flex flex-col justify-between gap-4">
            <div class="space-y-3">
                <div class="h-24 rounded-[16px] bg-[#F9F9F8] flex items-center justify-center">
                    <svg class="w-16 h-16" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <rect x="8" y="10" width="22" height="20" rx="6" fill="#1E1F21"/>
                        <rect x="34" y="10" width="22" height="12" rx="6" fill="#DDF296"/>
                        <rect x="34" y="26" width="22" height="28" rx="6" fill="#E1EFFA"/>
                        <rect x="8" y="34" width="22" height="20" rx="6" fill="#F7C948"/>
                    </svg>
                </div>
                <span class="badge badge-lime">Ringkasan Utama</span>
                <h2 class="bento-h2">Dashboard Keuangan</h2>
                <p class="bento-meta leading-relaxed">Pantau total saldo keseluruhan, arus kas 6 bulan terakhir, realisasi anggaran bulanan, dan transaksi terbaru.</p>
            </div>
            <span class="btn-lime w-fit">Buka Dashboard &rarr;</span>
        </a>

        <a href="{{ route('transactions.index') }}" class="bento-card bento-card-hover flex flex-col justify-between gap-4">
            <div class="space-y-3">
                <div class="h-24 rounded-[16px] bg-[#ECF8C2]/55 flex items-center justify-center">
                    <svg class="w-16 h-16" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <rect x="14" y="12" width="36" height="40" rx="9" fill="#FFFFFF"/>
                        <rect x="21" y="21" width="22" height="5" rx="2.5" fill="#1E1F21"/>
                        <rect x="21" y="30" width="16" height="5" rx="2.5" fill="#9AB847"/>
                        <rect x="21" y="39" width="20" height="5" rx="2.5" fill="#F7C948"/>
                    </svg>
                </div>
                <span class="badge badge-lime">Pencatatan Harian</span>
                <h2 class="bento-h2">Daftar & Filter Transaksi</h2>
                <p class="bento-meta leading-relaxed">Kelola pemasukan dan pengeluaran lengkap dengan filter tanggal, kategori, rekening, serta bukti pembayaran.</p>
            </div>
            <span class="btn-lime w-fit">Kelola Transaksi &rarr;</span>
        </a>

        <a href="{{ route('transactions.create') }}" class="bento-card bento-card-hover flex flex-col justify-between gap-4">
            <div class="space-y-3">
                <div class="h-24 rounded-[16px] bg-[#FDF3D7]/65 flex items-center justify-center">
                    <svg class="w-16 h-16" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <circle cx="32" cy="32" r="20" fill="#1E1F21"/>
                        <path d="M32 22v20M22 32h20" stroke="#DDF296" stroke-width="4" stroke-linecap="round"/>
                    </svg>
                </div>
                <span class="badge badge-yellow">Input Cepat</span>
                <h2 class="bento-h2">Catat Transaksi Baru</h2>
                <p class="bento-meta leading-relaxed">Tambahkan transaksi pemasukan atau pengeluaran dengan pembaruan saldo rekening otomatis secara real-time.</p>
            </div>
            <span class="btn-lime w-fit">+ Tambah Transaksi &rarr;</span>
        </a>

        <a href="{{ route('accounts.index') }}" class="bento-card bento-card-hover flex flex-col justify-between gap-4">
            <div class="space-y-3">
                <div class="h-24 rounded-[16px] bg-[#E1EFFA]/65 flex items-center justify-center">
                    <svg class="w-16 h-16" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <rect x="10" y="18" width="44" height="28" rx="9" fill="#1E1F21"/>
                        <rect x="34" y="27" width="20" height="10" rx="5" fill="#DDF296"/>
                        <circle cx="40" cy="32" r="2" fill="#1E1F21"/>
                    </svg>
                </div>
                <span class="badge badge-blue">Sumber Dana</span>
                <h2 class="bento-h2">Rekening & Dompet</h2>
                <p class="bento-meta leading-relaxed">Kelola rekening bank, dompet digital (e-wallet), dan kas tunai beserta rincian mutasi dana masuk dan keluar.</p>
            </div>
            <span class="btn-lime w-fit">Kelola Rekening &rarr;</span>
        </a>

        <a href="{{ route('categories.index') }}" class="bento-card bento-card-hover flex flex-col justify-between gap-4">
            <div class="space-y-3">
                <div class="h-24 rounded-[16px] bg-[#F9F9F8] flex items-center justify-center">
                    <svg class="w-16 h-16" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <rect x="12" y="16" width="18" height="14" rx="7" fill="#DDF296"/>
                        <rect x="34" y="16" width="18" height="14" rx="7" fill="#F7C948"/>
                        <rect x="12" y="34" width="24" height="14" rx="7" fill="#E1EFFA"/>
                        <rect x="40" y="34" width="12" height="14" rx="6" fill="#1E1F21"/>
                    </svg>
                </div>
                <span class="badge badge-yellow">Master Data</span>
                <h2 class="bento-h2">Kategori Transaksi</h2>
                <p class="bento-meta leading-relaxed">Atur kategori pemasukan dan pengeluaran pribadi Anda untuk pengelompokan laporan yang terstruktur.</p>
            </div>
            <span class="btn-lime w-fit">Kelola Kategori &rarr;</span>
        </a>

        <a href="{{ route('reports.index') }}" class="bento-card bento-card-hover flex flex-col justify-between gap-4">
            <div class="space-y-3">
                <div class="h-24 rounded-[16px] bg-[#FCEAE8]/55 flex items-center justify-center">
                    <svg class="w-16 h-16" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <rect x="14" y="32" width="9" height="18" rx="4.5" fill="#1E1F21"/>
                        <rect x="28" y="22" width="9" height="28" rx="4.5" fill="#F7C948"/>
                        <rect x="42" y="14" width="9" height="36" rx="4.5" fill="#DDF296"/>
                    </svg>
                </div>
                <span class="badge badge-coral">Analitik & Ekspor</span>
                <h2 class="bento-h2">Laporan Periodik</h2>
                <p class="bento-meta leading-relaxed">Evaluasi rekapitulasi keuangan bulanan, tahunan, atau rentang tanggal khusus serta unduh laporan CSV.</p>
            </div>
            <span class="btn-lime w-fit">Lihat Laporan &rarr;</span>
        </a>
    </div>
</x-layouts.app>
