<x-layouts.app title="Edit Kategori">
    <x-page-header
        title="Edit Kategori: {{ $category->name }}"
        subtitle="Perbarui nama atau tipe kategori transaksi."
        :backUrl="route('categories.index')"
        backText="Kembali ke Daftar Kategori"
    />

    <div class="max-w-xl lg:max-w-2xl bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-3.5 sm:p-5 lg:p-6 shadow-2xs">
        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-3.5 lg:space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-2.5 sm:gap-3.5 lg:gap-4 xs-single-col">
                <div>
                    <label for="name" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-slate-700 mb-1 lg:mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        maxlength="50"
                        required
                        value="{{ old('name', $category->name) }}"
                        class="w-full rounded-lg lg:rounded-xl border border-slate-300 px-3 py-2 lg:px-3.5 lg:py-2.5 text-xs md:text-sm lg:text-base focus:border-slate-900 focus:outline-none"
                    >
                    @error('name')
                        <p class="text-[11px] lg:text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-slate-700 mb-1 lg:mb-1.5">Tipe Kategori <span class="text-rose-500">*</span></label>
                    <select
                        id="type"
                        name="type"
                        required
                        class="w-full rounded-lg lg:rounded-xl border border-slate-300 px-3 py-2 lg:px-3.5 lg:py-2.5 text-xs md:text-sm lg:text-base bg-white focus:border-slate-900 focus:outline-none"
                    >
                        <option value="expense" @selected(old('type', $category->type) === 'expense')>Pengeluaran (Expense)</option>
                        <option value="income" @selected(old('type', $category->type) === 'income')>Pemasukan (Income)</option>
                    </select>
                    @error('type')
                        <p class="text-[11px] lg:text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if ($category->transactions_count > 0)
                <p class="text-[11px] lg:text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg lg:rounded-xl px-3 py-2 lg:px-4 lg:py-3">
                    Kategori ini sudah digunakan oleh <strong>{{ $category->transactions_count }} transaksi</strong>. Tipe kategori tidak dapat diubah selama masih memiliki transaksi.
                </p>
            @endif

            <div class="flex items-center justify-end gap-2 lg:gap-3 pt-2.5 lg:pt-3.5 border-t border-slate-100">
                <a href="{{ route('categories.index') }}" class="px-3.5 py-1.5 lg:px-4 lg:py-2.5 rounded-lg lg:rounded-xl border border-slate-300 text-xs lg:text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-1.5 lg:px-5 lg:py-2.5 rounded-lg lg:rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs lg:text-sm font-semibold transition cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
