<x-layouts.app title="Edit Kategori">
    <x-page-header
        title="Edit Kategori:"
        :accent="$category->name"
        subtitle="Perbarui nama atau tipe kategori transaksi."
        :backUrl="route('categories.index')"
        backText="Kembali ke Daftar Kategori"
    />

    <div class="max-w-2xl bento-card">
        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

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
                        value="{{ old('name', $category->name) }}"
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
                        <option value="expense" @selected(old('type', $category->type) === 'expense')>Pengeluaran (Expense)</option>
                        <option value="income" @selected(old('type', $category->type) === 'income')>Pemasukan (Income)</option>
                    </select>
                    @error('type')
                        <p class="text-[11px] lg:text-[12px] font-semibold text-[#C23B3B] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if ($category->transactions_count > 0)
                <div class="rounded-[16px] bg-[#FDF3D7] px-4 py-3 text-[12px] lg:text-[13px] text-[#18191B]">
                    Kategori ini sudah digunakan oleh <strong>{{ $category->transactions_count }} transaksi</strong>. Tipe kategori tidak dapat diubah selama masih memiliki transaksi.
                </div>
            @endif

            <div class="flex items-center justify-end gap-2.5 pt-3.5 border-t border-[#ECECE9]">
                <a href="{{ route('categories.index') }}" class="btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn-bento-dark">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
