<x-layouts.app title="Laporan Periodik">
    <x-page-header
        title="Laporan Keuangan Periodik"
        subtitle="Evaluasi pemasukan, pengeluaran, dan arus kas bersih berdasarkan periode ({{ $startDate }} s/d {{ $endDate }})."
    >
        <a href="{{ route('reports.export', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span>Ekspor CSV</span>
        </a>
    </x-page-header>

    {{-- Filter Periode Laporan (2 Kolom di Mobile) --}}
    <div class="bg-white rounded-xl border border-slate-200 p-3.5 sm:p-4 shadow-2xs">
        <form action="{{ route('reports.index') }}" method="GET">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2.5 xs-single-col">
                <div>
                    <label for="period_type" class="block text-[11px] font-semibold text-slate-600 mb-1">Jenis Periode</label>
                    <select
                        id="period_type"
                        name="period_type"
                        onchange="toggleCustomPeriodFields(this.value)"
                        class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white focus:border-slate-900 focus:outline-none"
                    >
                        <option value="monthly" @selected($periodType === 'monthly')>Bulanan</option>
                        <option value="yearly" @selected($periodType === 'yearly')>Tahunan</option>
                        <option value="custom" @selected($periodType === 'custom')>Rentang Tanggal</option>
                    </select>
                </div>

                <div id="month_field" class="{{ $periodType === 'custom' ? 'hidden' : '' }}">
                    <label for="month" class="block text-[11px] font-semibold text-slate-600 mb-1">Pilih Bulan / Tahun</label>
                    <input
                        type="month"
                        id="month"
                        name="month"
                        value="{{ $month }}"
                        class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs focus:border-slate-900 focus:outline-none"
                    >
                </div>

                <div id="start_date_field" class="{{ $periodType === 'custom' ? '' : 'hidden' }}">
                    <label for="start_date" class="block text-[11px] font-semibold text-slate-600 mb-1">Dari Tanggal</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $startDate }}"
                        class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs focus:border-slate-900 focus:outline-none"
                    >
                </div>

                <div id="end_date_field" class="{{ $periodType === 'custom' ? '' : 'hidden' }}">
                    <label for="end_date" class="block text-[11px] font-semibold text-slate-600 mb-1">Sampai Tanggal</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ $endDate }}"
                        class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs focus:border-slate-900 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="account_id" class="block text-[11px] font-semibold text-slate-600 mb-1">Rekening / Dompet</label>
                    <select
                        id="account_id"
                        name="account_id"
                        class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white focus:border-slate-900 focus:outline-none"
                    >
                        <option value="">Semua Rekening</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" @selected((string) $accountId === (string) $account->id)>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-1.5">
                    <button type="submit" class="w-full py-1.5 px-3 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
                        Tampilkan
                    </button>
                    <a href="{{ route('reports.index') }}" class="py-1.5 px-2.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Metrik Ringkasan Periode (2 Kolom di Mobile, 4 Kolom di Desktop) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3.5 xs-single-col">
        <x-stat-card
            title="Total Pemasukan"
            :value="'Rp' . number_format($totalIncome, 0, ',', '.')"
            :subtitle="$startDate . ' s/d ' . $endDate"
            variant="income"
        />
        <x-stat-card
            title="Total Pengeluaran"
            :value="'Rp' . number_format($totalExpense, 0, ',', '.')"
            :subtitle="$startDate . ' s/d ' . $endDate"
            variant="expense"
        />
        <x-stat-card
            title="Selisih Arus Kas"
            :value="($netBalance >= 0 ? '+Rp' : '-Rp') . number_format(abs($netBalance), 0, ',', '.')"
            :subtitle="$netBalance >= 0 ? 'Surplus periode' : 'Defisit periode'"
            variant="primary"
        />
        <x-stat-card
            title="Volume Transaksi"
            :value="$transactionCount . ' Transaksi'"
            subtitle="Tercatat pada periode"
            variant="default"
        />
    </div>

    {{-- Rekapitulasi per Kategori --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3.5 sm:gap-4">
        {{-- Pemasukan per Kategori --}}
        <div class="bg-white rounded-xl border border-slate-200 p-3.5 sm:p-4 shadow-2xs space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-sm sm:text-base font-bold text-slate-900">Pemasukan per Kategori</h2>
                <span class="text-xs font-bold text-emerald-600">Rp{{ number_format($totalIncome, 0, ',', '.') }}</span>
            </div>

            @if ($incomeByCategory->isEmpty())
                <p class="text-xs text-slate-500 py-4 text-center">Tidak ada pemasukan pada periode ini.</p>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-1 gap-2 xs-single-col">
                    @foreach ($incomeByCategory as $item)
                        @php
                            $pct = $totalIncome > 0 ? round(((int) $item->total_amount / $totalIncome) * 100, 1) : 0;
                        @endphp
                        <div class="p-2 sm:p-0 rounded-lg border sm:border-0 border-slate-100 space-y-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between text-[11px] gap-0.5">
                                <span class="font-semibold text-slate-800 truncate">{{ $item->category?->name ?? '-' }} ({{ $item->tx_count }}x)</span>
                                <span class="font-bold text-emerald-600 shrink-0">Rp{{ number_format((int) $item->total_amount, 0, ',', '.') }} ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ min(100, $pct) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Pengeluaran per Kategori --}}
        <div class="bg-white rounded-xl border border-slate-200 p-3.5 sm:p-4 shadow-2xs space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-sm sm:text-base font-bold text-slate-900">Pengeluaran per Kategori</h2>
                <span class="text-xs font-bold text-rose-600">Rp{{ number_format($totalExpense, 0, ',', '.') }}</span>
            </div>

            @if ($expenseByCategory->isEmpty())
                <p class="text-xs text-slate-500 py-4 text-center">Tidak ada pengeluaran pada periode ini.</p>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-1 gap-2 xs-single-col">
                    @foreach ($expenseByCategory as $item)
                        @php
                            $pct = $totalExpense > 0 ? round(((int) $item->total_amount / $totalExpense) * 100, 1) : 0;
                        @endphp
                        <div class="p-2 sm:p-0 rounded-lg border sm:border-0 border-slate-100 space-y-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between text-[11px] gap-0.5">
                                <span class="font-semibold text-slate-800 truncate">{{ $item->category?->name ?? '-' }} ({{ $item->tx_count }}x)</span>
                                <span class="font-bold text-rose-600 shrink-0">Rp{{ number_format((int) $item->total_amount, 0, ',', '.') }} ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-rose-500 rounded-full" style="width: {{ min(100, $pct) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Tabel Transaksi Periode --}}
    <div class="bg-white rounded-xl border border-slate-200 p-3.5 sm:p-4 shadow-2xs space-y-3">
        <h2 class="text-sm sm:text-base font-bold text-slate-900">Daftar Transaksi pada Periode Ini</h2>

        @if ($transactions->isEmpty())
            <x-empty-state
                title="Tidak ada transaksi pada periode ini"
                description="Pilih bulan atau rentang tanggal lain untuk melihat laporan transaksi."
            />
        @else
            <div class="overflow-x-auto -mx-3.5 sm:mx-0 px-3.5 sm:px-0">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="py-2 pr-2">Tanggal</th>
                            <th class="py-2 px-2">Kategori</th>
                            <th class="py-2 px-2 hidden sm:table-cell">Rekening</th>
                            <th class="py-2 px-2">Deskripsi</th>
                            <th class="py-2 px-2">Tipe</th>
                            <th class="py-2 pl-2 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach ($transactions as $tx)
                            <tr class="hover:bg-slate-50/80">
                                <td class="py-2 pr-2 whitespace-nowrap text-[11px] text-slate-500">{{ $tx->date->format('d/m/Y') }}</td>
                                <td class="py-2 px-2 font-semibold text-slate-900">{{ $tx->category?->name ?? '-' }}</td>
                                <td class="py-2 px-2 text-[11px] font-medium text-slate-700 hidden sm:table-cell">{{ $tx->account?->name ?? '-' }}</td>
                                <td class="py-2 px-2 text-slate-600">
                                    <a href="{{ route('transactions.show', $tx) }}" class="hover:underline">
                                        {{ $tx->description ?: '-' }}
                                    </a>
                                </td>
                                <td class="py-2 px-2"><x-badge-type :type="$tx->type" /></td>
                                <td class="py-2 pl-2 text-right font-bold {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}{{ $tx->formatted_amount }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-3">
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
