<x-layouts.app title="Dashboard">
    <x-page-header
        title="Dashboard Keuangan"
        subtitle="Ringkasan saldo, arus kas bulan {{ $currentMonthLabel }}, dan evaluasi anggaran."
    >
        <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-medium transition">
            <span>Laporan Periodik</span>
        </a>
        <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
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
            <div class="rounded-xl bg-rose-600 text-white p-3 sm:p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2.5" role="alert">
                <div class="flex items-start gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-sm font-bold leading-snug">Peringatan Anggaran Terlampaui!</h2>
                        <p class="text-xs text-rose-100 mt-0.5 leading-normal">
                            Total pengeluaran bulan {{ $currentMonthLabel }} (<strong>Rp{{ number_format($monthlyExpense, 0, ',', '.') }}</strong>) telah melebihi batas anggaran (<strong>{{ $currentBudget->formatted_amount }}</strong>) sebesar <strong>Rp{{ number_format($monthlyExpense - $budgetLimit, 0, ',', '.') }}</strong> ({{ $budgetPercentage }}%).
                        </p>
                    </div>
                </div>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-white text-rose-700 text-xs font-bold hover:bg-rose-50 shrink-0 transition">
                    Evaluasi Pengeluaran
                </a>
            </div>
        @elseif ($budgetPercentage >= 80)
            <div class="rounded-xl bg-amber-50 border border-amber-300 text-amber-900 p-3 flex items-center justify-between gap-2.5" role="alert">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs">
                        <strong>Perhatian:</strong> Penggunaan anggaran bulan ini telah mencapai <strong>{{ $budgetPercentage }}%</strong> dari batas <strong>{{ $currentBudget->formatted_amount }}</strong>.
                    </p>
                </div>
            </div>
        @endif
    @endif

    {{-- Kartu Statistik: 2 Kolom di Mobile, 4 Kolom di Desktop --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3.5 xs-single-col">
        <x-stat-card
            title="Total Saldo Keseluruhan"
            :value="'Rp' . number_format($totalBalance, 0, ',', '.')"
            :subtitle="$accounts->count() . ' rekening/dompet aktif'"
            variant="primary"
        >
            <x-slot:icon>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/>
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    {{-- Baris Grafik Arus Kas & Anggaran Bulanan --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3.5 sm:gap-4">
        {{-- Grafik Arus Kas 6 Bulan --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-3.5 sm:p-4 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900">Grafik Arus Kas (6 Bulan Terakhir)</h2>
                        <p class="text-[11px] text-slate-500">Perbandingan pemasukan dan pengeluaran bulanan</p>
                    </div>
                    <div class="flex items-center gap-3 text-[11px] font-medium">
                        <span class="inline-flex items-center gap-1 text-emerald-700">
                            <span class="w-2.5 h-2.5 rounded-xs bg-emerald-500"></span> Pemasukan
                        </span>
                        <span class="inline-flex items-center gap-1 text-rose-700">
                            <span class="w-2.5 h-2.5 rounded-xs bg-rose-500"></span> Pengeluaran
                        </span>
                    </div>
                </div>

                @php
                    $maxChartVal = collect($cashflowChart)->flatMap(fn ($item) => [$item['income'], $item['expense']])->max() ?: 1;
                @endphp

                <div class="grid grid-cols-6 gap-2 items-end h-32 pt-2 px-1 border-b border-slate-200">
                    @foreach ($cashflowChart as $point)
                        @php
                            $incHeight = max(4, round(($point['income'] / $maxChartVal) * 105));
                            $expHeight = max(4, round(($point['expense'] / $maxChartVal) * 105));
                        @endphp
                        <div class="flex flex-col items-center">
                            <div class="w-full flex items-end justify-center gap-1 h-28">
                                <div class="w-2.5 sm:w-4 rounded-t-xs bg-emerald-500 transition-all"
                                     style="height: {{ $point['income'] > 0 ? $incHeight : 4 }}px"
                                     title="Pemasukan {{ $point['label'] }}: Rp{{ number_format($point['income'], 0, ',', '.') }}"></div>
                                <div class="w-2.5 sm:w-4 rounded-t-xs bg-rose-500 transition-all"
                                     style="height: {{ $point['expense'] > 0 ? $expHeight : 4 }}px"
                                     title="Pengeluaran {{ $point['label'] }}: Rp{{ number_format($point['expense'], 0, ',', '.') }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="grid grid-cols-6 gap-1 pt-1.5 text-center">
                    @foreach ($cashflowChart as $point)
                        <span class="text-[11px] font-medium text-slate-500 truncate">{{ $point['label'] }}</span>
                    @endforeach
                </div>
            </div>

            <div class="mt-3 pt-2.5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
                <div>
                    <span class="text-slate-500">Selisih Bulan Ini:</span>
                    <span class="font-bold ml-1 {{ $netCashflow >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $netCashflow >= 0 ? '+' : '-' }}Rp{{ number_format(abs($netCashflow), 0, ',', '.') }}
                    </span>
                </div>
                <a href="{{ route('reports.index', ['period_type' => 'monthly', 'month' => $currentMonthKey]) }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900 underline underline-offset-4">
                    Laporan Bulan Ini &rarr;
                </a>
            </div>
        </div>

        {{-- Anggaran Bulanan (Bagian 9.2) --}}
        <div class="bg-white rounded-xl border border-slate-200 p-3.5 sm:p-4 shadow-2xs flex flex-col justify-between space-y-3">
            <div>
                <h2 class="text-sm sm:text-base font-bold text-slate-900">Anggaran Pengeluaran</h2>
                <p class="text-[11px] text-slate-500">Kontrol batas belanja {{ $currentMonthLabel }}</p>

                @if ($currentBudget)
                    @php
                        $pct = $currentBudget->amount > 0 ? min(100, round(($monthlyExpense / $currentBudget->amount) * 100)) : 0;
                        $rawPct = $currentBudget->amount > 0 ? round(($monthlyExpense / $currentBudget->amount) * 100, 1) : 0;
                        $barColor = $rawPct > 100 ? 'bg-rose-600' : ($rawPct >= 80 ? 'bg-amber-500' : 'bg-emerald-500');
                    @endphp
                    <div class="mt-3 p-3 rounded-lg bg-slate-50 border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-500 font-medium">Realisasi vs Anggaran</span>
                            <span class="font-bold {{ $rawPct > 100 ? 'text-rose-600' : 'text-slate-700' }}">{{ $rawPct }}%</span>
                        </div>
                        <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full {{ $barColor }} rounded-full transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-[11px] pt-0.5">
                            <div>
                                <span class="text-slate-400 block">Terpakai</span>
                                <span class="font-semibold text-slate-800">Rp{{ number_format($monthlyExpense, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-slate-400 block">Batas Anggaran</span>
                                <span class="font-semibold text-slate-800">{{ $currentBudget->formatted_amount }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-3 p-3 rounded-lg bg-slate-50 border border-dashed border-slate-300 text-[11px] text-slate-500">
                        Belum ada batas anggaran untuk bulan {{ $currentMonthLabel }}.
                    </div>
                @endif
            </div>

            <form action="{{ route('budgets.store') }}" method="POST" class="space-y-2 pt-2 border-t border-slate-100">
                @csrf
                <input type="hidden" name="month" value="{{ $currentMonthKey }}">
                <label for="budget_amount" class="block text-[11px] font-semibold text-slate-700">
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
                        class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs focus:border-slate-900 focus:outline-none"
                    >
                    <button type="submit" class="w-full py-1.5 px-3 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan Saldo per Rekening (2 Kolom di Mobile) & Transaksi Terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3.5 sm:gap-4">
        {{-- Saldo per Rekening/Dompet --}}
        <div class="bg-white rounded-xl border border-slate-200 p-3.5 sm:p-4 shadow-2xs space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900">Saldo Rekening & Dompet</h2>
                    <p class="text-[11px] text-slate-500">Posisi saldo masing-masing akun</p>
                </div>
                <a href="{{ route('accounts.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">
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
                <div class="grid grid-cols-2 lg:grid-cols-1 gap-2 xs-single-col">
                    @foreach ($accounts as $account)
                        @php
                            $share = $totalBalance > 0 ? round(($account->balance / $totalBalance) * 100, 1) : 0;
                        @endphp
                        <a href="{{ route('accounts.show', $account) }}" class="block p-2.5 rounded-lg border border-slate-200 hover:border-slate-300 hover:bg-slate-50/70 transition min-w-0">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-1">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-900 truncate">{{ $account->name }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ $account->transactions_count }} tx &bull; {{ $share }}%</p>
                                </div>
                                <p class="text-xs font-bold text-slate-900 shrink-0">{{ $account->formatted_balance }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if (($categoriesCount ?? 0) === 0)
                <div class="pt-3 border-t border-slate-100">
                    <x-empty-state
                        title="Belum ada kategori. Buat kategori untuk mengelompokkan transaksi."
                        description="Tambahkan kategori pemasukan dan pengeluaran sesuai kebutuhan Anda."
                        :actionUrl="route('categories.create')"
                        actionText="+ Buat Kategori"
                    />
                </div>
            @elseif ($topExpenseCategories->isNotEmpty())
                <div class="pt-3 border-t border-slate-100 space-y-2">
                    <h3 class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Pengeluaran Terbesar Bulan Ini</h3>
                    <div class="grid grid-cols-2 lg:grid-cols-1 gap-2 xs-single-col">
                        @foreach ($topExpenseCategories as $item)
                            <div class="p-2 rounded-lg bg-slate-50 border border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-0.5 text-[11px] min-w-0">
                                <span class="text-slate-700 font-medium truncate">{{ $item->category?->name ?? '-' }} ({{ $item->tx_count }}x)</span>
                                <span class="font-semibold text-rose-600 shrink-0">Rp{{ number_format((int) $item->total_amount, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Daftar Transaksi Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-3.5 sm:p-4 shadow-2xs space-y-3 min-w-0">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900">Transaksi Terbaru</h2>
                    <p class="text-[11px] text-slate-500">Catatan pemasukan dan pengeluaran terakhir</p>
                </div>
                <a href="{{ route('transactions.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">
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
                            <tr class="border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                <th class="py-2 pr-2">Tanggal</th>
                                <th class="py-2 px-2">Kategori & Deskripsi</th>
                                <th class="py-2 px-2 hidden sm:table-cell">Rekening</th>
                                <th class="py-2 px-2">Tipe</th>
                                <th class="py-2 pl-2 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach ($recentTransactions as $tx)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-2 pr-2 whitespace-nowrap text-[11px] text-slate-500">
                                        {{ $tx->date->format('d/m/y') }}
                                    </td>
                                    <td class="py-2 px-2 min-w-0">
                                        <a href="{{ route('transactions.show', $tx) }}" class="font-semibold text-slate-900 hover:text-emerald-600 block truncate">
                                            {{ $tx->category?->name ?? '-' }}
                                        </a>
                                        <span class="text-[11px] text-slate-500 block truncate">
                                            {{ $tx->account?->name }} {{ $tx->description ? '• ' . $tx->description : '' }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-2 whitespace-nowrap text-[11px] font-medium text-slate-600 hidden sm:table-cell">
                                        {{ $tx->account?->name ?? '-' }}
                                    </td>
                                    <td class="py-2 px-2 whitespace-nowrap">
                                        <x-badge-type :type="$tx->type" />
                                    </td>
                                    <td class="py-2 pl-2 whitespace-nowrap text-right font-bold {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
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
