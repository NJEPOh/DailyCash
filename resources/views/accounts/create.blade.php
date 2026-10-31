<x-layouts.app title="Tambah Rekening/Dompet">
    <x-page-header
        title="Tambah Rekening atau Dompet"
        subtitle="Buat akun penyimpanan dana baru dengan nilai saldo awal default Rp0."
        :backUrl="route('accounts.index')"
        backText="Kembali ke Rekening & Dompet"
    />

    <div class="max-w-xl bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
        <form action="{{ route('accounts.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Rekening / Dompet <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    maxlength="50"
                    required
                    value="{{ old('name') }}"
                    placeholder="Contoh: Dompet Utama, Rekening BCA, OVO, Tunai"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-slate-900 focus:outline-none"
                >
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="balance" class="block text-sm font-semibold text-slate-700 mb-1.5">Saldo Awal (Rp)</label>
                <input
                    type="number"
                    id="balance"
                    name="balance"
                    min="0"
                    step="1"
                    value="{{ old('balance', 0) }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-slate-900 focus:outline-none"
                >
                <p class="text-xs text-slate-500 mt-1">Nilai bawaan adalah 0. Saldo selanjutnya akan otomatis bertambah/berkurang dari transaksi.</p>
                @error('balance')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <a href="{{ route('accounts.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition">
                    Simpan Rekening
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
