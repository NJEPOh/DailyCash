<x-layouts.app title="Tambah Rekening/Dompet">
    <x-page-header
        title="Tambah Rekening atau Dompet"
        subtitle="Buat akun penyimpanan dana baru dengan nilai saldo awal default Rp0."
        :backUrl="route('accounts.index')"
        backText="Kembali ke Rekening & Dompet"
    />

    <div class="max-w-xl lg:max-w-2xl bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-3.5 sm:p-5 lg:p-6 shadow-2xs">
        <form action="{{ route('accounts.store') }}" method="POST" class="space-y-3.5 lg:space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-2.5 sm:gap-3.5 lg:gap-4 xs-single-col">
                <div>
                    <label for="name" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-slate-700 mb-1 lg:mb-1.5">Nama Rekening / Dompet <span class="text-rose-500">*</span></label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        maxlength="50"
                        required
                        value="{{ old('name') }}"
                        placeholder="Contoh: Rekening BCA, OVO"
                        class="w-full rounded-lg lg:rounded-xl border border-slate-300 px-3 py-2 lg:px-3.5 lg:py-2.5 text-xs md:text-sm lg:text-base focus:border-slate-900 focus:outline-none"
                    >
                    @error('name')
                        <p class="text-[11px] lg:text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="balance" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-slate-700 mb-1 lg:mb-1.5">Saldo Awal (Rp)</label>
                    <input
                        type="number"
                        id="balance"
                        name="balance"
                        min="0"
                        step="1"
                        value="{{ old('balance', 0) }}"
                        class="w-full rounded-lg lg:rounded-xl border border-slate-300 px-3 py-2 lg:px-3.5 lg:py-2.5 text-xs md:text-sm lg:text-base focus:border-slate-900 focus:outline-none"
                    >
                    @error('balance')
                        <p class="text-[11px] lg:text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <p class="text-[11px] lg:text-sm text-slate-500">Nilai bawaan saldo awal adalah 0. Saldo selanjutnya otomatis bertambah/berkurang dari transaksi.</p>

            <div class="flex items-center justify-end gap-2 lg:gap-3 pt-2.5 lg:pt-3.5 border-t border-slate-100">
                <a href="{{ route('accounts.index') }}" class="px-3.5 py-1.5 lg:px-4 lg:py-2.5 rounded-lg lg:rounded-xl border border-slate-300 text-xs lg:text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-1.5 lg:px-5 lg:py-2.5 rounded-lg lg:rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs lg:text-sm font-semibold transition cursor-pointer">
                    Simpan Rekening
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
