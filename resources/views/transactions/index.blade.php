<x-layouts.app title="Daftar Transaksi">
    <x-page-header
        title="Daftar Transaksi"
        subtitle="Kelola seluruh catatan pemasukan dan pengeluaran harian beserta filter pencarian."
    >
        <a href="{{ route('transactions.create') }}" class="btn-primary text-xs lg:text-sm">
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
    <div class="card">
        <form action="{{ route('transactions.index') }}" method="GET" class="space-y-3 lg:space-y-4">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2.5 lg:gap-3.5 xs-single-col">
                <div>
                    <label for="start_date" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Tanggal Awal</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $filters['start_date'] }}"
                        class="input-field text-xs md:text-sm lg:text-base"
                    >
                </div>

                <div>
                    <label for="end_date" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Tanggal Akhir</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ $filters['end_date'] }}"
                        class="input-field text-xs md:text-sm lg:text-base"
                    >
                </div>

                <div>
                    <label for="type" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Tipe Transaksi</label>
                    <select
                        id="type"
                        name="type"
                        class="input-field text-xs md:text-sm lg:text-base"
                    >
                        <option value="">Semua Tipe</option>
                        <option value="income" @selected($filters['type'] === 'income')>Pemasukan (Income)</option>
                        <option value="expense" @selected($filters['type'] === 'expense')>Pengeluaran (Expense)</option>
                    </select>
                </div>

                <div>
                    <label for="category_id" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Kategori</label>
                    <select
                        id="category_id"
                        name="category_id"
                        class="input-field text-xs md:text-sm lg:text-base"
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
                    <label for="account_id" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Rekening / Dompet</label>
                    <select
                        id="account_id"
                        name="account_id"
                        class="input-field text-xs md:text-sm lg:text-base"
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

            <div class="flex flex-wrap items-center justify-between gap-2 pt-2.5 lg:pt-3.5 border-t border-[#F4F5F9]">
                <span class="text-[11px] lg:text-sm text-[#7E869E]">
                    Menampilkan <strong class="text-[#1A1D20]">{{ $filteredCount }}</strong> transaksi
                </span>

                <div class="flex items-center gap-1.5 lg:gap-2.5">
                    <a href="{{ route('transactions.index') }}" class="btn-secondary text-xs lg:text-sm">
                        Hapus Filter
                    </a>
                    <button type="submit" class="btn-primary text-xs lg:text-sm">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel Daftar Transaksi --}}
    <div class="card space-y-3 lg:space-y-4">
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
                        <tr class="border-b border-[#F4F5F9] text-[11px] lg:text-sm font-semibold uppercase tracking-wider text-[#A3AABF]">
                            <th class="py-2 lg:py-3 pr-2">Tanggal</th>
                            <th class="py-2 lg:py-3 px-2">Kategori & Deskripsi</th>
                            <th class="py-2 lg:py-3 px-2 hidden sm:table-cell">Rekening</th>
                            <th class="py-2 lg:py-3 px-2">Tipe</th>
                            <th class="py-2 lg:py-3 px-2 text-center hidden md:table-cell">Bukti</th>
                            <th class="py-2 lg:py-3 px-2 text-right">Nominal</th>
                            <th class="py-2 lg:py-3 pl-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F4F5F9] text-xs md:text-sm lg:text-base">
                        @foreach ($transactions as $transaction)
                            <tr class="hover:bg-[#F4F5F9]/60 transition">
                                <td class="py-2.5 lg:py-3.5 pr-2 whitespace-nowrap text-[11px] lg:text-sm font-medium text-[#7E869E]">
                                    {{ $transaction->date->format('d/m/Y') }}
                                </td>
                                <td class="py-2.5 lg:py-3.5 px-2 min-w-0">
                                    <a href="{{ route('transactions.show', $transaction) }}" class="font-semibold text-[#1A1D20] hover:text-[#5D56F3] block">
                                        {{ $transaction->category?->name ?? '-' }}
                                    </a>
                                    <span class="text-[11px] lg:text-sm text-[#7E869E] line-clamp-1">
                                        {{ $transaction->description ?: 'Tanpa deskripsi' }}
                                    </span>
                                </td>
                                <td class="py-2.5 lg:py-3.5 px-2 whitespace-nowrap text-[11px] lg:text-sm font-semibold text-[#1A1D20] hidden sm:table-cell">
                                    {{ $transaction->account?->name ?? '-' }}
                                </td>
                                <td class="py-2.5 lg:py-3.5 px-2 whitespace-nowrap">
                                    <x-badge-type :type="$transaction->type" />
                                </td>
                                <td class="py-2.5 lg:py-3.5 px-2 text-center whitespace-nowrap hidden md:table-cell">
                                    @if ($transaction->proof_image)
                                        <a href="{{ route('transactions.show', $transaction) }}" class="badge badge-primary">
                                            Ada Bukti
                                        </a>
                                    @else
                                        <span class="text-[11px] lg:text-sm text-[#A3AABF]">-</span>
                                    @endif
                                </td>
                                <td class="py-2.5 lg:py-3.5 px-2 whitespace-nowrap text-right font-bold {{ $transaction->type === 'income' ? 'text-[#2ED47A]' : 'text-[#FF6B8B]' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}{{ $transaction->formatted_amount }}
                                </td>
                                <td class="py-2.5 lg:py-3.5 pl-2 whitespace-nowrap text-right">
                                    <div class="inline-flex items-center gap-1 lg:gap-1.5">
                                        <a href="{{ route('transactions.show', $transaction) }}" class="px-2.5 py-1 lg:px-3 lg:py-1.5 rounded-full bg-[#F4F5F9] hover:bg-[#EEEDFE] text-xs lg:text-sm font-medium text-[#1A1D20] hover:text-[#5D56F3]">
                                            Detail
                                        </a>
                                        <a href="{{ route('transactions.edit', $transaction) }}" class="px-2.5 py-1 lg:px-3 lg:py-1.5 rounded-full bg-[#EEEDFE] text-xs lg:text-sm font-medium text-[#5D56F3] hover:opacity-85">
                                            Edit
                                        </a>
                                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Dampak transaksi terhadap saldo rekening akan dibalikkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 lg:px-3 lg:py-1.5 rounded-full bg-[#FF6B8B]/12 text-xs lg:text-sm font-medium text-[#FF6B8B] hover:bg-[#FF6B8B]/20 cursor-pointer">
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

            <div class="pt-3 border-t border-[#F4F5F9]">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
