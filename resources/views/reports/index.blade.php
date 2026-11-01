<x-layouts.app title="Laporan Periodik">
    <x-page-header
        title="Laporan Keuangan Periodik"
        subtitle="Evaluasi pemasukan, pengeluaran, dan arus kas bersih berdasarkan periode ({{ $startDate }} s/d {{ $endDate }})."
    >
        <a href="{{ route('reports.export', request()->query()) }}" class="btn-primary text-xs lg:text-sm">
            <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span>Ekspor CSV</span>
        </a>
    </x-page-header>

    {{-- Filter Periode Laporan (2 Kolom di Mobile) --}}
    <div class="card">
        <form action="{{ route('reports.index') }}" method="GET">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2.5 lg:gap-3.5 xs-single-col">
                <div>
                    <label for="period_type" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Jenis Periode</label>
                    <select
                        id="period_type"
                        name="period_type"
                        onchange="toggleCustomPeriodFields(this.value)"
                        class="input-field text-xs md:text-sm lg:text-base"
                    >
                        <option value="monthly" @selected($periodType === 'monthly')>Bulanan</option>
                        <option value="yearly" @selected($periodType === 'yearly')>Tahunan</option>
                        <option value="custom" @selected($periodType === 'custom')>Rentang Tanggal</option>
                    </select>
                </div>

                <div id="month_field" class="{{ $periodType === 'custom' ? 'hidden' : '' }}">
                    <label for="month" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Pilih Bulan / Tahun</label>
                    <input
                        type="month"
                        id="month"
                        name="month"
                        value="{{ $month }}"
                        class="input-field text-xs md:text-sm lg:text-base"
                    >
                </div>

                <div id="start_date_field" class="{{ $periodType === 'custom' ? '' : 'hidden' }}">
                    <label for="start_date" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Dari Tanggal</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $startDate }}"
                        class="input-field text-xs md:text-sm lg:text-base"
                    >
                </div>

                <div id="end_date_field" class="{{ $periodType === 'custom' ? '' : 'hidden' }}">
                    <label for="end_date" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Sampai Tanggal</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ $endDate }}"
                        class="input-field text-xs md:text-sm lg:text-base"
                    >
                </div>

                <div>
                    <label for="account_id" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Rekening / Dompet</label>
                    <select
                        id="account_id"
                        name="account_id"
                        class="input-field text-xs md:text-sm lg:text-base"
                    >
                        <option value="">Semua Rekening</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" @selected((string) $accountId === (string) $account->id)>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-1.5 lg:gap-2">
                    <button type="submit" class="btn-primary w-full text-xs lg:text-sm">
                        Tampilkan
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn-secondary text-xs lg:text-sm">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Metrik Ringkasan Periode (2 Kolom di Mobile, 4 Kolom di Desktop) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3.5 lg:gap-5 xs-single-col">
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3.5 sm:gap-4 lg:gap-6">
        {{-- Pemasukan per Kategori --}}
        <div class="card space-y-3 lg:space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Pemasukan per Kategori</h2>
                <span class="text-xs md:text-sm lg:text-base font-bold text-[#2ED47A]">Rp{{ number_format($totalIncome, 0, ',', '.') }}</span>
            </div>

            @if ($incomeByCategory->isEmpty())
                <p class="text-xs md:text-sm lg:text-base text-[#7E869E] py-4 text-center">Tidak ada pemasukan pada periode ini.</p>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-1 gap-2 lg:gap-3 xs-single-col">
                    @foreach ($incomeByCategory as $item)
                        @php
                            $pct = $totalIncome > 0 ? round(((int) $item->total_amount / $totalIncome) * 100, 1) : 0;
                        @endphp
                        <div class="p-2.5 sm:p-0 rounded-[12px] sm:rounded-none bg-[#F4F5F9] sm:bg-transparent space-y-1.5 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between text-[11px] lg:text-sm gap-0.5">
                                <span class="font-semibold text-[#1A1D20] truncate">{{ $item->category?->name ?? '-' }} ({{ $item->tx_count }}x)</span>
                                <span class="font-bold text-[#2ED47A] shrink-0">Rp{{ number_format((int) $item->total_amount, 0, ',', '.') }} ({{ $pct }}%)</span>
                            </div>
                            <div class="progress-container">
                                <div class="progress-bar progress-bar-green" style="width: {{ min(100, $pct) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Pengeluaran per Kategori --}}
        <div class="card space-y-3 lg:space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Pengeluaran per Kategori</h2>
                <span class="text-xs md:text-sm lg:text-base font-bold text-[#FF6B8B]">Rp{{ number_format($totalExpense, 0, ',', '.') }}</span>
            </div>

            @if ($expenseByCategory->isEmpty())
                <p class="text-xs md:text-sm lg:text-base text-[#7E869E] py-4 text-center">Tidak ada pengeluaran pada periode ini.</p>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-1 gap-2 lg:gap-3 xs-single-col">
                    @foreach ($expenseByCategory as $item)
                        @php
                            $pct = $totalExpense > 0 ? round(((int) $item->total_amount / $totalExpense) * 100, 1) : 0;
                        @endphp
                        <div class="p-2.5 sm:p-0 rounded-[12px] sm:rounded-none bg-[#F4F5F9] sm:bg-transparent space-y-1.5 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between text-[11px] lg:text-sm gap-0.5">
                                <span class="font-semibold text-[#1A1D20] truncate">{{ $item->category?->name ?? '-' }} ({{ $item->tx_count }}x)</span>
                                <span class="font-bold text-[#FF6B8B] shrink-0">Rp{{ number_format((int) $item->total_amount, 0, ',', '.') }} ({{ $pct }}%)</span>
                            </div>
                            <div class="progress-container">
                                <div class="progress-bar progress-bar-pink" style="width: {{ min(100, $pct) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Tabel Transaksi Periode --}}
    <div class="card space-y-3 lg:space-y-4">
        <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Daftar Transaksi pada Periode Ini</h2>

        @if ($transactions->isEmpty())
            <x-empty-state
                title="Tidak ada transaksi pada periode ini"
                description="Pilih bulan atau rentang tanggal lain untuk melihat laporan transaksi."
            />
        @else
            <div class="overflow-x-auto -mx-3.5 sm:mx-0 px-3.5 sm:px-0">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#F4F5F9] text-[11px] lg:text-sm font-semibold uppercase tracking-wider text-[#A3AABF]">
                            <th class="py-2 lg:py-3 pr-2">Tanggal</th>
                            <th class="py-2 lg:py-3 px-2">Kategori</th>
                            <th class="py-2 lg:py-3 px-2 hidden sm:table-cell">Rekening</th>
                            <th class="py-2 lg:py-3 px-2">Deskripsi</th>
                            <th class="py-2 lg:py-3 px-2">Tipe</th>
                            <th class="py-2 lg:py-3 pl-2 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F4F5F9] text-xs md:text-sm lg:text-base">
                        @foreach ($transactions as $tx)
                            <tr class="hover:bg-[#F4F5F9]/60">
                                <td class="py-2 lg:py-3 pr-2 whitespace-nowrap text-[11px] lg:text-sm text-[#7E869E]">{{ $tx->date->format('d/m/Y') }}</td>
                                <td class="py-2 lg:py-3 px-2 font-semibold text-[#1A1D20]">{{ $tx->category?->name ?? '-' }}</td>
                                <td class="py-2 lg:py-3 px-2 text-[11px] lg:text-sm font-medium text-[#1A1D20] hidden sm:table-cell">{{ $tx->account?->name ?? '-' }}</td>
                                <td class="py-2 lg:py-3 px-2 text-[#7E869E]">
                                    <a href="{{ route('transactions.show', $tx) }}" class="hover:text-[#5D56F3] hover:underline">
                                        {{ $tx->description ?: '-' }}
                                    </a>
                                </td>
                                <td class="py-2 lg:py-3 px-2"><x-badge-type :type="$tx->type" /></td>
                                <td class="py-2 lg:py-3 pl-2 text-right font-bold {{ $tx->type === 'income' ? 'text-[#2ED47A]' : 'text-[#FF6B8B]' }}">
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
