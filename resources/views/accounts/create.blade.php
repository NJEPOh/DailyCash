<x-layouts.app title="Tambah Rekening/Dompet">
    <x-page-header
        title="Tambah Rekening atau Dompet"
        subtitle="Buat akun penyimpanan dana baru dengan nilai saldo awal default Rp0."
        :backUrl="route('accounts.index')"
        backText="Kembali ke Rekening & Dompet"
    />

    <div class="max-w-xl bg-white rounded-xl border border-slate-200 p-3.5 sm:p-5 shadow-2xs">
        <form action="{{ route('accounts.store') }}" method="POST" class="space-y-3.5">
            @csrf

            <div class="grid grid-cols-2 gap-2.5 sm:gap-3.5 xs-single-col">
                <div>
                    <label for="name" class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Rekening / Dompet <span class="text-rose-500">*</span></label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        maxlength="50"
                        required
                        value="{{ old('name') }}"
                        placeholder="Contoh: Rekening BCA, OVO"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:border-slate-900 focus:outline-none"
                    >
                    @error('name')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="balance" class="block text-[11px] font-semibold text-slate-700 mb-1">Saldo Awal (Rp)</label>
                    <input
                        type="number"
                        id="balance"
                        name="balance"
                        min="0"
                        step="1"
                        value="{{ old('balance', 0) }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:border-slate-900 focus:outline-none"
                    >
                    @error('balance')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <p class="text-[11px] text-slate-500">Nilai bawaan saldo awal adalah 0. Saldo selanjutnya otomatis bertambah/berkurang dari transaksi.</p>

            <div class="flex items-center justify-end gap-2 pt-2.5 border-t border-slate-100">
                <a href="{{ route('accounts.index') }}" class="px-3.5 py-1.5 rounded-lg border border-slate-300 text-xs font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
                    Simpan Rekening
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
