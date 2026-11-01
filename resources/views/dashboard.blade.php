<x-layouts.app title="Dashboard">
    <x-page-header
        title="Dashboard Keuangan"
        subtitle="Ringkasan saldo, arus kas bulan {{ $currentMonthLabel }}, dan evaluasi anggaran."
    >
        <a href="{{ route('reports.index') }}" class="btn-secondary text-xs lg:text-sm">
            <span>Laporan Periodik</span>
        </a>
        <a href="{{ route('transactions.create') }}" class="btn-primary text-xs lg:text-sm">
            <span>+ Tambah Transaksi</span>
        </a>
    </x-page-header>

    {{-- Peringatan Anggaran (Bagian 9.3) --}}
    @if ($currentBudget)
        @php
            $budgetLimit = $currentBudget->amount;
            $budgetPercentage = $budgetLimit > 0 ? round(($monthlyExpense / $budgetLimit) * 100, 1) : 0;
        @endphp

        @if ($monthlyExpense > $budgetLimit)
            <div class="rounded-[20px] bg-[#FF6B8B] text-white p-4 lg:p-6 shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-3" role="alert">
                <div class="flex items-start gap-2.5 lg:gap-3.5">
                    <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-[12px] bg-white/20 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 lg:w-5 lg:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-sm sm:text-base lg:text-xl font-bold leading-snug">Peringatan Anggaran Terlampaui!</h2>
                        <p class="text-xs md:text-sm lg:text-base text-white/90 mt-0.5 leading-normal">
                            Total pengeluaran bulan {{ $currentMonthLabel }} (<strong>Rp{{ number_format($monthlyExpense, 0, ',', '.') }}</strong>) telah melebihi batas anggaran (<strong>{{ $currentBudget->formatted_amount }}</strong>) sebesar <strong>Rp{{ number_format($monthlyExpense - $budgetLimit, 0, ',', '.') }}</strong> ({{ $budgetPercentage }}%).
                        </p>
                    </div>
                </div>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-white text-[#FF6B8B] text-xs lg:text-sm font-bold hover:bg-white/90 shrink-0 transition">
                    Evaluasi Pengeluaran
                </a>
            </div>
        @elseif ($budgetPercentage >= 80)
            <div class="card border-l-4 !border-l-[#FFB800] flex items-center justify-between gap-2.5" role="alert">
                <div class="flex items-center gap-2 lg:gap-3">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 text-[#FFB800] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs md:text-sm lg:text-base text-[#1A1D20]">
                        <strong>Perhatian:</strong> Penggunaan anggaran bulan ini telah mencapai <strong>{{ $budgetPercentage }}%</strong> dari batas <strong>{{ $currentBudget->formatted_amount }}</strong>.
                    </p>
                </div>
            </div>
        @endif
    @endif

    {{-- Kartu Statistik: 2 Kolom di Mobile, 4 Kolom di Desktop --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3.5 lg:gap-5 xs-single-col">
        <x-stat-card
            title="Total Saldo Keseluruhan"
            :value="'Rp' . number_format($totalBalance, 0, ',', '.')"
            :subtitle="$accounts->count() . ' rekening/dompet aktif'"
            variant="primary"
        >
            <x-slot:icon>
                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card
            title="Total Pemasukan Bulan Ini"
            :value="'Rp' . number_format($monthlyIncome, 0, ',', '.')"
            :subtitle="'Periode ' . $currentMonthLabel"
            variant="income"
        >
            <x-slot:icon>
                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card
            title="Total Pengeluaran Bulan Ini"
            :value="'Rp' . number_format($monthlyExpense, 0, ',', '.')"
            :subtitle="'Periode ' . $currentMonthLabel"
            variant="expense"
        >
            <x-slot:icon>
                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card
            title="Selisih Arus Kas"
            :value="($netCashflow >= 0 ? '+Rp' : '-Rp') . number_format(abs($netCashflow), 0, ',', '.')"
            :subtitle="$netCashflow >= 0 ? 'Surplus bulan berjalan' : 'Defisit bulan berjalan'"
            variant="default"
        >
            <x-slot:icon>
                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/>
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    {{-- Baris Grafik Arus Kas & Anggaran Bulanan --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3.5 sm:gap-4 lg:gap-6">
        {{-- Grafik Arus Kas 6 Bulan --}}
        <div class="lg:col-span-2 card flex flex-col justify-between">
            <div>
                <div class="flex flex-wrap items-center justify-between gap-2 mb-3 lg:mb-4">
                    <div>
                        <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Grafik Arus Kas (6 Bulan Terakhir)</h2>
                        <p class="text-[11px] lg:text-sm text-[#7E869E]">Perbandingan pemasukan dan pengeluaran bulanan</p>
                    </div>
                    <div class="flex items-center gap-3 text-[11px] lg:text-sm font-medium">
                        <span class="inline-flex items-center gap-1.5 text-[#2ED47A]">
                            <span class="w-2.5 h-2.5 lg:w-3 lg:h-3 rounded-full bg-[#2ED47A]"></span> Pemasukan
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[#FF6B8B]">
                            <span class="w-2.5 h-2.5 lg:w-3 lg:h-3 rounded-full bg-[#FF6B8B]"></span> Pengeluaran
                        </span>
                    </div>
                </div>

                @php
                    $maxChartVal = collect($cashflowChart)->flatMap(fn ($item) => [$item['income'], $item['expense']])->max() ?: 1;
                @endphp

                <div class="grid grid-cols-6 gap-2 lg:gap-4 items-end h-32 lg:h-40 pt-2 px-1 border-b border-[#F4F5F9]">
                    @foreach ($cashflowChart as $point)
                        @php
                            $incHeight = max(4, round(($point['income'] / $maxChartVal) * 105));
                            $expHeight = max(4, round(($point['expense'] / $maxChartVal) * 105));
                        @endphp
                        <div class="flex flex-col items-center">
                            <div class="w-full flex items-end justify-center gap-1 lg:gap-1.5 h-28 lg:h-36">
                                <div class="w-2.5 sm:w-4 lg:w-5 rounded-t-md bg-[#2ED47A] transition-all"
                                     style="height: {{ $point['income'] > 0 ? $incHeight : 4 }}px"
                                     title="Pemasukan {{ $point['label'] }}: Rp{{ number_format($point['income'], 0, ',', '.') }}"></div>
                                <div class="w-2.5 sm:w-4 lg:w-5 rounded-t-md bg-[#FF6B8B] transition-all"
                                     style="height: {{ $point['expense'] > 0 ? $expHeight : 4 }}px"
                                     title="Pengeluaran {{ $point['label'] }}: Rp{{ number_format($point['expense'], 0, ',', '.') }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="grid grid-cols-6 gap-1 pt-1.5 lg:pt-2 text-center">
                    @foreach ($cashflowChart as $point)
                        <span class="text-[11px] lg:text-sm font-medium text-[#7E869E] truncate">{{ $point['label'] }}</span>
                    @endforeach
                </div>
            </div>

            <div class="mt-3 lg:mt-4 pt-2.5 lg:pt-3.5 border-t border-[#F4F5F9] flex flex-wrap items-center justify-between gap-2 text-xs md:text-sm lg:text-base">
                <div>
                    <span class="text-[#7E869E]">Selisih Bulan Ini:</span>
                    <span class="font-bold ml-1 {{ $netCashflow >= 0 ? 'text-[#2ED47A]' : 'text-[#FF6B8B]' }}">
                        {{ $netCashflow >= 0 ? '+' : '-' }}Rp{{ number_format(abs($netCashflow), 0, ',', '.') }}
                    </span>
                </div>
                <a href="{{ route('reports.index', ['period_type' => 'monthly', 'month' => $currentMonthKey]) }}" class="text-xs lg:text-sm font-semibold text-[#5D56F3] hover:text-[#4B45D3] underline underline-offset-4">
                    Laporan Bulan Ini &rarr;
                </a>
            </div>
        </div>

        {{-- Anggaran Bulanan (Bagian 9.2) --}}
        <div class="card flex flex-col justify-between space-y-3 lg:space-y-4">
            <div>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Anggaran Pengeluaran</h2>
                <p class="text-[11px] lg:text-sm text-[#7E869E]">Kontrol batas belanja {{ $currentMonthLabel }}</p>

                @if ($currentBudget)
                    @php
                        $pct = $currentBudget->amount > 0 ? min(100, round(($monthlyExpense / $currentBudget->amount) * 100)) : 0;
                        $rawPct = $currentBudget->amount > 0 ? round(($monthlyExpense / $currentBudget->amount) * 100, 1) : 0;
                        $barColor = $rawPct > 100 ? 'progress-bar-pink' : ($rawPct >= 80 ? 'progress-bar-orange' : '');
                    @endphp
                    <div class="mt-3 lg:mt-4 p-3 lg:p-4 rounded-[12px] bg-[#F4F5F9] space-y-2 lg:space-y-2.5">
                        <div class="flex items-center justify-between text-[11px] lg:text-sm">
                            <span class="text-[#7E869E] font-medium">Realisasi vs Anggaran</span>
                            <span class="font-bold {{ $rawPct > 100 ? 'text-[#FF6B8B]' : 'text-[#5D56F3]' }}">{{ $rawPct }}%</span>
                        </div>
                        <div class="progress-container !bg-white">
                            <div class="progress-bar {{ $barColor }}" style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-[11px] lg:text-sm pt-0.5">
                            <div>
                                <span class="text-[#A3AABF] block">Terpakai</span>
                                <span class="font-semibold text-[#1A1D20]">Rp{{ number_format($monthlyExpense, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[#A3AABF] block">Batas Anggaran</span>
                                <span class="font-semibold text-[#1A1D20]">{{ $currentBudget->formatted_amount }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-3 lg:mt-4 p-3 lg:p-4 rounded-[12px] bg-[#F4F5F9] border border-dashed border-[#A3AABF]/50 text-[11px] lg:text-sm text-[#7E869E]">
                        Belum ada batas anggaran untuk bulan {{ $currentMonthLabel }}.
                    </div>
                @endif
            </div>

            <form action="{{ route('budgets.store') }}" method="POST" class="space-y-2 pt-2.5 border-t border-[#F4F5F9]">
                @csrf
                <input type="hidden" name="month" value="{{ $currentMonthKey }}">
                <label for="budget_amount" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20]">
                    {{ $currentBudget ? 'Perbarui Batas Anggaran (Rp)' : 'Tetapkan Batas Anggaran (Rp)' }}
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <input
                        type="number"
                        id="budget_amount"
                        name="amount"
                        min="1"
                        step="1"
                        required
                        value="{{ old('amount', $currentBudget?->amount) }}"
                        placeholder="5000000"
                        class="input-field text-xs md:text-sm lg:text-base"
                    >
                    <button type="submit" class="btn-primary w-full text-xs lg:text-sm">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan Saldo per Rekening (2 Kolom di Mobile) & Transaksi Terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3.5 sm:gap-4 lg:gap-6">
        {{-- Saldo per Rekening/Dompet --}}
        <div class="card space-y-3 lg:space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Saldo Rekening & Dompet</h2>
                    <p class="text-[11px] lg:text-sm text-[#7E869E]">Posisi saldo masing-masing akun</p>
                </div>
                <a href="{{ route('accounts.index') }}" class="text-xs lg:text-sm font-semibold text-[#5D56F3] hover:text-[#4B45D3]">
                    Kelola &rarr;
                </a>
            </div>

            @if ($accounts->isEmpty())
                <x-empty-state
                    title="Belum ada rekening. Tambahkan rekening pertama Anda."
                    description="Buat rekening bank, dompet tunai, atau e-wallet untuk mulai menyimpan saldo."
                    :actionUrl="route('accounts.create')"
                    actionText="+ Tambah Rekening"
                />
            @else
                {{-- 2 Kolom di Mobile, 1 Kolom di Sidebar Desktop lg --}}
                <div class="grid grid-cols-2 lg:grid-cols-1 gap-2 lg:gap-2.5 xs-single-col">
                    @foreach ($accounts as $account)
                        @php
                            $share = $totalBalance > 0 ? round(($account->balance / $totalBalance) * 100, 1) : 0;
                        @endphp
                        <a href="{{ route('accounts.show', $account) }}" class="block p-3 lg:p-3.5 rounded-[12px] bg-[#F4F5F9] hover:bg-[#EEEDFE]/60 transition min-w-0">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-1">
                                <div class="min-w-0">
                                    <p class="text-xs md:text-sm lg:text-base font-semibold text-[#1A1D20] truncate">{{ $account->name }}</p>
                                    <p class="text-[11px] lg:text-sm text-[#7E869E] truncate">{{ $account->transactions_count }} tx &bull; {{ $share }}%</p>
                                </div>
                                <p class="text-xs md:text-sm lg:text-base font-bold text-[#5D56F3] shrink-0">{{ $account->formatted_balance }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if (($categoriesCount ?? 0) === 0)
                <div class="pt-3 border-t border-[#F4F5F9]">
                    <x-empty-state
                        title="Belum ada kategori. Buat kategori untuk mengelompokkan transaksi."
                        description="Tambahkan kategori pemasukan dan pengeluaran sesuai kebutuhan Anda."
                        :actionUrl="route('categories.create')"
                        actionText="+ Buat Kategori"
                    />
                </div>
            @elseif ($topExpenseCategories->isNotEmpty())
                <div class="pt-3 lg:pt-4 border-t border-[#F4F5F9] space-y-2 lg:space-y-2.5">
                    <h3 class="text-sm sm:text-base lg:text-lg font-bold text-[#1A1D20]">Pengeluaran Terbesar Bulan Ini</h3>
                    <div class="grid grid-cols-2 lg:grid-cols-1 gap-2 xs-single-col">
                        @foreach ($topExpenseCategories as $item)
                            <div class="p-2.5 lg:p-3 rounded-[12px] bg-[#F4F5F9] flex flex-col lg:flex-row lg:items-center justify-between gap-0.5 text-[11px] lg:text-sm min-w-0">
                                <span class="text-[#1A1D20] font-medium truncate">{{ $item->category?->name ?? '-' }} ({{ $item->tx_count }}x)</span>
                                <span class="font-semibold text-[#FF6B8B] shrink-0">Rp{{ number_format((int) $item->total_amount, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="pt-3 lg:pt-4 border-t border-[#F4F5F9] flex items-center justify-between gap-2 text-[11px] lg:text-sm text-[#7E869E]">
                    <span>Tersedia <strong class="text-[#1A1D20]">{{ $categoriesCount }} kategori</strong> siap digunakan.</span>
                    <a href="{{ route('categories.index') }}" class="font-semibold text-[#5D56F3] hover:text-[#4B45D3]">Lihat Kategori &rarr;</a>
                </div>
            @endif
        </div>

        {{-- Daftar Transaksi Terbaru --}}
        <div class="lg:col-span-2 card space-y-3 lg:space-y-4 min-w-0">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Transaksi Terbaru</h2>
                    <p class="text-[11px] lg:text-sm text-[#7E869E]">Catatan pemasukan dan pengeluaran terakhir</p>
                </div>
                <a href="{{ route('transactions.index') }}" class="text-xs lg:text-sm font-semibold text-[#5D56F3] hover:text-[#4B45D3]">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if ($recentTransactions->isEmpty())
                <x-empty-state
                    title="Belum ada transaksi. Mulai catat pemasukan atau pengeluaran."
                    description="Catat pemasukan atau pengeluaran pertama Anda untuk melihat histori di sini."
                    :actionUrl="route('transactions.create')"
                    actionText="+ Catat Transaksi"
                />
            @else
                <div class="overflow-x-auto -mx-3.5 sm:mx-0 px-3.5 sm:px-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#F4F5F9] text-[11px] lg:text-sm font-semibold uppercase tracking-wider text-[#A3AABF]">
                                <th class="py-2 lg:py-3 pr-2">Tanggal</th>
                                <th class="py-2 lg:py-3 px-2">Kategori & Deskripsi</th>
                                <th class="py-2 lg:py-3 px-2 hidden sm:table-cell">Rekening</th>
                                <th class="py-2 lg:py-3 px-2">Tipe</th>
                                <th class="py-2 lg:py-3 pl-2 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F4F5F9] text-xs md:text-sm lg:text-base">
                            @foreach ($recentTransactions as $tx)
                                <tr class="hover:bg-[#F4F5F9]/60 transition">
                                    <td class="py-2.5 lg:py-3 pr-2 whitespace-nowrap text-[11px] lg:text-sm text-[#7E869E]">
                                        {{ $tx->date->format('d/m/y') }}
                                    </td>
                                    <td class="py-2.5 lg:py-3 px-2 min-w-0">
                                        <a href="{{ route('transactions.show', $tx) }}" class="font-semibold text-[#1A1D20] hover:text-[#5D56F3] block truncate">
                                            {{ $tx->category?->name ?? '-' }}
                                        </a>
                                        <span class="text-[11px] lg:text-sm text-[#7E869E] block truncate">
                                            {{ $tx->account?->name }} {{ $tx->description ? '• ' . $tx->description : '' }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 lg:py-3 px-2 whitespace-nowrap text-[11px] lg:text-sm font-medium text-[#7E869E] hidden sm:table-cell">
                                        {{ $tx->account?->name ?? '-' }}
                                    </td>
                                    <td class="py-2.5 lg:py-3 px-2 whitespace-nowrap">
                                        <x-badge-type :type="$tx->type" />
                                    </td>
                                    <td class="py-2.5 lg:py-3 pl-2 whitespace-nowrap text-right font-bold {{ $tx->type === 'income' ? 'text-[#2ED47A]' : 'text-[#FF6B8B]' }}">
                                        {{ $tx->type === 'income' ? '+' : '-' }}{{ $tx->formatted_amount }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
