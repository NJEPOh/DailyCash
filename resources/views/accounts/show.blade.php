<x-layouts.app :title="'Detail Rekening: ' . $account->name">
    <x-page-header
        :title="$account->name"
        subtitle="Rincian posisi saldo dan mutasi transaksi pada rekening/dompet ini."
        :backUrl="route('accounts.index')"
        backText="Kembali ke Rekening & Dompet"
    >
        <a href="{{ route('accounts.edit', $account) }}" class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-medium text-slate-700 hover:bg-slate-50">
            Ubah Nama
        </a>
        <a href="{{ route('transactions.create', ['account_id' => $account->id]) }}" class="px-3 py-1.5 rounded-lg bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800">
            + Transaksi Baru
        </a>
    </x-page-header>

    {{-- 2 Kolom di Mobile, 4 Kolom di Desktop --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3.5 xs-single-col">
        <x-stat-card
            title="Saldo Saat Ini"
            :value="$account->formatted_balance"
            subtitle="Posisi dana aktif"
            variant="primary"
        />
        <x-stat-card
            title="Total Pemasukan"
            :value="'Rp' . number_format($totalIncome, 0, ',', '.')"
            subtitle="Akumulasi dana masuk"
            variant="income"
        />
        <x-stat-card
            title="Total Pengeluaran"
            :value="'Rp' . number_format($totalExpense, 0, ',', '.')"
            subtitle="Akumulasi dana keluar"
            variant="expense"
        />
        <x-stat-card
            title="Total Transaksi"
            :value="$account->transactions_count . ' Transaksi'"
            subtitle="Frekuensi mutasi"
            variant="default"
        />
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-3.5 sm:p-4 shadow-2xs space-y-3">
        <h2 class="text-sm sm:text-base font-bold text-slate-900">Riwayat Mutasi Transaksi</h2>

        @if ($transactions->isEmpty())
            <x-empty-state
                title="Belum ada transaksi di rekening ini"
                description="Catat pemasukan atau pengeluaran menggunakan rekening ini."
                :actionUrl="route('transactions.create', ['account_id' => $account->id])"
                actionText="+ Catat Transaksi"
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="py-2 pr-2.5">Tanggal</th>
                            <th class="py-2 px-2.5">Kategori</th>
                            <th class="py-2 px-2.5">Deskripsi</th>
                            <th class="py-2 px-2.5">Tipe</th>
                            <th class="py-2 px-2.5 text-right">Nominal</th>
                            <th class="py-2 pl-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach ($transactions as $tx)
                            <tr class="hover:bg-slate-50/80">
                                <td class="py-2 pr-2.5 whitespace-nowrap text-[11px] text-slate-500">{{ $tx->date->format('d/m/Y') }}</td>
                                <td class="py-2 px-2.5 font-semibold text-slate-900">{{ $tx->category?->name ?? '-' }}</td>
                                <td class="py-2 px-2.5 text-slate-600">{{ $tx->description ?: '-' }}</td>
                                <td class="py-2 px-2.5"><x-badge-type :type="$tx->type" /></td>
                                <td class="py-2 px-2.5 text-right font-bold {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}{{ $tx->formatted_amount }}
                                </td>
                                <td class="py-2 pl-2.5 text-right whitespace-nowrap">
                                    <a href="{{ route('transactions.show', $tx) }}" class="px-2 py-1 rounded border border-slate-200 text-[11px] font-medium text-slate-700 hover:bg-slate-100">
                                        Detail
                                    </a>
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
</x-layouts.app>
