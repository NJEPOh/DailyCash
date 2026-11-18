<x-layouts.app title="Laporan Periodik">
    <x-page-header
        title="Laporan Keuangan"
        accent="Periodik"
        subtitle="Evaluasi pemasukan, pengeluaran, dan arus kas bersih berdasarkan periode ({{ $startDate }} s/d {{ $endDate }})."
    >
        <a href="{{ route('reports.export', request()->query()) }}" class="btn-bento-dark">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span>Ekspor CSV</span>
        </a>
    </x-page-header>

    {{-- Filter Periode Laporan --}}
    <div class="bento-card">
        <form action="{{ route('reports.index') }}" method="GET">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 xs-single-col">
                <div>
                    <label for="period_type" class="block text-[11px] lg:text-[12px] font-semibold text-[#18191B] mb-1.5">Jenis Periode</label>
                    <select
                        id="period_type"
                        name="period_type"
                        onchange="toggleCustomPeriodFields(this.value)"
                        class="input-field"
                    >
                        <option value="monthly" @selected($periodType === 'monthly')>Bulanan</option>
                        <option value="yearly" @selected($periodType === 'yearly')>Tahunan</option>
                        <option value="custom" @selected($periodType === 'custom')>Rentang Tanggal</option>
                    </select>
                </div>

                <div id="month_field" class="{{ $periodType === 'custom' ? 'hidden' : '' }}">
                    <label for="month" class="block text-[11px] lg:text-[12px] font-semibold text-[#18191B] mb-1.5">Pilih Bulan / Tahun</label>
                    <input
                        type="month"
                        id="month"
                        name="month"
                        value="{{ $month }}"
                        class="input-field"
                    >
                </div>

                <div id="start_date_field" class="{{ $periodType === 'custom' ? '' : 'hidden' }}">
                    <label for="start_date" class="block text-[11px] lg:text-[12px] font-semibold text-[#18191B] mb-1.5">Dari Tanggal</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $startDate }}"
                        class="input-field"
                    >
                </div>

                <div id="end_date_field" class="{{ $periodType === 'custom' ? '' : 'hidden' }}">
                    <label for="end_date" class="block text-[11px] lg:text-[12px] font-semibold text-[#18191B] mb-1.5">Sampai Tanggal</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ $endDate }}"
                        class="input-field"
                    >
                </div>

                <div>
                    <label for="account_id" class="block text-[11px] lg:text-[12px] font-semibold text-[#18191B] mb-1.5">Rekening / Dompet</label>
                    <select id="account_id" name="account_id" class="input-field">
                        <option value="">Semua Rekening</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" @selected((string) $accountId === (string) $account->id)>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-bento-dark flex-1">
                        Tampilkan
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn-secondary">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Metrik Ringkasan Periode --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 xs-single-col">
        <x-stat-card
            title="Total Pemasukan"
            :value="'Rp' . number_format($totalIncome, 0, ',', '.')"
            :subtitle="$startDate . ' s/d ' . $endDate"
            variant="lime"
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
            variant="dark"
        />
        <x-stat-card
            title="Volume Transaksi"
            :value="$transactionCount . ' Transaksi'"
            subtitle="Tercatat pada periode"
            variant="default"
        />
    </div>

    {{-- Rekapitulasi per Kategori --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Pemasukan per Kategori --}}
        <div class="bento-card space-y-4">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <h2 class="bento-h2">Pemasukan per Kategori</h2>
                    <p class="bento-meta">Proporsi sumber dana masuk</p>
                </div>
                <span class="badge badge-lime text-[12px]">Rp{{ number_format($totalIncome, 0, ',', '.') }}</span>
            </div>

            @if ($incomeByCategory->isEmpty())
                <div class="bento-dashed-zone py-6 px-4">
                    <p class="bento-meta">Tidak ada pemasukan pada periode ini.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 xs-single-col">
                    @foreach ($incomeByCategory as $item)
                        @php
                            $pct = $totalIncome > 0 ? round(((int) $item->total_amount / $totalIncome) * 100, 1) : 0;
                        @endphp
                        <div class="p-3 sm:p-0 rounded-[14px] sm:rounded-none bg-[#F9F9F8] sm:bg-transparent space-y-1.5 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 text-[12px] lg:text-[13px]">
                                <span class="font-semibold text-[#18191B] truncate">{{ $item->category?->name ?? '-' }} ({{ $item->tx_count }}x)</span>
                                <span class="font-bold text-[#4B6411] shrink-0">Rp{{ number_format((int) $item->total_amount, 0, ',', '.') }} ({{ $pct }}%)</span>
                            </div>
                            <div class="bento-progress-track">
                                <div class="bento-progress-fill-lime" style="width: {{ min(100, $pct) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Pengeluaran per Kategori --}}
        <div class="bento-card space-y-4">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <h2 class="bento-h2">Pengeluaran per Kategori</h2>
                    <p class="bento-meta">Distribusi pos belanja & beban</p>
                </div>
                <span class="badge badge-coral text-[12px]">Rp{{ number_format($totalExpense, 0, ',', '.') }}</span>
            </div>

            @if ($expenseByCategory->isEmpty())
                <div class="bento-dashed-zone py-6 px-4">
                    <p class="bento-meta">Tidak ada pengeluaran pada periode ini.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 xs-single-col">
                    @foreach ($expenseByCategory as $item)
                        @php
                            $pct = $totalExpense > 0 ? round(((int) $item->total_amount / $totalExpense) * 100, 1) : 0;
                        @endphp
                        <div class="p-3 sm:p-0 rounded-[14px] sm:rounded-none bg-[#F9F9F8] sm:bg-transparent space-y-1.5 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 text-[12px] lg:text-[13px]">
                                <span class="font-semibold text-[#18191B] truncate">{{ $item->category?->name ?? '-' }} ({{ $item->tx_count }}x)</span>
                                <span class="font-bold text-[#C23B3B] shrink-0">Rp{{ number_format((int) $item->total_amount, 0, ',', '.') }} ({{ $pct }}%)</span>
                            </div>
                            <div class="bento-progress-track">
                                <div class="bento-progress-fill-yellow" style="width: {{ min(100, $pct) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Tabel Transaksi Periode --}}
    <div class="bento-card space-y-4">
        <h2 class="bento-h2">Daftar Transaksi pada Periode Ini</h2>

        @if ($transactions->isEmpty())
            <x-empty-state
                title="Tidak ada transaksi pada periode ini"
                description="Pilih bulan atau rentang tanggal lain untuk melihat laporan transaksi."
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#ECECE9] bento-label">
                            <th class="py-3 pr-3">Tanggal</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3 hidden sm:table-cell">Rekening</th>
                            <th class="py-3 px-3">Deskripsi</th>
                            <th class="py-3 px-3">Tipe</th>
                            <th class="py-3 pl-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#ECECE9] text-[13px] lg:text-[14px]">
                        @foreach ($transactions as $tx)
                            <tr class="hover:bg-[#F9F9F8] transition">
                                <td class="py-3 pr-3 whitespace-nowrap bento-meta">{{ $tx->date->format('d/m/Y') }}</td>
                                <td class="py-3 px-3 font-semibold text-[#18191B]">{{ $tx->category?->name ?? '-' }}</td>
                                <td class="py-3 px-3 hidden sm:table-cell">
                                    <span class="badge badge-blue">{{ $tx->account?->name ?? '-' }}</span>
                                </td>
                                <td class="py-3 px-3 text-[#18191B]">
                                    <a href="{{ route('transactions.show', $tx) }}" class="hover:underline">
                                        {{ $tx->description ?: '-' }}
                                    </a>
                                </td>
                                <td class="py-3 px-3"><x-badge-type :type="$tx->type" /></td>
                                <td class="py-3 pl-3 text-right font-bold {{ $tx->type === 'income' ? 'text-[#4B6411]' : 'text-[#C23B3B]' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}{{ $tx->formatted_amount }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-3 border-t border-[#ECECE9]">
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
