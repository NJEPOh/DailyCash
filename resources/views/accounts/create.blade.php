<x-layouts.app title="Tambah Rekening/Dompet">
    <x-page-header
        title="Tambah Rekening"
        accent="atau Dompet"
        subtitle="Buat akun penyimpanan dana baru dengan nilai saldo awal default Rp0."
        :backUrl="route('accounts.index')"
        backText="Kembali ke Rekening & Dompet"
    />

    <div class="max-w-2xl bento-card">
        <form action="{{ route('accounts.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-3 lg:gap-4 xs-single-col">
                <div>
                    <label for="name" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
                        Nama Rekening / Dompet <span class="text-[#C23B3B]">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        maxlength="50"
                        required
                        value="{{ old('name') }}"
                        placeholder="Contoh: Rekening BCA, OVO"
                        class="input-field"
                    >
                    @error('name')
                        <p class="text-[11px] lg:text-[12px] font-semibold text-[#C23B3B] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="balance" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
                        Saldo Awal (Rp)
                    </label>
                    <input
                        type="number"
                        id="balance"
                        name="balance"
                        min="0"
                        step="1"
                        value="{{ old('balance', 0) }}"
                        class="input-field"
                    >
                    @error('balance')
                        <p class="text-[11px] lg:text-[12px] font-semibold text-[#C23B3B] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <p class="bento-meta">Nilai bawaan saldo awal adalah 0. Saldo selanjutnya otomatis bertambah atau berkurang dari transaksi.</p>

            <div class="flex items-center justify-end gap-2.5 pt-3.5 border-t border-[#ECECE9]">
                <a href="{{ route('accounts.index') }}" class="btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn-bento-dark">
                    Simpan Rekening
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
