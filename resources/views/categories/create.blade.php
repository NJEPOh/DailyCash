<x-layouts.app title="Tambah Kategori">
    <x-page-header
        title="Tambah Kategori Baru"
        subtitle="Tambahkan kategori pemasukan atau pengeluaran transaksi."
        :backUrl="route('categories.index')"
        backText="Kembali ke Daftar Kategori"
    />

    <div class="max-w-xl bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    maxlength="50"
                    required
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama kategori (maksimal 50 karakter)"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-slate-900 focus:outline-none"
                >
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="type" class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe Kategori <span class="text-rose-500">*</span></label>
                <select
                    id="type"
                    name="type"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm bg-white focus:border-slate-900 focus:outline-none"
                >
                    <option value="expense" @selected(old('type', 'expense') === 'expense')>Pengeluaran (Expense)</option>
                    <option value="income" @selected(old('type') === 'income')>Pemasukan (Income)</option>
                </select>
                @error('type')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <a href="{{ route('categories.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
