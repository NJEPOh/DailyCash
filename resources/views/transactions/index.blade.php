<x-layouts.app title="Daftar Transaksi">
    <x-page-header
        title="Daftar Transaksi"
        subtitle="Kelola seluruh catatan pemasukan dan pengeluaran harian beserta filter pencarian."
    >
        <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition">
            <span>+ Tambah Transaksi</span>
        </a>
    </x-page-header>

    {{-- Panel Filter Transaksi (Bagian 4.5) --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
        <form action="{{ route('transactions.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label for="start_date" class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Awal</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $filters['start_date'] }}"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="end_date" class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Akhir</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ $filters['end_date'] }}"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="type" class="block text-xs font-semibold text-slate-600 mb-1">Tipe Transaksi</label>
                    <select
                        id="type"
                        name="type"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm bg-white focus:border-slate-900 focus:outline-none"
                    >
                        <option value="">Semua Tipe</option>
                        <option value="income" @selected($filters['type'] === 'income')>Pemasukan (Income)</option>
                        <option value="expense" @selected($filters['type'] === 'expense')>Pengeluaran (Expense)</option>
                    </select>
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                    <select
                        id="category_id"
                        name="category_id"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm bg-white focus:border-slate-900 focus:outline-none"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $filters['category_id'] === (string) $category->id)>
                                [{{ $category->type_label }}] {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="account_id" class="block text-xs font-semibold text-slate-600 mb-1">Rekening / Dompet</label>
                    <select
                        id="account_id"
                        name="account_id"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm bg-white focus:border-slate-900 focus:outline-none"
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

            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100">
                <div class="text-xs text-slate-500 flex flex-wrap items-center gap-3">
                    <span>Menampilkan <strong>{{ $filteredCount }}</strong> transaksi</span>
                    <span class="text-emerald-600 font-semibold">Pemasukan: Rp{{ number_format($filteredIncome, 0, ',', '.') }}</span>
                    <span class="text-rose-600 font-semibold">Pengeluaran: Rp{{ number_format($filteredExpense, 0, ',', '.') }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('transactions.index') }}" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Hapus Filter
                    </a>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel Daftar Transaksi --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
        @if ($transactions->isEmpty())
            <x-empty-state
                title="Tidak ada transaksi ditemukan"
                description="Belum ada transaksi yang tercatat atau tidak ada data yang cocok dengan filter pencarian Anda."
                :actionUrl="route('transactions.create')"
                actionText="+ Catat Transaksi Baru"
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="py-3 pr-3">Tanggal</th>
                            <th class="py-3 px-3">Kategori & Deskripsi</th>
                            <th class="py-3 px-3">Rekening/Dompet</th>
                            <th class="py-3 px-3">Tipe</th>
                            <th class="py-3 px-3 text-center">Bukti</th>
                            <th class="py-3 px-3 text-right">Nominal</th>
                            <th class="py-3 pl-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach ($transactions as $transaction)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 pr-3 whitespace-nowrap text-xs font-medium text-slate-600">
                                    {{ $transaction->date->format('d/m/Y') }}
                                </td>
                                <td class="py-3.5 px-3">
                                    <a href="{{ route('transactions.show', $transaction) }}" class="font-semibold text-slate-900 hover:text-emerald-600 block">
                                        {{ $transaction->category?->name ?? '-' }}
                                    </a>
                                    <span class="text-xs text-slate-500 line-clamp-1">
                                        {{ $transaction->description ?: 'Tanpa deskripsi' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap text-xs font-semibold text-slate-700">
                                    {{ $transaction->account?->name ?? '-' }}
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    <x-badge-type :type="$transaction->type" />
                                </td>
                                <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                    @if ($transaction->proof_image)
                                        <a href="{{ route('transactions.show', $transaction) }}" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-sky-50 text-sky-700 border border-sky-200 text-[11px] font-medium">
                                            Ada Bukti
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap text-right font-bold {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}{{ $transaction->formatted_amount }}
                                </td>
                                <td class="py-3.5 pl-3 whitespace-nowrap text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('transactions.show', $transaction) }}" class="px-2.5 py-1 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-100">
                                            Detail
                                        </a>
                                        <a href="{{ route('transactions.edit', $transaction) }}" class="px-2.5 py-1 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-100">
                                            Edit
                                        </a>
                                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Dampak transaksi terhadap saldo rekening akan dibalikkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 rounded-lg border border-rose-200 text-xs font-medium text-rose-600 hover:bg-rose-50">
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

            <div class="pt-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
