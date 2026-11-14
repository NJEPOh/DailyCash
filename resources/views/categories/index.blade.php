<x-layouts.app title="Kategori Transaksi">
    <x-page-header
        title="Kategori Transaksi"
        subtitle="Kelola master kategori pemasukan (income) dan pengeluaran (expense)."
    >
        <a href="{{ route('categories.create') }}" class="btn-primary text-xs lg:text-sm">
            <span>+ Tambah Kategori</span>
        </a>
    </x-page-header>

    {{-- Ringkasan Statistik Kategori (2 Kolom di Mobile) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-2.5 sm:gap-3.5 lg:gap-5 xs-single-col">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3.5 sm:gap-4 lg:gap-6">
        {{-- Form Tambah Kategori Cepat (2 Kolom di Mobile) --}}
        <div class="card h-fit space-y-3 lg:space-y-4">
            <div>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Tambah Kategori Cepat</h2>
                <p class="text-[11px] lg:text-sm text-[#7E869E]">Buat kategori baru untuk transaksi</p>
            </div>

            <form action="{{ route('categories.store') }}" method="POST" class="space-y-2.5 lg:space-y-3.5">
                @csrf
                <div class="grid grid-cols-2 lg:grid-cols-1 gap-2.5 lg:gap-3.5 xs-single-col">
                    <div>
                        <label for="name" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Nama Kategori <span class="text-[#FF6B8B]">*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            maxlength="50"
                            required
                            value="{{ old('name') }}"
                            placeholder="Contoh: Gaji, Makan"
                            class="input-field text-xs md:text-sm lg:text-base"
                        >
                    </div>

                    <div>
                        <label for="type" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Tipe Kategori <span class="text-[#FF6B8B]">*</span></label>
                        <select
                            id="type"
                            name="type"
                            required
                            class="input-field text-xs md:text-sm lg:text-base"
                        >
                            <option value="expense" @selected(old('type', 'expense') === 'expense')>Pengeluaran (Expense)</option>
                            <option value="income" @selected(old('type') === 'income')>Pemasukan (Income)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full text-xs lg:text-sm">
                    + Simpan Kategori
                </button>
            </form>
        </div>

        {{-- Daftar Kategori --}}
        <div class="lg:col-span-2 card space-y-3 lg:space-y-4 min-w-0">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Daftar Kategori ({{ $categories->count() }})</h2>
                    <p class="text-[11px] lg:text-sm text-[#7E869E]">{{ $incomeCount }} Pemasukan &bull; {{ $expenseCount }} Pengeluaran</p>
                </div>

                <div class="inline-flex rounded-full bg-[#ECFDF5] p-1 text-xs lg:text-sm font-semibold">
                    <a href="{{ route('categories.index') }}"
                       class="px-3 py-1 lg:px-3.5 lg:py-1.5 rounded-full transition {{ ! $typeFilter ? 'bg-[#059669] text-white shadow-xs' : 'text-[#64756B] hover:text-[#14251D]' }}">
                        Semua
                    </a>
                    <a href="{{ route('categories.index', ['type' => 'income']) }}"
                       class="px-3 py-1 lg:px-3.5 lg:py-1.5 rounded-full transition {{ $typeFilter === 'income' ? 'bg-[#2ED47A] text-white shadow-xs' : 'text-[#64756B] hover:text-[#14251D]' }}">
                        Pemasukan
                    </a>
                    <a href="{{ route('categories.index', ['type' => 'expense']) }}"
                       class="px-3 py-1 lg:px-3.5 lg:py-1.5 rounded-full transition {{ $typeFilter === 'expense' ? 'bg-[#FF6B8B] text-white shadow-xs' : 'text-[#64756B] hover:text-[#14251D]' }}">
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
                        <div class="rounded-[12px] p-3 flex flex-col justify-between space-y-2 bg-[#ECFDF5] min-w-0">
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-start justify-between gap-1">
                                    <a href="{{ route('categories.show', $category) }}" class="text-xs font-bold text-[#14251D] hover:text-[#059669] truncate block">
                                        {{ $category->name }}
                                    </a>
                                </div>
                                <x-badge-type :type="$category->type" />
                                <p class="text-[11px] text-[#64756B] pt-0.5">
                                    {{ $category->transactions_count }} transaksi
                                </p>
                                <p class="text-xs font-bold text-[#14251D] truncate">
                                    Rp{{ number_format((int) ($category->transactions_sum_amount ?? 0), 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="flex items-center justify-between gap-1 pt-2 border-t border-white">
                                <a href="{{ route('categories.edit', $category) }}" class="px-2.5 py-1 rounded-full bg-white text-xs font-medium text-[#059669] hover:bg-[#D1FAE5]">
                                    Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori {{ addslashes($category->name) }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 rounded-full bg-white text-xs font-medium text-[#FF6B8B] hover:bg-[#FF6B8B]/10">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Tampilan Tablet & Desktop: Tabel Responsif --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#D1FAE5] text-[11px] lg:text-sm font-semibold uppercase tracking-wider text-[#64756B]">
                                <th class="py-2 lg:py-3 pr-2.5">Nama Kategori</th>
                                <th class="py-2 lg:py-3 px-2.5">Tipe</th>
                                <th class="py-2 lg:py-3 px-2.5 text-center">Transaksi</th>
                                <th class="py-2 lg:py-3 px-2.5 text-right">Total Akumulasi</th>
                                <th class="py-2 lg:py-3 pl-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#D1FAE5] text-xs md:text-sm lg:text-base">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-[#ECFDF5]/60 transition">
                                    <td class="py-2.5 lg:py-3.5 pr-2.5 font-semibold text-[#14251D]">
                                        <a href="{{ route('categories.show', $category) }}" class="hover:text-[#059669]">
                                            {{ $category->name }}
                                        </a>
                                    </td>
                                    <td class="py-2.5 lg:py-3.5 px-2.5">
                                        <x-badge-type :type="$category->type" />
                                    </td>
                                    <td class="py-2.5 lg:py-3.5 px-2.5 text-center text-[11px] lg:text-sm text-[#64756B]">
                                        {{ $category->transactions_count }}
                                    </td>
                                    <td class="py-2.5 lg:py-3.5 px-2.5 text-right font-semibold text-[#14251D]">
                                        Rp{{ number_format((int) ($category->transactions_sum_amount ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 lg:py-3.5 pl-2.5 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('categories.show', $category) }}" class="px-3 py-1 lg:px-3.5 lg:py-1.5 rounded-full bg-[#ECFDF5] hover:bg-[#D1FAE5] text-xs lg:text-sm font-medium text-[#14251D] hover:text-[#059669] transition">
                                                Detail
                                            </a>
                                            <a href="{{ route('categories.edit', $category) }}" class="px-3 py-1 lg:px-3.5 lg:py-1.5 rounded-full bg-[#D1FAE5] text-xs lg:text-sm font-medium text-[#059669] hover:opacity-85 transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ addslashes($category->name) }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 lg:px-3.5 lg:py-1.5 rounded-full bg-[#FF6B8B]/12 text-xs lg:text-sm font-medium text-[#FF6B8B] hover:bg-[#FF6B8B]/20 transition cursor-pointer">
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
