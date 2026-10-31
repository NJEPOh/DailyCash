<x-layouts.app :title="'Detail Rekening: ' . $account->name">
    <x-page-header
        :title="$account->name"
        subtitle="Rincian posisi saldo dan mutasi transaksi pada rekening/dompet ini."
        :backUrl="route('accounts.index')"
        backText="Kembali ke Rekening & Dompet"
    >
        <a href="{{ route('accounts.edit', $account) }}" class="px-4 py-2 rounded-xl border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50">
            Ubah Nama
        </a>
        <a href="{{ route('transactions.create', ['account_id' => $account->id]) }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800">
            + Transaksi di Rekening Ini
        </a>
    </x-page-header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <x-stat-card
            title="Saldo Saat Ini"
            :value="$account->formatted_balance"
            :subtitle="$account->transactions_count . ' total transaksi'"
            variant="primary"
        />
        <x-stat-card
            title="Total Pemasukan Rekening"
            :value="'Rp' . number_format($totalIncome, 0, ',', '.')"
            subtitle="Akumulasi dana masuk"
            variant="income"
        />
        <x-stat-card
            title="Total Pengeluaran Rekening"
            :value="'Rp' . number_format($totalExpense, 0, ',', '.')"
            subtitle="Akumulasi dana keluar"
            variant="expense"
        />
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
        <h2 class="text-base font-bold text-slate-900">Riwayat Mutasi Transaksi</h2>

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
                            <th class="py-3 pr-3">Tanggal</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3">Deskripsi</th>
                            <th class="py-3 px-3">Tipe</th>
                            <th class="py-3 px-3 text-right">Nominal</th>
                            <th class="py-3 pl-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach ($transactions as $tx)
                            <tr class="hover:bg-slate-50/80">
                                <td class="py-3 pr-3 whitespace-nowrap text-xs text-slate-500">{{ $tx->date->format('d/m/Y') }}</td>
                                <td class="py-3 px-3 font-semibold text-slate-900">{{ $tx->category?->name ?? '-' }}</td>
                                <td class="py-3 px-3 text-slate-600">{{ $tx->description ?: '-' }}</td>
                                <td class="py-3 px-3"><x-badge-type :type="$tx->type" /></td>
                                <td class="py-3 px-3 text-right font-bold {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}{{ $tx->formatted_amount }}
                                </td>
                                <td class="py-3 pl-3 text-right whitespace-nowrap">
                                    <a href="{{ route('transactions.show', $tx) }}" class="px-2.5 py-1 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-100">
                                        Detail
                                    </a>
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
</x-layouts.app>
