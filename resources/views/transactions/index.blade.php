<x-layouts.app title="Daftar Transaksi">
    <x-page-header
        title="Daftar Transaksi"
        subtitle="Kelola seluruh catatan pemasukan dan pengeluaran harian beserta filter pencarian."
    >
        <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 lg:px-4 lg:py-2 rounded-lg lg:rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs lg:text-sm font-semibold transition">
            <span>+ Tambah Transaksi</span>
        </a>
    </x-page-header>

    {{-- Ringkasan Hasil Filter (2 Kolom di Mobile) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-2.5 sm:gap-3.5 lg:gap-5 xs-single-col">
        <x-stat-card
            title="Pemasukan Terfilter"
            :value="'Rp' . number_format($filteredIncome, 0, ',', '.')"
            subtitle="Akumulasi dana masuk"
            variant="income"
        />
        <x-stat-card
            title="Pengeluaran Terfilter"
            :value="'Rp' . number_format($filteredExpense, 0, ',', '.')"
            subtitle="Akumulasi dana keluar"
            variant="expense"
        />
        <div class="col-span-2 md:col-span-1">
            <x-stat-card
                title="Jumlah Data"
                :value="$filteredCount . ' Transaksi'"
                subtitle="Sesuai kriteria filter"
                variant="primary"
            />
        </div>
    </div>

    {{-- Panel Filter Transaksi (2 Kolom di Mobile - Bagian 4.5) --}}
    <div class="bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-3.5 sm:p-4 lg:p-6 shadow-2xs">
        <form action="{{ route('transactions.index') }}" method="GET" class="space-y-3 lg:space-y-4">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2.5 lg:gap-3.5 xs-single-col">
                <div>
                    <label for="start_date" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-slate-600 mb-1 lg:mb-1.5">Tanggal Awal</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $filters['start_date'] }}"
                        class="w-full rounded-lg lg:rounded-xl border border-slate-300 px-2.5 py-1.5 lg:px-3.5 lg:py-2 text-xs md:text-sm lg:text-base focus:border-slate-900 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="end_date" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-slate-600 mb-1 lg:mb-1.5">Tanggal Akhir</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ $filters['end_date'] }}"
                        class="w-full rounded-lg lg:rounded-xl border border-slate-300 px-2.5 py-1.5 lg:px-3.5 lg:py-2 text-xs md:text-sm lg:text-base focus:border-slate-900 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="type" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-slate-600 mb-1 lg:mb-1.5">Tipe Transaksi</label>
                    <select
                        id="type"
                        name="type"
                        class="w-full rounded-lg lg:rounded-xl border border-slate-300 px-2.5 py-1.5 lg:px-3.5 lg:py-2 text-xs md:text-sm lg:text-base bg-white focus:border-slate-900 focus:outline-none"
                    >
                        <option value="">Semua Tipe</option>
                        <option value="income" @selected($filters['type'] === 'income')>Pemasukan (Income)</option>
                        <option value="expense" @selected($filters['type'] === 'expense')>Pengeluaran (Expense)</option>
                    </select>
                </div>

                <div>
                    <label for="category_id" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-slate-600 mb-1 lg:mb-1.5">Kategori</label>
                    <select
                        id="category_id"
                        name="category_id"
                        class="w-full rounded-lg lg:rounded-xl border border-slate-300 px-2.5 py-1.5 lg:px-3.5 lg:py-2 text-xs md:text-sm lg:text-base bg-white focus:border-slate-900 focus:outline-none"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $filters['category_id'] === (string) $category->id)>
                                [{{ $category->type_label }}] {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label for="account_id" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-slate-600 mb-1 lg:mb-1.5">Rekening / Dompet</label>
                    <select
                        id="account_id"
                        name="account_id"
                        class="w-full rounded-lg lg:rounded-xl border border-slate-300 px-2.5 py-1.5 lg:px-3.5 lg:py-2 text-xs md:text-sm lg:text-base bg-white focus:border-slate-900 focus:outline-none"
                    >
                        <option value="">Semua Rekening</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" @selected((string) $filters['account_id'] === (string) $account->id)>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2 pt-2.5 lg:pt-3.5 border-t border-slate-100">
                <span class="text-[11px] lg:text-sm text-slate-500">
                    Menampilkan <strong>{{ $filteredCount }}</strong> transaksi
                </span>

                <div class="flex items-center gap-1.5 lg:gap-2.5">
                    <a href="{{ route('transactions.index') }}" class="px-3 py-1.5 lg:px-4 lg:py-2 rounded-lg lg:rounded-xl border border-slate-300 text-xs lg:text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Hapus Filter
                    </a>
                    <button type="submit" class="px-3.5 py-1.5 lg:px-4 lg:py-2 rounded-lg lg:rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs lg:text-sm font-semibold transition cursor-pointer">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel Daftar Transaksi --}}
    <div class="bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-3.5 sm:p-4 lg:p-6 shadow-2xs space-y-3 lg:space-y-4">
        @if ($transactions->isEmpty())
            <x-empty-state
                title="Tidak ada transaksi ditemukan"
                description="Belum ada transaksi yang tercatat atau tidak ada data yang cocok dengan filter pencarian Anda."
                :actionUrl="route('transactions.create')"
                actionText="+ Catat Transaksi Baru"
            />
        @else
            <div class="overflow-x-auto -mx-3.5 sm:mx-0 px-3.5 sm:px-0">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-[11px] lg:text-sm font-semibold uppercase tracking-wider text-slate-400">
                            <th class="py-2 lg:py-3 pr-2">Tanggal</th>
                            <th class="py-2 lg:py-3 px-2">Kategori & Deskripsi</th>
                            <th class="py-2 lg:py-3 px-2 hidden sm:table-cell">Rekening</th>
                            <th class="py-2 lg:py-3 px-2">Tipe</th>
                            <th class="py-2 lg:py-3 px-2 text-center hidden md:table-cell">Bukti</th>
                            <th class="py-2 lg:py-3 px-2 text-right">Nominal</th>
                            <th class="py-2 lg:py-3 pl-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs md:text-sm lg:text-base">
                        @foreach ($transactions as $transaction)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-2.5 lg:py-3.5 pr-2 whitespace-nowrap text-[11px] lg:text-sm font-medium text-slate-600">
                                    {{ $transaction->date->format('d/m/Y') }}
                                </td>
                                <td class="py-2.5 lg:py-3.5 px-2 min-w-0">
                                    <a href="{{ route('transactions.show', $transaction) }}" class="font-semibold text-slate-900 hover:text-emerald-600 block">
                                        {{ $transaction->category?->name ?? '-' }}
                                    </a>
                                    <span class="text-[11px] lg:text-sm text-slate-500 line-clamp-1">
                                        {{ $transaction->description ?: 'Tanpa deskripsi' }}
                                    </span>
                                </td>
                                <td class="py-2.5 lg:py-3.5 px-2 whitespace-nowrap text-[11px] lg:text-sm font-semibold text-slate-700 hidden sm:table-cell">
                                    {{ $transaction->account?->name ?? '-' }}
                                </td>
                                <td class="py-2.5 lg:py-3.5 px-2 whitespace-nowrap">
                                    <x-badge-type :type="$transaction->type" />
                                </td>
                                <td class="py-2.5 lg:py-3.5 px-2 text-center whitespace-nowrap hidden md:table-cell">
                                    @if ($transaction->proof_image)
                                        <a href="{{ route('transactions.show', $transaction) }}" class="inline-flex items-center gap-1 px-2 py-0.5 lg:px-2.5 lg:py-1 rounded bg-sky-50 text-sky-700 border border-sky-200 text-[11px] lg:text-sm font-medium">
                                            Ada Bukti
                                        </a>
                                    @else
                                        <span class="text-[11px] lg:text-sm text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-2.5 lg:py-3.5 px-2 whitespace-nowrap text-right font-bold {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}{{ $transaction->formatted_amount }}
                                </td>
                                <td class="py-2.5 lg:py-3.5 pl-2 whitespace-nowrap text-right">
                                    <div class="inline-flex items-center gap-1 lg:gap-1.5">
                                        <a href="{{ route('transactions.show', $transaction) }}" class="px-2 py-1 lg:px-3 lg:py-1.5 rounded border border-slate-200 text-xs lg:text-sm font-medium text-slate-700 hover:bg-slate-100">
                                            Detail
                                        </a>
                                        <a href="{{ route('transactions.edit', $transaction) }}" class="px-2 py-1 lg:px-3 lg:py-1.5 rounded border border-slate-200 text-xs lg:text-sm font-medium text-slate-700 hover:bg-slate-100">
                                            Edit
                                        </a>
                                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Dampak transaksi terhadap saldo rekening akan dibalikkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 lg:px-3 lg:py-1.5 rounded border border-rose-200 text-xs lg:text-sm font-medium text-rose-600 hover:bg-rose-50 cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-3 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
