<x-layouts.app title="Tambah Kategori">
    <x-page-header
        title="Tambah Kategori Baru"
        subtitle="Tambahkan kategori pemasukan atau pengeluaran transaksi."
        :backUrl="route('categories.index')"
        backText="Kembali ke Daftar Kategori"
    />

    <div class="max-w-xl lg:max-w-2xl card">
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-3.5 lg:space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-2.5 sm:gap-3.5 lg:gap-4 xs-single-col">
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
                    @error('name')
                        <p class="text-[11px] lg:text-sm text-[#FF6B8B] mt-1">{{ $message }}</p>
                    @enderror
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
                    @error('type')
                        <p class="text-[11px] lg:text-sm text-[#FF6B8B] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 lg:gap-3 pt-2.5 lg:pt-3.5 border-t border-[#F4F5F9]">
                <a href="{{ route('categories.index') }}" class="btn-secondary text-xs lg:text-sm">
                    Batal
                </a>
                <button type="submit" class="btn-primary text-xs lg:text-sm">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
