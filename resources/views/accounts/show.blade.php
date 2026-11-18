<x-layouts.app :title="'Detail Rekening: ' . $account->name">
    <x-page-header
        :title="$account->name"
        subtitle="Rincian posisi saldo dan mutasi transaksi pada rekening/dompet ini."
        :backUrl="route('accounts.index')"
        backText="Kembali ke Rekening & Dompet"
    >
        <a href="{{ route('accounts.edit', $account) }}" class="btn-secondary">
            Ubah Nama
        </a>
        <a href="{{ route('transactions.create', ['account_id' => $account->id]) }}" class="btn-bento-dark">
            + Transaksi Baru
        </a>
    </x-page-header>

    {{-- Statistik Saldo Rekening --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 xs-single-col">
        <x-stat-card
            title="Saldo Saat Ini"
            :value="$account->formatted_balance"
            subtitle="Posisi dana aktif"
            variant="dark"
        />
        <x-stat-card
            title="Total Pemasukan"
            :value="'Rp' . number_format($totalIncome, 0, ',', '.')"
            subtitle="Akumulasi dana masuk"
            variant="lime"
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

    <div class="bento-card space-y-4">
        <h2 class="bento-h2">Riwayat Mutasi Transaksi</h2>

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
                        <tr class="border-b border-[#ECECE9] bento-label">
                            <th class="py-3 pr-3">Tanggal</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3">Deskripsi</th>
                            <th class="py-3 px-3">Tipe</th>
                            <th class="py-3 px-3 text-right">Nominal</th>
                            <th class="py-3 pl-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#ECECE9] text-[13px] lg:text-[14px]">
                        @foreach ($transactions as $tx)
                            <tr class="hover:bg-[#F9F9F8] transition">
                                <td class="py-3 pr-3 whitespace-nowrap bento-meta">{{ $tx->date->format('d/m/Y') }}</td>
                                <td class="py-3 px-3 font-semibold text-[#18191B]">{{ $tx->category?->name ?? '-' }}</td>
                                <td class="py-3 px-3 bento-meta">{{ $tx->description ?: '-' }}</td>
                                <td class="py-3 px-3"><x-badge-type :type="$tx->type" /></td>
                                <td class="py-3 px-3 text-right font-bold {{ $tx->type === 'income' ? 'text-[#4B6411]' : 'text-[#C23B3B]' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}{{ $tx->formatted_amount }}
                                </td>
                                <td class="py-3 pl-3 text-right whitespace-nowrap">
                                    <a href="{{ route('transactions.show', $tx) }}" class="badge badge-neutral hover:bg-[#DDF296] hover:text-[#18191B] transition">
                                        Detail
                                    </a>
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
</x-layouts.app>
