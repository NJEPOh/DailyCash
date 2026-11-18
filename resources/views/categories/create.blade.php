<x-layouts.app title="Tambah Kategori">
    <x-page-header
        title="Tambah Kategori"
        accent="Baru"
        subtitle="Tambahkan kategori pemasukan atau pengeluaran transaksi."
        :backUrl="route('categories.index')"
        backText="Kembali ke Daftar Kategori"
    />

    <div class="max-w-2xl bento-card">
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-3 lg:gap-4 xs-single-col">
                <div>
                    <label for="name" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
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
                    @error('name')
                        <p class="text-[11px] lg:text-[12px] font-semibold text-[#C23B3B] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
                        Tipe Kategori <span class="text-[#C23B3B]">*</span>
                    </label>
                    <select id="type" name="type" required class="input-field">
                        <option value="expense" @selected(old('type', 'expense') === 'expense')>Pengeluaran (Expense)</option>
                        <option value="income" @selected(old('type') === 'income')>Pemasukan (Income)</option>
                    </select>
                    @error('type')
                        <p class="text-[11px] lg:text-[12px] font-semibold text-[#C23B3B] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3.5 border-t border-[#ECECE9]">
                <a href="{{ route('categories.index') }}" class="btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn-bento-dark">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
