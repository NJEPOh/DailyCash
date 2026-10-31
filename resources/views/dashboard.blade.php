<x-layouts.app title="Dashboard">
    <x-page-header
        title="Dashboard Keuangan"
        subtitle="Ringkasan saldo, arus kas bulan {{ $currentMonthLabel }}, dan evaluasi anggaran."
    >
        <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium transition">
            <span>Laporan Periodik</span>
        </a>
        <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition">
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
            <div class="rounded-2xl bg-rose-600 text-white p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4" role="alert">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold">Peringatan Anggaran Terlampaui!</h2>
                        <p class="text-sm text-rose-100 mt-0.5">
                            Total pengeluaran bulan {{ $currentMonthLabel }} (<strong>Rp{{ number_format($monthlyExpense, 0, ',', '.') }}</strong>) telah melebihi batas anggaran bulanan Anda (<strong>{{ $currentBudget->formatted_amount }}</strong>) sebesar <strong>Rp{{ number_format($monthlyExpense - $budgetLimit, 0, ',', '.') }}</strong> ({{ $budgetPercentage }}%).
                        </p>
                    </div>
                </div>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-white text-rose-700 text-xs font-bold hover:bg-rose-50 shrink-0 transition">
                    Evaluasi Pengeluaran
                </a>
            </div>
        @elseif ($budgetPercentage >= 80)
            <div class="rounded-2xl bg-amber-50 border border-amber-300 text-amber-900 p-4 flex items-center justify-between gap-4" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm">
                        <strong>Perhatian:</strong> Penggunaan anggaran bulan ini telah mencapai <strong>{{ $budgetPercentage }}%</strong> dari batas <strong>{{ $currentBudget->formatted_amount }}</strong>.
                    </p>
                </div>
            </div>
        @endif
    @endif

    {{-- 3 Metrik Utama (Bagian 4.7) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <x-stat-card
            title="Total Saldo Keseluruhan"
            :value="'Rp' . number_format($totalBalance, 0, ',', '.')"
            :subtitle="'Akumulasi dari ' . $accounts->count() . ' rekening/dompet aktif'"
            variant="primary"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    {{-- Baris Grafik Arus Kas & Anggaran Bulanan --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Grafik Arus Kas 6 Bulan & Ringkasan Pemasukan/Pengeluaran --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Grafik Arus Kas (6 Bulan Terakhir)</h2>
                        <p class="text-xs text-slate-500">Perbandingan pemasukan dan pengeluaran bulanan</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-medium">
                        <span class="inline-flex items-center gap-1.5 text-emerald-700">
                            <span class="w-3 h-3 rounded-xs bg-emerald-500"></span> Pemasukan
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-rose-700">
                            <span class="w-3 h-3 rounded-xs bg-rose-500"></span> Pengeluaran
                        </span>
                    </div>
                </div>

                @php
                    $maxChartVal = collect($cashflowChart)->flatMap(fn ($item) => [$item['income'], $item['expense']])->max() ?: 1;
                @endphp

                <div class="grid grid-cols-6 gap-3 items-end h-44 pt-4 px-2 border-b border-slate-200">
                    @foreach ($cashflowChart as $point)
                        @php
                            $incHeight = max(4, round(($point['income'] / $maxChartVal) * 140));
                            $expHeight = max(4, round(($point['expense'] / $maxChartVal) * 140));
                        @endphp
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-full flex items-end justify-center gap-1.5 h-36">
                                <div class="w-3 sm:w-5 rounded-t-md bg-emerald-500 transition-all"
                                     style="height: {{ $point['income'] > 0 ? $incHeight : 4 }}px"
                                     title="Pemasukan {{ $point['label'] }}: Rp{{ number_format($point['income'], 0, ',', '.') }}"></div>
                                <div class="w-3 sm:w-5 rounded-t-md bg-rose-500 transition-all"
                                     style="height: {{ $point['expense'] > 0 ? $expHeight : 4 }}px"
                                     title="Pengeluaran {{ $point['label'] }}: Rp{{ number_format($point['expense'], 0, ',', '.') }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="grid grid-cols-6 gap-3 pt-2 text-center">
                    @foreach ($cashflowChart as $point)
                        <span class="text-[11px] font-medium text-slate-500 truncate">{{ $point['label'] }}</span>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-sm">
                <div>
                    <span class="text-slate-500">Selisih Arus Kas Bulan Ini:</span>
                    <span class="font-bold ml-1 {{ $netCashflow >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $netCashflow >= 0 ? '+' : '-' }}Rp{{ number_format(abs($netCashflow), 0, ',', '.') }}
                    </span>
                </div>
                <a href="{{ route('reports.index', ['period_type' => 'monthly', 'month' => $currentMonthKey]) }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900 underline underline-offset-4">
                    Lihat Laporan Lengkap Bulan Ini &rarr;
                </a>
            </div>
        </div>

        {{-- Anggaran Bulanan (Bagian 9.2) --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between space-y-5">
            <div>
                <h2 class="text-base font-bold text-slate-900">Anggaran Pengeluaran Bulanan</h2>
                <p class="text-xs text-slate-500 mt-0.5">Kontrol batas belanja periode {{ $currentMonthLabel }}</p>

                @if ($currentBudget)
                    @php
                        $pct = $currentBudget->amount > 0 ? min(100, round(($monthlyExpense / $currentBudget->amount) * 100)) : 0;
                        $rawPct = $currentBudget->amount > 0 ? round(($monthlyExpense / $currentBudget->amount) * 100, 1) : 0;
                        $barColor = $rawPct > 100 ? 'bg-rose-600' : ($rawPct >= 80 ? 'bg-amber-500' : 'bg-emerald-500');
                    @endphp
                    <div class="mt-5 p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">Realisasi vs Anggaran</span>
                            <span class="font-bold {{ $rawPct > 100 ? 'text-rose-600' : 'text-slate-700' }}">{{ $rawPct }}%</span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full {{ $barColor }} rounded-full transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="flex items-center justify-between text-xs pt-1">
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
                    <div class="mt-5 p-4 rounded-xl bg-slate-50 border border-dashed border-slate-300 text-xs text-slate-500">
                        Belum ada batas anggaran untuk bulan {{ $currentMonthLabel }}. Atur batas pengeluaran di bawah ini.
                    </div>
                @endif
            </div>

            <form action="{{ route('budgets.store') }}" method="POST" class="space-y-3 pt-2 border-t border-slate-100">
                @csrf
                <input type="hidden" name="month" value="{{ $currentMonthKey }}">
                <div>
                    <label for="budget_amount" class="block text-xs font-semibold text-slate-700 mb-1">
                        {{ $currentBudget ? 'Perbarui Batas Anggaran (Rp)' : 'Tetapkan Batas Anggaran (Rp)' }}
                    </label>
                    <input
                        type="number"
                        id="budget_amount"
                        name="amount"
                        min="1"
                        step="1"
                        required
                        value="{{ old('amount', $currentBudget?->amount) }}"
                        placeholder="Contoh: 5000000"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-slate-900 focus:outline-none"
                    >
                </div>
                <button type="submit" class="w-full py-2 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
                    Simpan Anggaran Bulan Ini
                </button>
            </form>
        </div>
    </div>

    {{-- Ringkasan Saldo per Rekening/Dompet & Pengeluaran per Kategori --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Saldo per Rekening/Dompet --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Saldo Rekening & Dompet</h2>
                    <p class="text-xs text-slate-500">Posisi saldo masing-masing akun</p>
                </div>
                <a href="{{ route('accounts.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                    Kelola &rarr;
                </a>
            </div>

            @if ($accounts->isEmpty())
                <x-empty-state
                    title="Belum ada rekening"
                    description="Tambahkan rekening atau dompet terlebih dahulu."
                    :actionUrl="route('accounts.create')"
                    actionText="+ Tambah Rekening"
                />
            @else
                <div class="space-y-3">
                    @foreach ($accounts as $account)
                        @php
                            $share = $totalBalance > 0 ? round(($account->balance / $totalBalance) * 100, 1) : 0;
                        @endphp
                        <a href="{{ route('accounts.show', $account) }}" class="block p-3.5 rounded-xl border border-slate-200 hover:border-slate-300 hover:bg-slate-50/70 transition">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $account->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $account->transactions_count }} transaksi &bull; {{ $share }}% dari total</p>
                                </div>
                                <p class="text-sm font-bold text-slate-900">{{ $account->formatted_balance }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($topExpenseCategories->isNotEmpty())
                <div class="pt-4 border-t border-slate-100 space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Pengeluaran Terbesar Bulan Ini</h3>
                    @foreach ($topExpenseCategories as $item)
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-700 font-medium">{{ $item->category?->name ?? '-' }} ({{ $item->tx_count }}x)</span>
                            <span class="font-semibold text-rose-600">Rp{{ number_format((int) $item->total_amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Daftar Transaksi Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Transaksi Terbaru</h2>
                    <p class="text-xs text-slate-500">Catatan pemasukan dan pengeluaran terakhir</p>
                </div>
                <a href="{{ route('transactions.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if ($recentTransactions->isEmpty())
                <x-empty-state
                    title="Belum ada transaksi"
                    description="Catat pemasukan atau pengeluaran pertama Anda untuk melihat histori di sini."
                    :actionUrl="route('transactions.create')"
                    actionText="+ Catat Transaksi"
                />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                <th class="py-3 pr-3">Tanggal</th>
                                <th class="py-3 px-3">Kategori & Deskripsi</th>
                                <th class="py-3 px-3">Rekening</th>
                                <th class="py-3 px-3">Tipe</th>
                                <th class="py-3 pl-3 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach ($recentTransactions as $tx)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3 pr-3 whitespace-nowrap text-xs text-slate-500">
                                        {{ $tx->date->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <a href="{{ route('transactions.show', $tx) }}" class="font-semibold text-slate-900 hover:text-emerald-600 block">
                                            {{ $tx->category?->name ?? '-' }}
                                        </a>
                                        @if ($tx->description)
                                            <span class="text-xs text-slate-500 line-clamp-1">{{ $tx->description }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap text-xs font-medium text-slate-600">
                                        {{ $tx->account?->name ?? '-' }}
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <x-badge-type :type="$tx->type" />
                                    </td>
                                    <td class="py-3 pl-3 whitespace-nowrap text-right font-bold {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
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
