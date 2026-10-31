<x-layouts.app title="Kategori Transaksi">
    <x-page-header
        title="Kategori Transaksi"
        subtitle="Kelola master kategori pemasukan (income) dan pengeluaran (expense)."
    >
        <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition">
            <span>+ Tambah Kategori</span>
        </a>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Tambah Kategori Cepat --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs h-fit space-y-4">
            <div>
                <h2 class="text-base font-bold text-slate-900">Tambah Kategori Baru</h2>
                <p class="text-xs text-slate-500">Buat kategori untuk pengelompokan transaksi</p>
            </div>

            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        maxlength="50"
                        required
                        value="{{ old('name') }}"
                        placeholder="Contoh: Gaji, Makan & Minum"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-slate-900 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="type" class="block text-xs font-semibold text-slate-700 mb-1">Tipe Kategori <span class="text-rose-500">*</span></label>
                    <select
                        id="type"
                        name="type"
                        required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm bg-white focus:border-slate-900 focus:outline-none"
                    >
                        <option value="expense" @selected(old('type', 'expense') === 'expense')>Pengeluaran (Expense)</option>
                        <option value="income" @selected(old('type') === 'income')>Pemasukan (Income)</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-semibold text-sm transition">
                    Simpan Kategori
                </button>
            </form>
        </div>

        {{-- Daftar Kategori --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Daftar Kategori ({{ $categories->count() }})</h2>
                    <p class="text-xs text-slate-500">{{ $incomeCount }} Pemasukan &bull; {{ $expenseCount }} Pengeluaran</p>
                </div>

                <div class="inline-flex rounded-xl bg-slate-100 p-1 text-xs font-semibold">
                    <a href="{{ route('categories.index') }}"
                       class="px-3 py-1.5 rounded-lg transition {{ ! $typeFilter ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Semua
                    </a>
                    <a href="{{ route('categories.index', ['type' => 'income']) }}"
                       class="px-3 py-1.5 rounded-lg transition {{ $typeFilter === 'income' ? 'bg-white text-emerald-700 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Pemasukan
                    </a>
                    <a href="{{ route('categories.index', ['type' => 'expense']) }}"
                       class="px-3 py-1.5 rounded-lg transition {{ $typeFilter === 'expense' ? 'bg-white text-rose-700 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
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
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                <th class="py-3 pr-3">Nama Kategori</th>
                                <th class="py-3 px-3">Tipe</th>
                                <th class="py-3 px-3 text-center">Transaksi</th>
                                <th class="py-3 px-3 text-right">Total Akumulasi</th>
                                <th class="py-3 pl-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3.5 pr-3 font-semibold text-slate-900">
                                        <a href="{{ route('categories.show', $category) }}" class="hover:text-emerald-600">
                                            {{ $category->name }}
                                        </a>
                                    </td>
                                    <td class="py-3.5 px-3">
                                        <x-badge-type :type="$category->type" />
                                    </td>
                                    <td class="py-3.5 px-3 text-center text-xs text-slate-600">
                                        {{ $category->transactions_count }}
                                    </td>
                                    <td class="py-3.5 px-3 text-right font-semibold text-slate-800">
                                        Rp{{ number_format((int) ($category->transactions_sum_amount ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 pl-3 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('categories.show', $category) }}" class="px-2.5 py-1 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-100">
                                                Detail
                                            </a>
                                            <a href="{{ route('categories.edit', $category) }}" class="px-2.5 py-1 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-100">
                                                Edit
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ addslashes($category->name) }}?');">
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
            @endif
        </div>
    </div>
</x-layouts.app>
