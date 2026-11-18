<x-layouts.app title="Daftar Transaksi">
    <x-page-header
        title="Daftar"
        accent="Transaksi"
        subtitle="Kelola seluruh catatan pemasukan dan pengeluaran harian beserta filter pencarian."
    >
        <a href="{{ route('transactions.create') }}" class="btn-bento-dark">
            <span>+ Tambah Transaksi</span>
        </a>
    </x-page-header>

    {{-- Ringkasan Hasil Filter (Bento Cards) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3.5 xs-single-col">
        <x-stat-card
            title="Pemasukan Terfilter"
            :value="'Rp' . number_format($filteredIncome, 0, ',', '.')"
            subtitle="Akumulasi dana masuk"
            variant="income"
        >
            <x-slot:icon>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card
            title="Pengeluaran Terfilter"
            :value="'Rp' . number_format($filteredExpense, 0, ',', '.')"
            subtitle="Akumulasi dana keluar"
            variant="expense"
        >
            <x-slot:icon>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <div class="col-span-2 md:col-span-1">
            <x-stat-card
                title="Jumlah Data"
                :value="$filteredCount . ' Transaksi'"
                subtitle="Sesuai kriteria filter"
                variant="dark"
            >
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                </x-slot:icon>
            </x-stat-card>
        </div>
    </div>

    {{-- Panel Filter Transaksi --}}
    <div class="bento-card">
        <form action="{{ route('transactions.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 xs-single-col">
                <div class="col-span-2 md:col-span-3 lg:col-span-1">
                    <label for="q" class="block text-[11px] lg:text-[12px] font-semibold text-[#18191B] mb-1.5">Kata Kunci</label>
                    <input
                        type="search"
                        id="q"
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        placeholder="Cari deskripsi..."
                        class="input-field"
                    >
                </div>

                <div>
                    <label for="start_date" class="block text-[11px] lg:text-[12px] font-semibold text-[#18191B] mb-1.5">Tanggal Awal</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $filters['start_date'] }}"
                        class="input-field"
                    >
                </div>

                <div>
                    <label for="end_date" class="block text-[11px] lg:text-[12px] font-semibold text-[#18191B] mb-1.5">Tanggal Akhir</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ $filters['end_date'] }}"
                        class="input-field"
                    >
                </div>

                <div>
                    <label for="type" class="block text-[11px] lg:text-[12px] font-semibold text-[#18191B] mb-1.5">Tipe Transaksi</label>
                    <select id="type" name="type" class="input-field">
                        <option value="">Semua Tipe</option>
                        <option value="income" @selected($filters['type'] === 'income')>Pemasukan (Income)</option>
                        <option value="expense" @selected($filters['type'] === 'expense')>Pengeluaran (Expense)</option>
                    </select>
                </div>

                <div>
                    <label for="category_id" class="block text-[11px] lg:text-[12px] font-semibold text-[#18191B] mb-1.5">Kategori</label>
                    <select id="category_id" name="category_id" class="input-field">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $filters['category_id'] === (string) $category->id)>
                                [{{ $category->type_label }}] {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="account_id" class="block text-[11px] lg:text-[12px] font-semibold text-[#18191B] mb-1.5">Rekening / Dompet</label>
                    <select id="account_id" name="account_id" class="input-field">
                        <option value="">Semua Rekening</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" @selected((string) $filters['account_id'] === (string) $account->id)>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2.5 pt-3 border-t border-[#ECECE9]">
                <span class="bento-meta">
                    Menampilkan <strong class="text-[#18191B]">{{ $filteredCount }}</strong> transaksi
                    @if (! empty($filters['q']))
                        untuk kata kunci "<strong class="text-[#18191B]">{{ $filters['q'] }}</strong>"
                    @endif
                </span>

                <div class="flex items-center gap-2">
                    <a href="{{ route('transactions.index') }}" class="btn-secondary">
                        Hapus Filter
                    </a>
                    <button type="submit" class="btn-bento-dark">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Daftar Transaksi Bento --}}
    <div class="bento-card space-y-4">
        @if ($transactions->isEmpty())
            <x-empty-state
                title="Tidak ada transaksi ditemukan"
                description="Belum ada transaksi yang tercatat atau tidak ada data yang cocok dengan filter pencarian Anda."
                :actionUrl="route('transactions.create')"
                actionText="+ Catat Transaksi Baru"
            />
        @else
            {{-- Mobile 2-Column Compact Cards --}}
            <div class="grid grid-cols-2 gap-2.5 md:hidden xs-single-col">
                @foreach ($transactions as $transaction)
                    <div class="bento-card-subtle !p-3 flex flex-col justify-between gap-2.5 min-w-0">
                        <div class="space-y-1.5 min-w-0">
                            <div class="flex items-center justify-between gap-1">
                                <span class="badge badge-neutral !text-[10px] !py-0.5 !px-2">{{ $transaction->date->format('d/m/y') }}</span>
                                <span class="badge {{ $transaction->type === 'income' ? 'badge-lime' : 'badge-coral' }} !text-[10px] !py-0.5 !px-2">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}
                                </span>
                            </div>
                            <a href="{{ route('transactions.show', $transaction) }}" class="bento-h3 block truncate">
                                {{ $transaction->category?->name ?? '-' }}
                            </a>
                            <p class="bento-meta truncate">{{ $transaction->account?->name ?? '-' }}</p>
                            <p class="text-[13px] font-bold {{ $transaction->type === 'income' ? 'text-[#4B6411]' : 'text-[#C23B3B]' }} truncate">
                                {{ $transaction->type === 'income' ? '+' : '-' }}{{ $transaction->formatted_amount }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between gap-1 pt-2 border-t border-[#ECECE9]">
                            <a href="{{ route('transactions.edit', $transaction) }}" class="badge badge-blue">Edit</a>
                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Dampak transaksi terhadap saldo rekening akan dibalikkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="badge badge-coral cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Desktop & Tablet Tabel / Assignment Rows Bento --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#ECECE9] bento-label">
                            <th class="py-3 pr-3">Tanggal</th>
                            <th class="py-3 px-3">Kategori & Deskripsi</th>
                            <th class="py-3 px-3">Rekening</th>
                            <th class="py-3 px-3">Tipe</th>
                            <th class="py-3 px-3 text-center">Bukti</th>
                            <th class="py-3 px-3 text-right">Nominal</th>
                            <th class="py-3 pl-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#ECECE9] text-[13px] lg:text-[14px]">
                        @foreach ($transactions as $transaction)
                            <tr class="hover:bg-[#F9F9F8] transition">
                                <td class="py-3.5 pr-3 whitespace-nowrap bento-meta">
                                    {{ $transaction->date->format('d M Y') }}
                                </td>
                                <td class="py-3.5 px-3 min-w-0">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="bento-squircle !w-9 !h-9 {{ $transaction->type === 'income' ? 'bg-[#ECF8C2] text-[#4B6411]' : 'bg-[#FCEAE8] text-[#C23B3B]' }}">
                                            @if ($transaction->type === 'income')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                                </svg>
                                            @endif
                                        </span>
                                        <div class="min-w-0">
                                            <a href="{{ route('transactions.show', $transaction) }}" class="font-semibold text-[#18191B] hover:underline block truncate">
                                                {{ $transaction->category?->name ?? '-' }}
                                            </a>
                                            <span class="bento-meta line-clamp-1">
                                                {{ $transaction->description ?: 'Tanpa deskripsi' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    <span class="badge badge-blue">{{ $transaction->account?->name ?? '-' }}</span>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    <x-badge-type :type="$transaction->type" />
                                </td>
                                <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                    @if ($transaction->proof_image)
                                        <a href="{{ route('transactions.show', $transaction) }}" class="badge badge-yellow">
                                            Ada Bukti
                                        </a>
                                    @else
                                        <span class="bento-meta">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap text-right font-bold {{ $transaction->type === 'income' ? 'text-[#4B6411]' : 'text-[#C23B3B]' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}{{ $transaction->formatted_amount }}
                                </td>
                                <td class="py-3.5 pl-3 whitespace-nowrap text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('transactions.show', $transaction) }}" class="badge badge-neutral hover:bg-[#DDF296] hover:text-[#18191B] transition">
                                            Detail
                                        </a>
                                        <a href="{{ route('transactions.edit', $transaction) }}" class="badge badge-blue hover:opacity-85 transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Dampak transaksi terhadap saldo rekening akan dibalikkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="badge badge-coral hover:opacity-85 cursor-pointer transition">
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

            <div class="pt-3 border-t border-[#ECECE9]">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
