<x-layouts.app title="Dashboard">
    @php
        $userName = trim((string) (auth()->user()?->name ?? 'Pengguna'));
        $firstName = explode(' ', $userName)[0] ?: 'Pengguna';
        $savingsRatio = $monthlyIncome > 0
            ? max(0, min(100, round((($monthlyIncome - $monthlyExpense) / $monthlyIncome) * 100)))
            : 0;
        $budgetPercentRaw = ($currentBudget && $currentBudget->amount > 0)
            ? round(($monthlyExpense / $currentBudget->amount) * 100, 1)
            : 0;
        $budgetPercentClamped = min(100, max(0, $budgetPercentRaw));
        $maxChartVal = collect($cashflowChart)->flatMap(fn ($item) => [$item['income'], $item['expense']])->max() ?: 1;
        $maxRecentTx = $recentTransactions->max('amount') ?: 1;

        $weekStart = now()->startOfWeek(\Illuminate\Support\Carbon::MONDAY);
        $weekDays = collect(range(0, 6))->map(function (int $offset) use ($weekStart) {
            $d = $weekStart->copy()->addDays($offset);
            $dayLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

            return [
                'label' => $dayLabels[$offset],
                'day' => $d->format('d'),
                'isToday' => $d->isToday(),
            ];
        });

        $initials = collect(explode(' ', $userName))
            ->filter()
            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('') ?: 'DC';
    @endphp

    {{-- Peringatan Anggaran --}}
    @if ($currentBudget)
        @php
            $budgetLimit = $currentBudget->amount;
        @endphp

        @if ($monthlyExpense > $budgetLimit)
            <div class="bento-card-dark flex flex-col sm:flex-row sm:items-center justify-between gap-4" role="alert">
                <div class="flex items-start gap-3.5">
                    <div class="bento-squircle bg-[#FCEAE8] text-[#C23B3B] shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <span class="badge badge-yellow mb-1.5">Peringatan Anggaran</span>
                        <h2 class="text-[16px] lg:text-[18px] font-bold text-white leading-snug">Peringatan Anggaran Terlampaui!</h2>
                        <p class="text-[12px] lg:text-[13px] text-white/75 mt-1 leading-relaxed">
                            Pengeluaran bulan {{ $currentMonthLabel }} (<strong class="text-white">Rp{{ number_format($monthlyExpense, 0, ',', '.') }}</strong>) telah melewati batas anggaran (<strong class="text-[#DDF296]">{{ $currentBudget->formatted_amount }}</strong>) sebesar <strong class="text-[#F7C948]">Rp{{ number_format($monthlyExpense - $budgetLimit, 0, ',', '.') }}</strong> ({{ $budgetPercentRaw }}%).
                        </p>
                    </div>
                </div>
                <a href="{{ route('reports.index') }}" class="btn-lime !py-2.5 !px-5 shrink-0">
                    Evaluasi Sekarang
                </a>
            </div>
        @elseif ($budgetPercentRaw >= 80)
            <div class="bento-card flex flex-col sm:flex-row sm:items-center justify-between gap-3" role="alert">
                <div class="flex items-center gap-3">
                    <span class="bento-squircle bg-[#FDF3D7] text-[#D99B26] shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    <p class="text-[12.5px] lg:text-[13.5px] text-[#18191B] font-medium">
                        <strong>Perhatian:</strong> Penggunaan anggaran bulan ini telah mencapai <strong class="text-[#D99B26]">{{ $budgetPercentRaw }}%</strong> dari batas <strong>{{ $currentBudget->formatted_amount }}</strong>.
                    </p>
                </div>
                <a href="{{ route('reports.index') }}" class="badge badge-yellow shrink-0">Lihat Detail &rarr;</a>
            </div>
        @endif
    @endif

    {{-- B. Baris Hero Bento (Grid 12 Kolom: 5 Sapaan + 7 Empat Kartu Ilustrasi) --}}
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
        {{-- Kolom Kiri (5 Kolom): Sapaan, Ringkasan Status & Tombol Tambah --}}
        <div class="lg:col-span-5 bento-card flex flex-col justify-between gap-4">
            <div class="space-y-2.5">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="badge badge-lime">Periode {{ $currentMonthLabel }}</span>
                    <span class="badge {{ $netCashflow >= 0 ? 'badge-blue' : 'badge-coral' }}">
                        {{ $netCashflow >= 0 ? 'Arus Kas Surplus' : 'Arus Kas Defisit' }}
                    </span>
                </div>

                <h1 class="bento-h1">
                    Halo, <span class="bento-h1-accent">{{ $firstName }}</span>!
                </h1>

                <p class="bento-meta leading-relaxed max-w-md">
                    Pantau saldo rekening, pemasukan, pengeluaran harian, dan kendali anggaran bulanan Anda dalam satu ruang kerja rapi.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 pt-1">
                <a href="{{ route('transactions.create') }}" class="btn-bento-dark">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 5v14m-7-7h14"/>
                    </svg>
                    <span>Tambah Transaksi</span>
                </a>
                <a href="{{ route('reports.index') }}" class="btn-secondary">
                    Laporan Periodik
                </a>
            </div>
        </div>

        {{-- Kolom Kanan (7 Kolom): 4 Kartu Bento Ilustratif Horizontal --}}
        <div class="lg:col-span-7 grid grid-cols-2 sm:grid-cols-4 gap-3.5 xs-single-col">
            {{-- 1. Total Saldo --}}
            <a href="{{ route('accounts.index') }}" class="bento-card bento-card-hover !p-4 flex flex-col justify-between gap-3">
                <div class="h-24 rounded-[14px] bg-[#F9F9F8] flex items-center justify-center p-2">
                    <svg class="w-16 h-16" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <rect x="8" y="16" width="44" height="32" rx="10" fill="#1E1F21"/>
                        <rect x="34" y="27" width="22" height="12" rx="6" fill="#DDF296"/>
                        <circle cx="42" cy="33" r="2.5" fill="#1E1F21"/>
                        <circle cx="20" cy="14" r="6" fill="#F7C948"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="bento-label truncate">Total Saldo</p>
                    <p class="text-[15px] lg:text-[16px] font-bold text-[#18191B] truncate mt-0.5">Rp{{ number_format($totalBalance, 0, ',', '.') }}</p>
                    <p class="bento-meta truncate">{{ $accounts->count() }} rekening</p>
                </div>
            </a>

            {{-- 2. Pemasukan Bulan Ini --}}
            <a href="{{ route('transactions.index', ['type' => 'income']) }}" class="bento-card bento-card-hover !p-4 flex flex-col justify-between gap-3">
                <div class="h-24 rounded-[14px] bg-[#ECF8C2]/55 flex items-center justify-center p-2">
                    <svg class="w-16 h-16" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <rect x="12" y="34" width="10" height="16" rx="5" fill="#9AB847"/>
                        <rect x="27" y="24" width="10" height="26" rx="5" fill="#1E1F21"/>
                        <rect x="42" y="14" width="10" height="36" rx="5" fill="#DDF296" stroke="#9AB847" stroke-width="1.5"/>
                        <path d="M16 24l14-10 12 6" stroke="#1E1F21" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="bento-label truncate">Pemasukan</p>
                    <p class="text-[15px] lg:text-[16px] font-bold text-[#18191B] truncate mt-0.5">Rp{{ number_format($monthlyIncome, 0, ',', '.') }}</p>
                    <p class="bento-meta truncate">Bulan ini</p>
                </div>
            </a>

            {{-- 3. Pengeluaran Bulan Ini --}}
            <a href="{{ route('transactions.index', ['type' => 'expense']) }}" class="bento-card bento-card-hover !p-4 flex flex-col justify-between gap-3">
                <div class="h-24 rounded-[14px] bg-[#FDF3D7]/65 flex items-center justify-center p-2">
                    <svg class="w-16 h-16" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <rect x="14" y="12" width="36" height="40" rx="10" fill="#FFFFFF" stroke="#ECECE9" stroke-width="1.5"/>
                        <rect x="22" y="22" width="20" height="5" rx="2.5" fill="#F7C948"/>
                        <rect x="22" y="31" width="14" height="5" rx="2.5" fill="#1E1F21"/>
                        <circle cx="44" cy="44" r="9" fill="#FCEAE8"/>
                        <path d="M40 44h8" stroke="#C23B3B" stroke-width="2.2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="bento-label truncate">Pengeluaran</p>
                    <p class="text-[15px] lg:text-[16px] font-bold text-[#C23B3B] truncate mt-0.5">Rp{{ number_format($monthlyExpense, 0, ',', '.') }}</p>
                    <p class="bento-meta truncate">Bulan ini</p>
                </div>
            </a>

            {{-- 4. Selisih Arus Kas --}}
            <a href="{{ route('reports.index') }}" class="bento-card bento-card-hover !p-4 flex flex-col justify-between gap-3">
                <div class="h-24 rounded-[14px] bg-[#E1EFFA]/65 flex items-center justify-center p-2">
                    <svg class="w-16 h-16" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <circle cx="32" cy="32" r="18" stroke="#FFFFFF" stroke-width="7"/>
                        <circle cx="32" cy="32" r="18" stroke="#DDF296" stroke-width="7" stroke-dasharray="78 114" stroke-linecap="round"/>
                        <circle cx="32" cy="32" r="7" fill="#1E1F21"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="bento-label truncate">Arus Bersih</p>
                    <p class="text-[15px] lg:text-[16px] font-bold {{ $netCashflow >= 0 ? 'text-[#4B6411]' : 'text-[#C23B3B]' }} truncate mt-0.5">
                        {{ $netCashflow >= 0 ? '+' : '-' }}Rp{{ number_format(abs($netCashflow), 0, ',', '.') }}
                    </p>
                    <p class="bento-meta truncate">{{ $netCashflow >= 0 ? 'Surplus' : 'Defisit' }}</p>
                </div>
            </a>
        </div>
    </section>

    {{-- C. Area Konten Utama Dual Column (~65% Kiri / ~35% Kanan) --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-5 items-start">
        {{-- Kolom Kiri (~65% -> 8 kolom) --}}
        <div class="xl:col-span-8 space-y-5 min-w-0">
            {{-- 1. Tiga Kartu Statistik Progres Melingkar (css.md 5.B.2) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                <x-stat-card
                    title="Rasio Simpanan"
                    :value="$savingsRatio . '%'"
                    :subtitle="$monthlyIncome > 0 ? 'Dari total pemasukan' : 'Belum ada pemasukan'"
                    variant="lime"
                    :progress="$savingsRatio"
                    :actionUrl="route('transactions.index')"
                    actionText="Riwayat"
                />

                <x-stat-card
                    title="Serapan Anggaran"
                    :value="$currentBudget ? $budgetPercentRaw . '%' : 'Belum Diatur'"
                    :subtitle="$currentBudget ? 'Batas ' . $currentBudget->formatted_amount : 'Atur batas bulan ini'"
                    variant="yellow"
                    :progress="$currentBudget ? $budgetPercentClamped : 0"
                    :actionUrl="route('reports.index')"
                    actionText="Evaluasi"
                />

                <x-stat-card
                    title="Kategori & Dompet"
                    :value="$accounts->count() . ' / ' . $categoriesCount"
                    subtitle="Rekening aktif / Kategori"
                    variant="default"
                    :progress="$accounts->count() > 0 ? min(100, $accounts->count() * 25) : 0"
                    :actionUrl="route('categories.index')"
                    actionText="Atur"
                />
            </div>

            {{-- 2. Dompet & Rekening (Bento Grid 2 Kolom: Featured Dark + Standard + Dashed Zone) --}}
            <section class="bento-card space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h2 class="bento-h2">Dompet & Rekening</h2>
                        <p class="bento-meta">Distribusi saldo di setiap akun penyimpanan dana</p>
                    </div>
                    <a href="{{ route('accounts.index') }}" class="btn-lime">
                        Kelola Rekening &rarr;
                    </a>
                </div>

                @if ($accounts->isEmpty())
                    <x-empty-state
                        title="Belum ada rekening."
                        description="Tambahkan rekening bank, dompet digital, atau kas tunai untuk mulai mencatat mutasi saldo."
                        :actionUrl="route('accounts.create')"
                        actionText="+ Tambah Rekening"
                    />
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        @foreach ($accounts as $index => $account)
                            @php
                                $share = $totalBalance > 0 ? round(($account->balance / $totalBalance) * 100, 1) : 0;
                            @endphp

                            @if ($index === 0)
                                {{-- Kartu Unggulan Gelap (css.md 5.B.3.A) --}}
                                <a href="{{ route('accounts.show', $account) }}" class="bento-card-dark flex flex-col justify-between gap-4 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="bento-label text-white/60">Rekening Utama</p>
                                            <h3 class="text-[16px] font-bold text-white truncate mt-0.5">{{ $account->name }}</h3>
                                        </div>
                                        <span class="badge badge-yellow shrink-0">{{ $share }}%</span>
                                    </div>

                                    <p class="text-[22px] lg:text-[26px] font-bold tracking-tight text-white truncate">
                                        {{ $account->formatted_balance }}
                                    </p>

                                    <div class="flex items-center justify-between gap-2 pt-1">
                                        <span class="badge !bg-[#ECF8C2] !text-[#18191B]">
                                            {{ $account->transactions_count }} Transaksi
                                        </span>
                                        <span class="w-7 h-7 rounded-full bg-[#DDF296] text-[#18191B] text-[11px] font-bold flex items-center justify-center shrink-0">
                                            {{ $initials }}
                                        </span>
                                    </div>
                                </a>
                            @else
                                {{-- Kartu Standar Subtle (css.md 5.B.3.B) --}}
                                <a href="{{ route('accounts.show', $account) }}" class="bento-card-subtle flex flex-col justify-between gap-4 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="bento-label">Sumber Dana</p>
                                            <h3 class="bento-h3 truncate mt-0.5">{{ $account->name }}</h3>
                                        </div>
                                        <span class="badge badge-yellow shrink-0">{{ $share }}%</span>
                                    </div>

                                    <p class="text-[20px] lg:text-[22px] font-bold tracking-tight text-[#18191B] truncate">
                                        {{ $account->formatted_balance }}
                                    </p>

                                    <div class="flex items-center justify-between gap-2 pt-1">
                                        <span class="badge badge-blue">
                                            {{ $account->transactions_count }} Transaksi
                                        </span>
                                        <span class="bento-meta font-semibold text-[#18191B]">Detail &rarr;</span>
                                    </div>
                                </a>
                            @endif
                        @endforeach

                        {{-- Area Aksi Tambah Dashed (css.md 5.B.4) --}}
                        <a href="{{ route('accounts.create') }}" class="bento-dashed-zone min-h-[152px] gap-2">
                            <span class="bento-plus-circle">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 5v14m-7-7h14"/>
                                </svg>
                            </span>
                            <span class="bento-h3">Tambah Rekening</span>
                            <span class="bento-meta">Bank, E-Wallet, atau Tunai</span>
                        </a>
                    </div>
                @endif
            </section>

            {{-- 3. Transaksi Terbaru (Baris Daftar Assignment Style - css.md 5.C.1) --}}
            <section class="bento-card space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h2 class="bento-h2">Transaksi Terbaru</h2>
                        <p class="bento-meta">Catatan mutasi pemasukan dan pengeluaran terkini</p>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="btn-secondary !py-1.5 !px-3.5 text-xs">
                        Lihat Semua &rarr;
                    </a>
                </div>

                @if ($recentTransactions->isEmpty())
                    <x-empty-state
                        title="Belum ada transaksi."
                        description="Mulai catat transaksi pertama Anda untuk melihat riwayat arus kas."
                        :actionUrl="route('transactions.create')"
                        actionText="+ Catat Transaksi"
                    />
                @else
                    <div class="space-y-1">
                        @foreach ($recentTransactions as $tx)
                            @php
                                $ratioPct = $maxRecentTx > 0 ? max(12, min(100, round(($tx->amount / $maxRecentTx) * 100))) : 25;
                            @endphp
                            <a href="{{ route('transactions.show', $tx) }}" class="bento-list-row group">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <span class="bento-squircle {{ $tx->type === 'income' ? 'bg-[#ECF8C2] text-[#4B6411]' : 'bg-[#FCEAE8] text-[#C23B3B]' }}">
                                        @if ($tx->type === 'income')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                            </svg>
                                        @endif
                                    </span>
                                    <div class="min-w-0">
                                        <p class="bento-h3 truncate group-hover:underline">{{ $tx->category?->name ?? '-' }}</p>
                                        <p class="bento-meta truncate">
                                            {{ $tx->account?->name ?? '-' }}{{ $tx->description ? ' • ' . $tx->description : '' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="hidden md:flex items-center gap-3 shrink-0">
                                    <span class="badge badge-blue">{{ $tx->account?->name ?? '-' }}</span>
                                    <div class="bento-progress-track-sm" title="Proporsi nominal terhadap transaksi terbesar">
                                        <div class="{{ $tx->type === 'income' ? 'bento-progress-fill-lime' : 'bento-progress-fill-yellow' }}" style="width: {{ $ratioPct }}%"></div>
                                    </div>
                                    @if ($tx->proof_image)
                                        <span class="w-6 h-6 rounded-full bg-[#EFEFED] text-[#8E9299] inline-flex items-center justify-center" title="Memiliki bukti transaksi">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                            </svg>
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2.5 shrink-0 text-right">
                                    <span class="text-[13px] lg:text-[14px] font-bold {{ $tx->type === 'income' ? 'text-[#4B6411]' : 'text-[#C23B3B]' }}">
                                        {{ $tx->type === 'income' ? '+' : '-' }}{{ $tx->formatted_amount }}
                                    </span>
                                    <span class="badge badge-neutral hidden sm:inline-flex">{{ $tx->date->format('d M') }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>

        {{-- Kolom Kanan (~35% -> 4 kolom) --}}
        <div class="xl:col-span-4 space-y-5 min-w-0">
            {{-- 1. Kalender Mingguan (Weekly Calendar Strip - css.md 5.C.2) --}}
            <section class="bento-card space-y-3.5">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="bento-h2">Kalender Mingguan</h2>
                    <span class="badge badge-yellow">{{ $currentMonthLabel }}</span>
                </div>

                <div class="grid grid-cols-7 gap-1 text-center">
                    @foreach ($weekDays as $wd)
                        <div class="flex flex-col items-center gap-1.5 py-1.5 rounded-[14px] {{ $wd['isToday'] ? 'bg-[#F9F9F8]' : '' }}">
                            <span class="bento-meta text-[10.5px]">{{ $wd['label'] }}</span>
                            <span class="w-8 h-8 rounded-full flex items-center justify-center text-[12px] {{ $wd['isToday'] ? 'bg-[#F7C948] text-[#18191B] font-bold' : 'text-[#18191B] font-medium' }}">
                                {{ $wd['day'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- 2. Anggaran Pengeluaran Bulanan --}}
            <section class="bento-card space-y-4">
                <div>
                    <h2 class="bento-h2">Anggaran Bulanan</h2>
                    <p class="bento-meta">Kontrol batas belanja {{ $currentMonthLabel }}</p>
                </div>

                @if ($currentBudget)
                    @php
                        $fillClass = $budgetPercentRaw > 100
                            ? 'bento-progress-fill-coral'
                            : ($budgetPercentRaw >= 80 ? 'bento-progress-fill-yellow' : 'bento-progress-fill-lime');
                    @endphp
                    <div class="rounded-[18px] bg-[#F9F9F8] p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="bento-meta">Realisasi Belanja</span>
                            <span class="badge {{ $budgetPercentRaw > 100 ? 'badge-coral' : ($budgetPercentRaw >= 80 ? 'badge-yellow' : 'badge-lime') }}">
                                {{ $budgetPercentRaw }}%
                            </span>
                        </div>

                        <div class="bento-progress-track">
                            <div class="{{ $fillClass }}" style="width: {{ $budgetPercentClamped }}%"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <div>
                                <span class="bento-meta block">Terpakai</span>
                                <span class="text-[13px] font-bold text-[#18191B]">Rp{{ number_format($monthlyExpense, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="bento-meta block">Batas Maksimal</span>
                                <span class="text-[13px] font-bold text-[#18191B]">{{ $currentBudget->formatted_amount }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bento-dashed-zone py-4 px-3">
                        <p class="bento-meta">Belum ada batas anggaran untuk bulan {{ $currentMonthLabel }}.</p>
                    </div>
                @endif

                <form action="{{ route('budgets.store') }}" method="POST" class="space-y-2.5">
                    @csrf
                    <input type="hidden" name="month" value="{{ $currentMonthKey }}">
                    <label for="budget_amount" class="block text-[12px] font-semibold text-[#18191B]">
                        {{ $currentBudget ? 'Perbarui Batas Anggaran (Rp)' : 'Tetapkan Batas Anggaran (Rp)' }}
                    </label>
                    <div class="flex items-center gap-2">
                        <input
                            type="number"
                            id="budget_amount"
                            name="amount"
                            min="1"
                            step="1"
                            required
                            value="{{ old('amount', $currentBudget?->amount) }}"
                            placeholder="5000000"
                            class="input-field flex-1"
                        >
                        <button type="submit" class="btn-bento-dark shrink-0">
                            Simpan
                        </button>
                    </div>
                </form>
            </section>

            {{-- 3. Grafik Arus Kas 6 Bulan & Pengeluaran Terbesar (Timeline Style - css.md 5.C.3) --}}
            <section class="bento-card space-y-4">
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <h2 class="bento-h2">Arus Kas 6 Bulan</h2>
                        <p class="bento-meta">Pemasukan vs Pengeluaran</p>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] font-semibold">
                        <span class="inline-flex items-center gap-1 text-[#4B6411]">
                            <span class="w-2 h-2 rounded-full bg-[#9AB847]"></span> Masuk
                        </span>
                        <span class="inline-flex items-center gap-1 text-[#18191B]">
                            <span class="w-2 h-2 rounded-full bg-[#1E1F21]"></span> Keluar
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-6 gap-2 items-end h-32 pt-3 px-1">
                    @foreach ($cashflowChart as $point)
                        @php
                            $incHeight = max(8, round(($point['income'] / $maxChartVal) * 96));
                            $expHeight = max(8, round(($point['expense'] / $maxChartVal) * 96));
                        @endphp
                        <div class="flex flex-col items-center justify-end h-full gap-1.5">
                            <div class="w-full flex items-end justify-center gap-1">
                                <div
                                    class="w-2.5 sm:w-3 rounded-full bg-[#DDF296] transition-all"
                                    style="height: {{ $point['income'] > 0 ? $incHeight : 8 }}px"
                                    title="Pemasukan {{ $point['label'] }}: Rp{{ number_format($point['income'], 0, ',', '.') }}"
                                ></div>
                                <div
                                    class="w-2.5 sm:w-3 rounded-full bg-[#1E1F21] transition-all"
                                    style="height: {{ $point['expense'] > 0 ? $expHeight : 8 }}px"
                                    title="Pengeluaran {{ $point['label'] }}: Rp{{ number_format($point['expense'], 0, ',', '.') }}"
                                ></div>
                            </div>
                            <span class="text-[10px] font-medium text-[#8E9299] truncate max-w-full">{{ \Illuminate\Support\Str::before($point['label'], ' ') }}</span>
                        </div>
                    @endforeach
                </div>

                @if ($topExpenseCategories->isNotEmpty())
                    <div class="pt-3 border-t border-dashed border-[#D5D7DC] space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="bento-h3">Pos Pengeluaran Terbesar</h3>
                            <a href="{{ route('reports.index') }}" class="bento-meta hover:text-[#18191B] font-semibold">Detail &rarr;</a>
                        </div>
                        <div class="space-y-3">
                            @foreach ($topExpenseCategories as $item)
                                <div class="bento-timeline-item flex items-center justify-between gap-2.5">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <span class="w-2.5 h-2.5 rounded-full bg-[#F7C948] shrink-0"></span>
                                        <div class="min-w-0">
                                            <p class="text-[13px] font-semibold text-[#18191B] truncate">{{ $item->category?->name ?? '-' }}</p>
                                            <p class="bento-meta">{{ $item->tx_count }} transaksi</p>
                                        </div>
                                    </div>
                                    <span class="text-[12.5px] font-bold text-[#C23B3B] shrink-0">
                                        Rp{{ number_format((int) $item->total_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-layouts.app>
