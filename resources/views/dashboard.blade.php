<x-layouts.app title="Dashboard">
    <x-page-header
        title="Dashboard Keuangan"
        subtitle="Ringkasan saldo, arus kas bulan {{ $currentMonthLabel }}, dan evaluasi anggaran."
    >
        <a href="{{ route('reports.index') }}" class="btn-secondary text-xs lg:text-sm shadow-sm hover:shadow transition-all">
            <span>Laporan Periodik</span>
        </a>
        <a href="{{ route('transactions.create') }}" class="btn-primary text-xs lg:text-sm shadow-md hover:shadow-lg transition-all">
            <span>+ Tambah Transaksi</span>
        </a>
    </x-page-header>

    {{-- Peringatan Anggaran --}}
    @if ($currentBudget)
        @php
            $budgetLimit = $currentBudget->amount;
            $budgetPercentage = $budgetLimit > 0 ? round(($monthlyExpense / $budgetLimit) * 100, 1) : 0;
        @endphp

        @if ($monthlyExpense > $budgetLimit)
            <div class="rounded-[20px] bg-gradient-to-r from-[#FF6B8B] to-[#FF8E53] text-white p-4 lg:p-6 shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-3 animate-pulse" role="alert">
                <div class="flex items-start gap-2.5 lg:gap-3.5">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0 shadow-inner">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-sm sm:text-base lg:text-xl font-bold tracking-tight">Peringatan Anggaran Terlampaui!</h2>
                        <p class="text-xs md:text-sm text-white/90 mt-0.5 leading-relaxed">
                            Pengeluaran bulan {{ $currentMonthLabel }} (<strong>Rp{{ number_format($monthlyExpense, 0, ',', '.') }}</strong>) telah melewati batas anggaran (<strong>{{ $currentBudget->formatted_amount }}</strong>) sebesar <strong>Rp{{ number_format($monthlyExpense - $budgetLimit, 0, ',', '.') }}</strong> ({{ $budgetPercentage }}%).
                        </p>
                    </div>
                </div>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white text-[#FF6B8B] text-xs lg:text-sm font-bold shadow-md hover:bg-gray-50 shrink-0 transition-all">
                    Evaluasi Sekarang
                </a>
            </div>
        @elseif ($budgetPercentage >= 80)
            <div class="card border-l-4 !border-l-[#FFB800] bg-amber-50/40 flex items-center justify-between gap-2.5 shadow-sm" role="alert">
                <div class="flex items-center gap-2.5 lg:gap-3.5">
                    <div class="p-2 bg-amber-100 rounded-xl text-amber-600">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-xs md:text-sm text-[#1A1D20] font-medium">
                        <strong>Perhatian:</strong> Penggunaan anggaran bulan ini sudah mencapai <strong class="text-amber-600">{{ $budgetPercentage }}%</strong> dari total batas <strong>{{ $currentBudget->formatted_amount }}</strong>.
                    </p>
                </div>
            </div>
        @endif
    @endif

    {{-- Kartu Statistik Utama (Modern FinTech Style) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-5">
        {{-- Total Saldo --}}
        <div class="card bg-gradient-to-br from-[#6366F1] to-[#4F46E5] text-white shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between p-4 lg:p-5">
            <div class="flex items-center justify-between">
                <span class="text-[11px] lg:text-xs font-semibold uppercase tracking-wider text-white/80">Total Saldo</span>
                <div class="p-2 bg-white/15 backdrop-blur-md rounded-xl">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
            </div>
            <div class="my-3">
                <p class="text-lg sm:text-xl lg:text-2xl font-black tracking-tight truncate">Rp{{ number_format($totalBalance, 0, ',', '.') }}</p>
            </div>
            <div class="text-[11px] lg:text-xs text-white/80 font-medium">
                {{ $accounts->count() }} rekening/dompet aktif
            </div>
        </div>

        {{-- Total Pemasukan --}}
        <div class="card bg-white border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between p-4 lg:p-5">
            <div class="flex items-center justify-between">
                <span class="text-[11px] lg:text-xs font-semibold uppercase tracking-wider text-gray-400">Pemasukan Bulan Ini</span>
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                </div>
            </div>
            <div class="my-3">
                <p class="text-lg sm:text-xl lg:text-2xl font-black tracking-tight text-emerald-600 truncate">Rp{{ number_format($monthlyIncome, 0, ',', '.') }}</p>
            </div>
            <div class="text-[11px] lg:text-xs text-gray-400 font-medium">
                Periode {{ $currentMonthLabel }}
            </div>
        </div>

        {{-- Total Pengeluaran --}}
        <div class="card bg-white border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between p-4 lg:p-5">
            <div class="flex items-center justify-between">
                <span class="text-[11px] lg:text-xs font-semibold uppercase tracking-wider text-gray-400">Pengeluaran Bulan Ini</span>
                <div class="p-2 bg-rose-50 text-rose-600 rounded-xl">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                </div>
            </div>
            <div class="my-3">
                <p class="text-lg sm:text-xl lg:text-2xl font-black tracking-tight text-rose-600 truncate">Rp{{ number_format($monthlyExpense, 0, ',', '.') }}</p>
            </div>
            <div class="text-[11px] lg:text-xs text-gray-400 font-medium">
                Periode {{ $currentMonthLabel }}
            </div>
        </div>

        {{-- Selisih Arus Kas --}}
        <div class="card bg-white border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between p-4 lg:p-5">
            <div class="flex items-center justify-between">
                <span class="text-[11px] lg:text-xs font-semibold uppercase tracking-wider text-gray-400">Selisih Arus Kas</span>
                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/></svg>
                </div>
            </div>
            <div class="my-3">
                <p class="text-lg sm:text-xl lg:text-2xl font-black tracking-tight {{ $netCashflow >= 0 ? 'text-emerald-600' : 'text-rose-600' }} truncate">
                    {{ $netCashflow >= 0 ? '+' : '-' }}Rp{{ number_format(abs($netCashflow), 0, ',', '.') }}
                </p>
            </div>
            <div class="text-[11px] lg:text-xs text-gray-400 font-medium">
                {{ $netCashflow >= 0 ? 'Surplus bulan berjalan' : 'Defisit bulan berjalan' }}
            </div>
        </div>
    </div>

    {{-- Grafik Arus Kas & Anggaran Bulanan --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
        {{-- Grafik Arus Kas 6 Bulan --}}
        <div class="lg:col-span-2 card bg-white shadow-sm flex flex-col justify-between p-5 lg:p-6">
            <div>
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                    <div>
                        <h2 class="text-base sm:text-lg lg:text-xl font-bold text-[#1A1D20]">Grafik Arus Kas</h2>
                        <p class="text-xs text-gray-400">Perbandingan pemasukan & pengeluaran 6 bulan terakhir</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-semibold">
                        <span class="inline-flex items-center gap-1.5 text-emerald-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Pemasukan
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-rose-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Pengeluaran
                        </span>
                    </div>
                </div>

                @php
                    $maxChartVal = collect($cashflowChart)->flatMap(fn ($item) => [$item['income'], $item['expense']])->max() ?: 1;
                @endphp

                <div class="grid grid-cols-6 gap-2 lg:gap-4 items-end h-36 lg:h-44 pt-4 px-2 border-b border-gray-100">
                    @foreach ($cashflowChart as $point)
                        @php
                            $incHeight = max(6, round(($point['income'] / $maxChartVal) * 110));
                            $expHeight = max(6, round(($point['expense'] / $maxChartVal) * 110));
                        @endphp
                        <div class="flex flex-col items-center h-full justify-end">
                            <div class="w-full flex items-end justify-center gap-1 lg:gap-1.5">
                                <div class="w-3 sm:w-5 rounded-t-lg bg-emerald-500 hover:bg-emerald-400 transition-all duration-500 shadow-sm"
                                     style="height: {{ $point['income'] > 0 ? $incHeight : 6 }}px"
                                     title="Pemasukan {{ $point['label'] }}: Rp{{ number_format($point['income'], 0, ',', '.') }}"></div>
                                <div class="w-3 sm:w-5 rounded-t-lg bg-rose-500 hover:bg-rose-400 transition-all duration-500 shadow-sm"
                                     style="height: {{ $point['expense'] > 0 ? $expHeight : 6 }}px"
                                     title="Pengeluaran {{ $point['label'] }}: Rp{{ number_format($point['expense'], 0, ',', '.') }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="grid grid-cols-6 gap-1 pt-2 text-center">
                    @foreach ($cashflowChart as $point)
                        <span class="text-xs font-semibold text-gray-400">{{ $point['label'] }}</span>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-2 text-xs lg:text-sm">
                <div>
                    <span class="text-gray-400 font-medium">Selisih Bulan Ini:</span>
                    <span class="font-bold ml-1.5 {{ $netCashflow >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $netCashflow >= 0 ? '+' : '-' }}Rp{{ number_format(abs($netCashflow), 0, ',', '.') }}
                    </span>
                </div>
                <a href="{{ route('reports.index', ['period_type' => 'monthly', 'month' => $currentMonthKey]) }}" class="font-bold text-[#6366F1] hover:text-[#4F46E5] transition-colors">
                    Lihat Laporan Detail &rarr;
                </a>
            </div>
        </div>

        {{-- Anggaran Pengeluaran --}}
        <div class="card bg-white shadow-sm flex flex-col justify-between p-5 lg:p-6 space-y-4">
            <div>
                <h2 class="text-base sm:text-lg lg:text-xl font-bold text-[#1A1D20]">Anggaran Pengeluaran</h2>
                <p class="text-xs text-gray-400">Kontrol batas belanja {{ $currentMonthLabel }}</p>

                @if ($currentBudget)
                    @php
                        $pct = $currentBudget->amount > 0 ? min(100, round(($monthlyExpense / $currentBudget->amount) * 100)) : 0;
                        $rawPct = $currentBudget->amount > 0 ? round(($monthlyExpense / $currentBudget->amount) * 100, 1) : 0;
                        $barColor = $rawPct > 100 ? 'bg-rose-500' : ($rawPct >= 80 ? 'bg-amber-500' : 'bg-indigo-600');
                    @endphp
                    <div class="mt-4 p-4 rounded-2xl bg-gray-50 space-y-3 border border-gray-100">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-gray-500">Realisasi Anggaran</span>
                            <span class="{{ $rawPct > 100 ? 'text-rose-600' : 'text-indigo-600' }}">{{ $rawPct }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 h-3 rounded-full overflow-hidden p-0.5">
                            <div class="{{ $barColor }} h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                            <div>
                                <span class="text-gray-400 block font-medium">Terpakai</span>
                                <span class="font-bold text-[#1A1D20]">Rp{{ number_format($monthlyExpense, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-gray-400 block font-medium">Batas Maksimal</span>
                                <span class="font-bold text-[#1A1D20]">{{ $currentBudget->formatted_amount }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-4 p-4 rounded-2xl bg-gray-50 border border-dashed border-gray-300 text-xs text-gray-500 text-center">
                        Belum ada batas anggaran untuk bulan {{ $currentMonthLabel }}.
                    </div>
                @endif
            </div>

            <form action="{{ route('budgets.store') }}" method="POST" class="space-y-2.5 pt-3 border-t border-gray-100">
                @csrf
                <input type="hidden" name="month" value="{{ $currentMonthKey }}">
                <label for="budget_amount" class="block text-xs font-bold text-gray-700">
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
                        class="input-field text-xs md:text-sm focus:ring-2 focus:ring-[#6366F1]"
                    >
                    <button type="submit" class="btn-primary w-full text-xs font-bold shadow-md hover:shadow-lg transition-all">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Rekening & Transaksi Terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
        {{-- Saldo Rekening --}}
        <div class="card bg-white shadow-sm space-y-4 p-5 lg:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base sm:text-lg lg:text-xl font-bold text-[#1A1D20]">Dompet & Rekening</h2>
                    <p class="text-xs text-gray-400">Distribusi saldo akun</p>
                </div>
                <a href="{{ route('accounts.index') }}" class="text-xs font-bold text-[#6366F1] hover:text-[#4F46E5] transition-colors">
                    Kelola &rarr;
                </a>
            </div>

            @if ($accounts->isEmpty())
                <x-empty-state
                    title="Belum ada rekening."
                    description="Tambahkan rekening bank atau e-wallet."
                    :actionUrl="route('accounts.create')"
                    actionText="+ Tambah Rekening"
                />
            @else
                <div class="grid grid-cols-2 lg:grid-cols-1 gap-2.5">
                    @foreach ($accounts as $account)
                        @php
                            $share = $totalBalance > 0 ? round(($account->balance / $totalBalance) * 100, 1) : 0;
                        @endphp
                        <a href="{{ route('accounts.show', $account) }}" class="block p-3.5 rounded-2xl bg-gray-50 hover:bg-indigo-50/50 hover:border-indigo-100 border border-transparent transition-all duration-200">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-1">
                                <div class="min-w-0">
                                    <p class="text-xs md:text-sm font-bold text-[#1A1D20] truncate">{{ $account->name }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $account->transactions_count }} transaksi &bull; {{ $share }}%</p>
                                </div>
                                <p class="text-xs md:text-sm font-black text-[#6366F1] shrink-0">{{ $account->formatted_balance }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($topExpenseCategories->isNotEmpty())
                <div class="pt-4 border-t border-gray-100 space-y-2.5">
                    <h3 class="text-xs lg:text-sm font-bold text-[#1A1D20]">Pengeluaran Terbesar</h3>
                    <div class="grid grid-cols-2 lg:grid-cols-1 gap-2">
                        @foreach ($topExpenseCategories as $item)
                            <div class="p-3 rounded-xl bg-rose-50/40 border border-rose-100/50 flex flex-col lg:flex-row lg:items-center justify-between gap-0.5 text-xs">
                                <span class="text-gray-700 font-medium truncate">{{ $item->category?->name ?? '-' }} ({{ $item->tx_count }}x)</span>
                                <span class="font-bold text-rose-600 shrink-0">Rp{{ number_format((int) $item->total_amount, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Transaksi Terbaru --}}
        <div class="lg:col-span-2 card bg-white shadow-sm space-y-4 p-5 lg:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base sm:text-lg lg:text-xl font-bold text-[#1A1D20]">Transaksi Terbaru</h2>
                    <p class="text-xs text-gray-400">Catatan mutasi pemasukan & pengeluaran</p>
                </div>
                <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-[#6366F1] hover:text-[#4F46E5] transition-colors">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if ($recentTransactions->isEmpty())
                <x-empty-state
                    title="Belum ada transaksi."
                    description="Mulai catat transaksi pertama Anda."
                    :actionUrl="route('transactions.create')"
                    actionText="+ Catat Transaksi"
                />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                                <th class="py-3 pr-3">Tanggal</th>
                                <th class="py-3 px-3">Kategori & Keterangan</th>
                                <th class="py-3 px-3 hidden sm:table-cell">Rekening</th>
                                <th class="py-3 px-3">Tipe</th>
                                <th class="py-3 pl-3 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-xs md:text-sm">
                            @foreach ($recentTransactions as $tx)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="py-3 pr-3 whitespace-nowrap text-xs text-gray-400 font-medium">
                                        {{ $tx->date->format('d/m/y') }}
                                    </td>
                                    <td class="py-3 px-3 min-w-0">
                                        <a href="{{ route('transactions.show', $tx) }}" class="font-bold text-[#1A1D20] hover:text-[#6366F1] transition-colors block truncate">
                                            {{ $tx->category?->name ?? '-' }}
                                        </a>
                                        <span class="text-xs text-gray-400 block truncate">
                                            {{ $tx->account?->name }} {{ $tx->description ? '• ' . $tx->description : '' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap text-xs font-medium text-gray-500 hidden sm:table-cell">
                                        {{ $tx->account?->name ?? '-' }}
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <x-badge-type :type="$tx->type" />
                                    </td>
                                    <td class="py-3 pl-3 whitespace-nowrap text-right font-black text-sm {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
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