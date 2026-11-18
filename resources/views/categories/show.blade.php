<x-layouts.app :title="'Detail Kategori: ' . $category->name">
    <x-page-header
        title="Kategori:"
        :accent="$category->name"
        subtitle="Detail informasi kategori dan riwayat transaksi terkait."
        :backUrl="route('categories.index')"
        backText="Kembali ke Daftar Kategori"
    >
        <a href="{{ route('categories.edit', $category) }}" class="btn-secondary">
            Edit Kategori
        </a>
    </x-page-header>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-3.5 xs-single-col">
        <div class="bento-card flex flex-col justify-between gap-2">
            <p class="bento-label">Tipe Kategori</p>
            <div>
                <x-badge-type :type="$category->type" />
            </div>
        </div>
        <x-stat-card
            title="Jumlah Transaksi"
            :value="$category->transactions_count . ' Tx'"
            subtitle="Total tercatat"
            variant="default"
        />
        <div class="col-span-2 md:col-span-1">
            <x-stat-card
                title="Total Akumulasi Nominal"
                :value="'Rp' . number_format($totalAmount, 0, ',', '.')"
                :subtitle="$category->type === 'income' ? 'Akumulasi pemasukan' : 'Akumulasi pengeluaran'"
                :variant="$category->type === 'income' ? 'lime' : 'expense'"
            />
        </div>
    </div>

    <div class="bento-card space-y-4">
        <h2 class="bento-h2">Riwayat Transaksi Kategori Ini</h2>

        @if ($transactions->isEmpty())
            <x-empty-state
                title="Belum ada transaksi pada kategori ini"
                description="Transaksi yang menggunakan kategori ini akan muncul di sini."
                :actionUrl="route('transactions.create', ['type' => $category->type])"
                actionText="+ Catat Transaksi"
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#ECECE9] bento-label">
                            <th class="py-3 pr-3">Tanggal</th>
                            <th class="py-3 px-3">Rekening</th>
                            <th class="py-3 px-3">Deskripsi</th>
                            <th class="py-3 pl-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#ECECE9] text-[13px] lg:text-[14px]">
                        @foreach ($transactions as $tx)
                            <tr class="hover:bg-[#F9F9F8] transition">
                                <td class="py-3 pr-3 whitespace-nowrap bento-meta">{{ $tx->date->format('d/m/Y') }}</td>
                                <td class="py-3 px-3">
                                    <span class="badge badge-blue">{{ $tx->account?->name ?? '-' }}</span>
                                </td>
                                <td class="py-3 px-3 text-[#18191B]">
                                    <a href="{{ route('transactions.show', $tx) }}" class="hover:underline font-medium">
                                        {{ $tx->description ?: 'Tanpa deskripsi' }}
                                    </a>
                                </td>
                                <td class="py-3 pl-3 text-right font-bold {{ $tx->type === 'income' ? 'text-[#4B6411]' : 'text-[#C23B3B]' }}">
                                    {{ $tx->formatted_amount }}
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
