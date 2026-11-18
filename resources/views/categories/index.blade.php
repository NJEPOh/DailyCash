<x-layouts.app title="Kategori Transaksi">
    <x-page-header
        title="Kategori"
        accent="Transaksi"
        subtitle="Kelola master kategori pemasukan (income) dan pengeluaran (expense)."
    >
        <a href="{{ route('categories.create') }}" class="btn-bento-dark">
            <span>+ Tambah Kategori</span>
        </a>
    </x-page-header>

    {{-- Ringkasan Statistik Kategori (Bento Cards) --}}
    @php
        $totalCategoriesCount = $incomeCount + $expenseCount;
        $incomePct = $totalCategoriesCount > 0 ? round(($incomeCount / $totalCategoriesCount) * 100) : 0;
        $expensePct = $totalCategoriesCount > 0 ? round(($expenseCount / $totalCategoriesCount) * 100) : 0;
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3.5 xs-single-col">
        <x-stat-card
            title="Total Kategori"
            :value="$totalCategoriesCount . ' Kategori'"
            subtitle="Master pengelompokan"
            variant="dark"
        />
        <x-stat-card
            title="Kategori Pemasukan"
            :value="$incomeCount . ' Income'"
            subtitle="Sumber dana masuk"
            variant="lime"
            :progress="$incomePct"
        />
        <div class="col-span-2 md:col-span-1">
            <x-stat-card
                title="Kategori Pengeluaran"
                :value="$expenseCount . ' Expense'"
                subtitle="Pos belanja & beban"
                variant="yellow"
                :progress="$expensePct"
            />
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Form Tambah Kategori Cepat --}}
        <div class="bento-card h-fit space-y-4">
            <div>
                <h2 class="bento-h2">Tambah Kategori Cepat</h2>
                <p class="bento-meta mt-0.5">Buat kategori baru untuk transaksi Anda</p>
            </div>

            <form action="{{ route('categories.store') }}" method="POST" class="space-y-3.5">
                @csrf
                <div class="grid grid-cols-2 lg:grid-cols-1 gap-3 xs-single-col">
                    <div>
                        <label for="name" class="block text-[12px] font-semibold text-[#18191B] mb-1.5">
                            Nama Kategori <span class="text-[#C23B3B]">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            maxlength="50"
                            required
                            value="{{ old('name') }}"
                            placeholder="Contoh: Gaji, Makan"
                            class="input-field"
                        >
                    </div>

                    <div>
                        <label for="type" class="block text-[12px] font-semibold text-[#18191B] mb-1.5">
                            Tipe Kategori <span class="text-[#C23B3B]">*</span>
                        </label>
                        <select id="type" name="type" required class="input-field">
                            <option value="expense" @selected(old('type', 'expense') === 'expense')>Pengeluaran (Expense)</option>
                            <option value="income" @selected(old('type') === 'income')>Pemasukan (Income)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-bento-dark w-full">
                    + Simpan Kategori
                </button>
            </form>
        </div>

        {{-- Daftar Kategori --}}
        <div class="lg:col-span-2 bento-card space-y-4 min-w-0">
            <div class="flex flex-wrap items-center justify-between gap-2.5">
                <div>
                    <h2 class="bento-h2">Daftar Kategori ({{ $categories->count() }})</h2>
                    <p class="bento-meta">{{ $incomeCount }} Pemasukan &bull; {{ $expenseCount }} Pengeluaran</p>
                </div>

                <div class="bento-switcher" role="group" aria-label="Filter Tipe Kategori">
                    <a href="{{ route('categories.index') }}"
                       class="bento-switcher-tab {{ ! $typeFilter ? 'active' : '' }}">
                        Semua
                    </a>
                    <a href="{{ route('categories.index', ['type' => 'income']) }}"
                       class="bento-switcher-tab {{ $typeFilter === 'income' ? 'active' : '' }}">
                        Pemasukan
                    </a>
                    <a href="{{ route('categories.index', ['type' => 'expense']) }}"
                       class="bento-switcher-tab {{ $typeFilter === 'expense' ? 'active' : '' }}">
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
                {{-- Mobile 2-Column Bento Cards --}}
                <div class="grid grid-cols-2 gap-2.5 md:hidden xs-single-col">
                    @foreach ($categories as $category)
                        <div class="bento-card-subtle !p-3 flex flex-col justify-between gap-2.5 min-w-0">
                            <div class="space-y-1.5 min-w-0">
                                <a href="{{ route('categories.show', $category) }}" class="bento-h3 block truncate">
                                    {{ $category->name }}
                                </a>
                                <x-badge-type :type="$category->type" />
                                <p class="bento-meta">{{ $category->transactions_count }} transaksi</p>
                                <p class="text-[13px] font-bold text-[#18191B] truncate">
                                    Rp{{ number_format((int) ($category->transactions_sum_amount ?? 0), 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="flex items-center justify-between gap-1 pt-2 border-t border-[#ECECE9]">
                                <a href="{{ route('categories.edit', $category) }}" class="badge badge-blue">
                                    Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori {{ addslashes($category->name) }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="badge badge-coral cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Tablet & Desktop Tabel Bento --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#ECECE9] bento-label">
                                <th class="py-3 pr-3">Nama Kategori</th>
                                <th class="py-3 px-3">Tipe</th>
                                <th class="py-3 px-3 text-center">Transaksi</th>
                                <th class="py-3 px-3 text-right">Total Akumulasi</th>
                                <th class="py-3 pl-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#ECECE9] text-[13px] lg:text-[14px]">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-[#F9F9F8] transition">
                                    <td class="py-3.5 pr-3 font-semibold text-[#18191B]">
                                        <a href="{{ route('categories.show', $category) }}" class="hover:underline">
                                            {{ $category->name }}
                                        </a>
                                    </td>
                                    <td class="py-3.5 px-3">
                                        <x-badge-type :type="$category->type" />
                                    </td>
                                    <td class="py-3.5 px-3 text-center">
                                        <span class="badge badge-neutral">{{ $category->transactions_count }}</span>
                                    </td>
                                    <td class="py-3.5 px-3 text-right font-bold text-[#18191B]">
                                        Rp{{ number_format((int) ($category->transactions_sum_amount ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 pl-3 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('categories.show', $category) }}" class="badge badge-neutral hover:bg-[#DDF296] hover:text-[#18191B] transition">
                                                Detail
                                            </a>
                                            <a href="{{ route('categories.edit', $category) }}" class="badge badge-blue hover:opacity-85 transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ addslashes($category->name) }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="badge badge-coral hover:opacity-85 transition cursor-pointer">
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
