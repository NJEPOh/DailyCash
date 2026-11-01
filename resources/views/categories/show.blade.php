<x-layouts.app :title="'Detail Kategori: ' . $category->name">
    <x-page-header
        :title="'Kategori: ' . $category->name"
        subtitle="Detail informasi kategori dan riwayat transaksi terkait."
        :backUrl="route('categories.index')"
        backText="Kembali ke Daftar Kategori"
    >
        <a href="{{ route('categories.edit', $category) }}" class="px-3 py-1.5 lg:px-4 lg:py-2 rounded-lg lg:rounded-xl border border-slate-300 bg-white text-xs lg:text-sm font-medium text-slate-700 hover:bg-slate-50">
            Edit Kategori
        </a>
    </x-page-header>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-2.5 sm:gap-3.5 lg:gap-5 xs-single-col">
        <div class="bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-3 sm:p-4 lg:p-5 shadow-2xs">
            <p class="text-[11px] lg:text-sm font-semibold uppercase tracking-wide text-slate-400">Tipe Kategori</p>
            <div class="mt-1.5 lg:mt-2">
                <x-badge-type :type="$category->type" />
            </div>
        </div>
        <div class="bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-3 sm:p-4 lg:p-5 shadow-2xs">
            <p class="text-[11px] lg:text-sm font-semibold uppercase tracking-wide text-slate-400">Jumlah Transaksi</p>
            <p class="text-[20px] sm:text-2xl lg:text-[30px] font-bold text-slate-900 mt-0.5">{{ $category->transactions_count }} Tx</p>
        </div>
        <div class="col-span-2 md:col-span-1 bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-3 sm:p-4 lg:p-5 shadow-2xs">
            <p class="text-[11px] lg:text-sm font-semibold uppercase tracking-wide text-slate-400">Total Akumulasi Nominal</p>
            <p class="text-[20px] sm:text-2xl lg:text-[30px] font-bold {{ $category->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }} mt-0.5">
                Rp{{ number_format($totalAmount, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-3.5 sm:p-4 lg:p-6 shadow-2xs space-y-3 lg:space-y-4">
        <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-slate-900">Riwayat Transaksi Kategori Ini</h2>

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
                        <tr class="border-b border-slate-200 text-[11px] lg:text-sm font-semibold uppercase tracking-wider text-slate-400">
                            <th class="py-2 lg:py-3 pr-2.5">Tanggal</th>
                            <th class="py-2 lg:py-3 px-2.5">Rekening</th>
                            <th class="py-2 lg:py-3 px-2.5">Deskripsi</th>
                            <th class="py-2 lg:py-3 pl-2.5 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs md:text-sm lg:text-base">
                        @foreach ($transactions as $tx)
                            <tr class="hover:bg-slate-50/80">
                                <td class="py-2 lg:py-3 pr-2.5 whitespace-nowrap text-[11px] lg:text-sm text-slate-500">{{ $tx->date->format('d/m/Y') }}</td>
                                <td class="py-2 lg:py-3 px-2.5 font-medium text-slate-800">{{ $tx->account?->name ?? '-' }}</td>
                                <td class="py-2 lg:py-3 px-2.5 text-slate-600">
                                    <a href="{{ route('transactions.show', $tx) }}" class="hover:underline">
                                        {{ $tx->description ?: 'Tanpa deskripsi' }}
                                    </a>
                                </td>
                                <td class="py-2 lg:py-3 pl-2.5 text-right font-bold {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $tx->formatted_amount }}
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
