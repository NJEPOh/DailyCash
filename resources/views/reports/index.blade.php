<x-layouts.app title="Laporan Periodik">
    <x-page-header
        title="Laporan Keuangan Periodik"
        subtitle="Evaluasi pemasukan, pengeluaran, dan arus kas bersih berdasarkan periode ({{ $startDate }} s/d {{ $endDate }})."
    >
        <a href="{{ route('reports.export', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span>Ekspor CSV</span>
        </a>
    </x-page-header>

    {{-- Filter Periode Laporan --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
        <form action="{{ route('reports.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label for="period_type" class="block text-xs font-semibold text-slate-600 mb-1">Jenis Periode</label>
                    <select
                        id="period_type"
                        name="period_type"
                        onchange="toggleCustomPeriodFields(this.value)"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm bg-white focus:border-slate-900 focus:outline-none"
                    >
                        <option value="monthly" @selected($periodType === 'monthly')>Bulanan</option>
                        <option value="yearly" @selected($periodType === 'yearly')>Tahunan</option>
                        <option value="custom" @selected($periodType === 'custom')>Rentang Tanggal Kustom</option>
                    </select>
                </div>

                <div id="month_field" class="{{ $periodType === 'custom' ? 'hidden' : '' }}">
                    <label for="month" class="block text-xs font-semibold text-slate-600 mb-1">Pilih Bulan / Tahun</label>
                    <input
                        type="month"
                        id="month"
                        name="month"
                        value="{{ $month }}"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none"
                    >
                </div>

                <div id="start_date_field" class="{{ $periodType === 'custom' ? '' : 'hidden' }}">
                    <label for="start_date" class="block text-xs font-semibold text-slate-600 mb-1">Dari Tanggal</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $startDate }}"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none"
                    >
                </div>

                <div id="end_date_field" class="{{ $periodType === 'custom' ? '' : 'hidden' }}">
                    <label for="end_date" class="block text-xs font-semibold text-slate-600 mb-1">Sampai Tanggal</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ $endDate }}"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="account_id" class="block text-xs font-semibold text-slate-600 mb-1">Rekening / Dompet</label>
                    <select
                        id="account_id"
                        name="account_id"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm bg-white focus:border-slate-900 focus:outline-none"
                    >
                        <option value="">Semua Rekening</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" @selected((string) $accountId === (string) $account->id)>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="w-full py-2 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
                        Tampilkan Laporan
                    </button>
                    <a href="{{ route('reports.index') }}" class="py-2 px-3 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Metrik Ringkasan Periode --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <x-stat-card
            title="Total Pemasukan Periode"
            :value="'Rp' . number_format($totalIncome, 0, ',', '.')"
            :subtitle="'Rentang ' . $startDate . ' s/d ' . $endDate"
            variant="income"
        />
        <x-stat-card
            title="Total Pengeluaran Periode"
            :value="'Rp' . number_format($totalExpense, 0, ',', '.')"
            :subtitle="'Rentang ' . $startDate . ' s/d ' . $endDate"
            variant="expense"
        />
        <x-stat-card
            title="Selisih / Arus Kas Bersih"
            :value="($netBalance >= 0 ? '+Rp' : '-Rp') . number_format(abs($netBalance), 0, ',', '.')"
            :subtitle="$transactionCount . ' total transaksi pada periode ini'"
            variant="primary"
        />
    </div>

    {{-- Rekapitulasi per Kategori --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pemasukan per Kategori --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900">Rincian Pemasukan per Kategori</h2>
                <span class="text-xs font-bold text-emerald-600">Rp{{ number_format($totalIncome, 0, ',', '.') }}</span>
            </div>

            @if ($incomeByCategory->isEmpty())
                <p class="text-sm text-slate-500 py-6 text-center">Tidak ada pemasukan pada periode ini.</p>
            @else
                <div class="space-y-3">
                    @foreach ($incomeByCategory as $item)
                        @php
                            $pct = $totalIncome > 0 ? round(((int) $item->total_amount / $totalIncome) * 100, 1) : 0;
                        @endphp
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-semibold text-slate-800">{{ $item->category?->name ?? '-' }} ({{ $item->tx_count }}x)</span>
                                <span class="font-bold text-emerald-600">Rp{{ number_format((int) $item->total_amount, 0, ',', '.') }} ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ min(100, $pct) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Pengeluaran per Kategori --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900">Rincian Pengeluaran per Kategori</h2>
                <span class="text-xs font-bold text-rose-600">Rp{{ number_format($totalExpense, 0, ',', '.') }}</span>
            </div>

            @if ($expenseByCategory->isEmpty())
                <p class="text-sm text-slate-500 py-6 text-center">Tidak ada pengeluaran pada periode ini.</p>
            @else
                <div class="space-y-3">
                    @foreach ($expenseByCategory as $item)
                        @php
                            $pct = $totalExpense > 0 ? round(((int) $item->total_amount / $totalExpense) * 100, 1) : 0;
                        @endphp
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-semibold text-slate-800">{{ $item->category?->name ?? '-' }} ({{ $item->tx_count }}x)</span>
                                <span class="font-bold text-rose-600">Rp{{ number_format((int) $item->total_amount, 0, ',', '.') }} ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-rose-500 rounded-full" style="width: {{ min(100, $pct) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Tabel Transaksi Periode --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
        <h2 class="text-base font-bold text-slate-900">Daftar Transaksi pada Periode Ini</h2>

        @if ($transactions->isEmpty())
            <x-empty-state
                title="Tidak ada transaksi pada periode ini"
                description="Pilih bulan atau rentang tanggal lain untuk melihat laporan transaksi."
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="py-3 pr-3">Tanggal</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3">Rekening</th>
                            <th class="py-3 px-3">Deskripsi</th>
                            <th class="py-3 px-3">Tipe</th>
                            <th class="py-3 pl-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach ($transactions as $tx)
                            <tr class="hover:bg-slate-50/80">
                                <td class="py-3 pr-3 whitespace-nowrap text-xs text-slate-500">{{ $tx->date->format('d/m/Y') }}</td>
                                <td class="py-3 px-3 font-semibold text-slate-900">{{ $tx->category?->name ?? '-' }}</td>
                                <td class="py-3 px-3 text-xs font-medium text-slate-700">{{ $tx->account?->name ?? '-' }}</td>
                                <td class="py-3 px-3 text-slate-600">
                                    <a href="{{ route('transactions.show', $tx) }}" class="hover:underline">
                                        {{ $tx->description ?: '-' }}
                                    </a>
                                </td>
                                <td class="py-3 px-3"><x-badge-type :type="$tx->type" /></td>
                                <td class="py-3 pl-3 text-right font-bold {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}{{ $tx->formatted_amount }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <script>
        function toggleCustomPeriodFields(periodType) {
            const monthField = document.getElementById('month_field');
            const startField = document.getElementById('start_date_field');
            const endField = document.getElementById('end_date_field');

            if (periodType === 'custom') {
                monthField.classList.add('hidden');
                startField.classList.remove('hidden');
                endField.classList.remove('hidden');
            } else {
                monthField.classList.remove('hidden');
                startField.classList.add('hidden');
                endField.classList.add('hidden');
            }
        }
    </script>
</x-layouts.app>
