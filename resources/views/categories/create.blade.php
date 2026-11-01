<x-layouts.app title="Tambah Kategori">
    <x-page-header
        title="Tambah Kategori Baru"
        subtitle="Tambahkan kategori pemasukan atau pengeluaran transaksi."
        :backUrl="route('categories.index')"
        backText="Kembali ke Daftar Kategori"
    />

    <div class="max-w-xl bg-white rounded-xl border border-slate-200 p-3.5 sm:p-5 shadow-2xs">
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-3.5">
            @csrf

            <div class="grid grid-cols-2 gap-2.5 sm:gap-3.5 xs-single-col">
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
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:border-slate-900 focus:outline-none"
                    >
                    @error('name')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-[11px] font-semibold text-slate-700 mb-1">Tipe Kategori <span class="text-rose-500">*</span></label>
                    <select
                        id="type"
                        name="type"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs bg-white focus:border-slate-900 focus:outline-none"
                    >
                        <option value="expense" @selected(old('type', 'expense') === 'expense')>Pengeluaran (Expense)</option>
                        <option value="income" @selected(old('type') === 'income')>Pemasukan (Income)</option>
                    </select>
                    @error('type')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2.5 border-t border-slate-100">
                <a href="{{ route('categories.index') }}" class="px-3.5 py-1.5 rounded-lg border border-slate-300 text-xs font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
