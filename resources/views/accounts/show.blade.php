<x-layouts.app :title="'Detail Rekening: ' . $account->name">
    <x-page-header
        :title="$account->name"
        subtitle="Rincian posisi saldo dan mutasi transaksi pada rekening/dompet ini."
        :backUrl="route('accounts.index')"
        backText="Kembali ke Rekening & Dompet"
    >
        <a href="{{ route('accounts.edit', $account) }}" class="btn-secondary text-xs lg:text-sm">
            Ubah Nama
        </a>
        <a href="{{ route('transactions.create', ['account_id' => $account->id]) }}" class="btn-primary text-xs lg:text-sm">
            + Transaksi Baru
        </a>
    </x-page-header>

    {{-- 2 Kolom di Mobile, 4 Kolom di Desktop --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3.5 lg:gap-5 xs-single-col">
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

    <div class="card space-y-3 lg:space-y-4">
        <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Riwayat Mutasi Transaksi</h2>

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
                        <tr class="border-b border-[#F4F5F9] text-[11px] lg:text-sm font-semibold uppercase tracking-wider text-[#A3AABF]">
                            <th class="py-2 lg:py-3 pr-2.5">Tanggal</th>
                            <th class="py-2 lg:py-3 px-2.5">Kategori</th>
                            <th class="py-2 lg:py-3 px-2.5">Deskripsi</th>
                            <th class="py-2 lg:py-3 px-2.5">Tipe</th>
                            <th class="py-2 lg:py-3 px-2.5 text-right">Nominal</th>
                            <th class="py-2 lg:py-3 pl-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F4F5F9] text-xs md:text-sm lg:text-base">
                        @foreach ($transactions as $tx)
                            <tr class="hover:bg-[#F4F5F9]/60">
                                <td class="py-2 lg:py-3 pr-2.5 whitespace-nowrap text-[11px] lg:text-sm text-[#7E869E]">{{ $tx->date->format('d/m/Y') }}</td>
                                <td class="py-2 lg:py-3 px-2.5 font-semibold text-[#1A1D20]">{{ $tx->category?->name ?? '-' }}</td>
                                <td class="py-2 lg:py-3 px-2.5 text-[#7E869E]">{{ $tx->description ?: '-' }}</td>
                                <td class="py-2 lg:py-3 px-2.5"><x-badge-type :type="$tx->type" /></td>
                                <td class="py-2 lg:py-3 px-2.5 text-right font-bold {{ $tx->type === 'income' ? 'text-[#2ED47A]' : 'text-[#FF6B8B]' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}{{ $tx->formatted_amount }}
                                </td>
                                <td class="py-2 lg:py-3 pl-2.5 text-right whitespace-nowrap">
                                    <a href="{{ route('transactions.show', $tx) }}" class="px-3 py-1 lg:px-3.5 lg:py-1.5 rounded-full bg-[#EEEDFE] text-xs lg:text-sm font-medium text-[#5D56F3] hover:opacity-85">
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
