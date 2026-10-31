<x-layouts.app :title="'Detail Kategori: ' . $category->name">
    <x-page-header
        :title="'Kategori: ' . $category->name"
        subtitle="Detail informasi kategori dan riwayat transaksi terkait."
        :backUrl="route('categories.index')"
        backText="Kembali ke Daftar Kategori"
    >
        <a href="{{ route('categories.edit', $category) }}" class="px-4 py-2 rounded-xl border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50">
            Edit Kategori
        </a>
    </x-page-header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tipe Kategori</p>
            <div class="mt-2">
                <x-badge-type :type="$category->type" />
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Jumlah Transaksi</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $category->transactions_count }} Transaksi</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Akumulasi Nominal</p>
            <p class="text-2xl font-bold {{ $category->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }} mt-1">
                Rp{{ number_format($totalAmount, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
        <h2 class="text-base font-bold text-slate-900">Riwayat Transaksi Kategori Ini</h2>

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
                        <tr class="border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="py-3 pr-3">Tanggal</th>
                            <th class="py-3 px-3">Rekening</th>
                            <th class="py-3 px-3">Deskripsi</th>
                            <th class="py-3 pl-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach ($transactions as $tx)
                            <tr class="hover:bg-slate-50/80">
                                <td class="py-3 pr-3 whitespace-nowrap text-xs text-slate-500">{{ $tx->date->format('d/m/Y') }}</td>
                                <td class="py-3 px-3 font-medium text-slate-800">{{ $tx->account?->name ?? '-' }}</td>
                                <td class="py-3 px-3 text-slate-600">
                                    <a href="{{ route('transactions.show', $tx) }}" class="hover:underline">
                                        {{ $tx->description ?: 'Tanpa deskripsi' }}
                                    </a>
                                </td>
                                <td class="py-3 pl-3 text-right font-bold {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $tx->formatted_amount }}
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
