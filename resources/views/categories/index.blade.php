<x-layouts.app title="Kategori Transaksi">
    <x-page-header
        title="Kategori Transaksi"
        subtitle="Kelola master kategori pemasukan (income) dan pengeluaran (expense)."
    >
        <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
            <span>+ Tambah Kategori</span>
        </a>
    </x-page-header>

    {{-- Ringkasan Statistik Kategori (2 Kolom di Mobile) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-2.5 sm:gap-3.5 xs-single-col">
        <x-stat-card
            title="Total Kategori"
            :value="($incomeCount + $expenseCount) . ' Kategori'"
            subtitle="Master pengelompokan"
            variant="primary"
        />
        <x-stat-card
            title="Kategori Pemasukan"
            :value="$incomeCount . ' Income'"
            subtitle="Sumber dana masuk"
            variant="income"
        />
        <div class="col-span-2 md:col-span-1">
            <x-stat-card
                title="Kategori Pengeluaran"
                :value="$expenseCount . ' Expense'"
                subtitle="Pos belanja & beban"
                variant="expense"
            />
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3.5 sm:gap-4">
        {{-- Form Tambah Kategori Cepat (2 Kolom di Mobile) --}}
        <div class="bg-white rounded-xl border border-slate-200 p-3.5 sm:p-4 shadow-2xs h-fit space-y-3">
            <div>
                <h2 class="text-sm sm:text-base font-bold text-slate-900">Tambah Kategori Cepat</h2>
                <p class="text-[11px] text-slate-500">Buat kategori baru untuk transaksi</p>
            </div>

            <form action="{{ route('categories.store') }}" method="POST" class="space-y-2.5">
                @csrf
                <div class="grid grid-cols-2 lg:grid-cols-1 gap-2.5 xs-single-col">
                    <div>
                        <label for="name" class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            maxlength="50"
                            required
                            value="{{ old('name') }}"
                            placeholder="Contoh: Gaji, Makan"
                            class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs focus:border-slate-900 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="type" class="block text-[11px] font-semibold text-slate-700 mb-1">Tipe Kategori <span class="text-rose-500">*</span></label>
                        <select
                            id="type"
                            name="type"
                            required
                            class="w-full rounded-lg border border-slate-300 px-2.5 py-1.5 text-xs bg-white focus:border-slate-900 focus:outline-none"
                        >
                            <option value="expense" @selected(old('type', 'expense') === 'expense')>Pengeluaran (Expense)</option>
                            <option value="income" @selected(old('type') === 'income')>Pemasukan (Income)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full py-2 px-3 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-semibold text-xs transition">
                    + Simpan Kategori
                </button>
            </form>
        </div>

        {{-- Daftar Kategori --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-3.5 sm:p-4 shadow-2xs space-y-3 min-w-0">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900">Daftar Kategori ({{ $categories->count() }})</h2>
                    <p class="text-[11px] text-slate-500">{{ $incomeCount }} Pemasukan &bull; {{ $expenseCount }} Pengeluaran</p>
                </div>

                <div class="inline-flex rounded-lg bg-slate-100 p-0.5 text-xs font-semibold">
                    <a href="{{ route('categories.index') }}"
                       class="px-2.5 py-1 rounded-md transition {{ ! $typeFilter ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Semua
                    </a>
                    <a href="{{ route('categories.index', ['type' => 'income']) }}"
                       class="px-2.5 py-1 rounded-md transition {{ $typeFilter === 'income' ? 'bg-white text-emerald-700 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Pemasukan
                    </a>
                    <a href="{{ route('categories.index', ['type' => 'expense']) }}"
                       class="px-2.5 py-1 rounded-md transition {{ $typeFilter === 'expense' ? 'bg-white text-rose-700 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Pengeluaran
                    </a>
                </div>
            </div>

            @if ($categories->isEmpty())
                <x-empty-state
                    title="Belum ada kategori"
                    description="Tambahkan kategori pemasukan atau pengeluaran terlebih dahulu."
                    :actionUrl="route('categories.create')"
                    actionText="+ Tambah Kategori"
                />
            @else
                {{-- Tampilan Mobile: Grid 2 Kolom Card Compact --}}
                <div class="grid grid-cols-2 gap-2.5 md:hidden xs-single-col">
                    @foreach ($categories as $category)
                        <div class="rounded-lg border border-slate-200 p-2.5 flex flex-col justify-between space-y-2 bg-slate-50/40 min-w-0">
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-start justify-between gap-1">
                                    <a href="{{ route('categories.show', $category) }}" class="text-xs font-bold text-slate-900 hover:text-emerald-600 truncate block">
                                        {{ $category->name }}
                                    </a>
                                </div>
                                <x-badge-type :type="$category->type" />
                                <p class="text-[11px] text-slate-500 pt-0.5">
                                    {{ $category->transactions_count }} transaksi
                                </p>
                                <p class="text-xs font-bold text-slate-800 truncate">
                                    Rp{{ number_format((int) ($category->transactions_sum_amount ?? 0), 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="flex items-center justify-between gap-1 pt-2 border-t border-slate-200/80">
                                <a href="{{ route('categories.edit', $category) }}" class="px-2 py-1 rounded border border-slate-200 bg-white text-[11px] font-medium text-slate-700 hover:bg-slate-100">
                                    Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori {{ addslashes($category->name) }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 rounded border border-rose-200 bg-white text-[11px] font-medium text-rose-600 hover:bg-rose-50">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Tampilan Tablet & Desktop: Tabel Compact --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                <th class="py-2 pr-2.5">Nama Kategori</th>
                                <th class="py-2 px-2.5">Tipe</th>
                                <th class="py-2 px-2.5 text-center">Transaksi</th>
                                <th class="py-2 px-2.5 text-right">Total Akumulasi</th>
                                <th class="py-2 pl-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-2.5 pr-2.5 font-semibold text-slate-900">
                                        <a href="{{ route('categories.show', $category) }}" class="hover:text-emerald-600">
                                            {{ $category->name }}
                                        </a>
                                    </td>
                                    <td class="py-2.5 px-2.5">
                                        <x-badge-type :type="$category->type" />
                                    </td>
                                    <td class="py-2.5 px-2.5 text-center text-[11px] text-slate-600">
                                        {{ $category->transactions_count }}
                                    </td>
                                    <td class="py-2.5 px-2.5 text-right font-semibold text-slate-800">
                                        Rp{{ number_format((int) ($category->transactions_sum_amount ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 pl-2.5 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1">
                                            <a href="{{ route('categories.show', $category) }}" class="px-2 py-1 rounded-md border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-100">
                                                Detail
                                            </a>
                                            <a href="{{ route('categories.edit', $category) }}" class="px-2 py-1 rounded-md border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-100">
                                                Edit
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ addslashes($category->name) }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 rounded-md border border-rose-200 text-xs font-medium text-rose-600 hover:bg-rose-50">
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
            @endif
        </div>
    </div>
</x-layouts.app>
