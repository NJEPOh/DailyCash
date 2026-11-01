<x-layouts.app title="Tambah Rekening/Dompet">
    <x-page-header
        title="Tambah Rekening atau Dompet"
        subtitle="Buat akun penyimpanan dana baru dengan nilai saldo awal default Rp0."
        :backUrl="route('accounts.index')"
        backText="Kembali ke Rekening & Dompet"
    />

    <div class="max-w-xl lg:max-w-2xl card">
        <form action="{{ route('accounts.store') }}" method="POST" class="space-y-3.5 lg:space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-2.5 sm:gap-3.5 lg:gap-4 xs-single-col">
                <div>
                    <label for="name" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Nama Rekening / Dompet <span class="text-[#FF6B8B]">*</span></label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        maxlength="50"
                        required
                        value="{{ old('name') }}"
                        placeholder="Contoh: Rekening BCA, OVO"
                        class="input-field text-xs md:text-sm lg:text-base"
                    >
                    @error('name')
                        <p class="text-[11px] lg:text-sm text-[#FF6B8B] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="balance" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Saldo Awal (Rp)</label>
                    <input
                        type="number"
                        id="balance"
                        name="balance"
                        min="0"
                        step="1"
                        value="{{ old('balance', 0) }}"
                        class="input-field text-xs md:text-sm lg:text-base"
                    >
                    @error('balance')
                        <p class="text-[11px] lg:text-sm text-[#FF6B8B] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <p class="text-[11px] lg:text-sm text-[#7E869E]">Nilai bawaan saldo awal adalah 0. Saldo selanjutnya otomatis bertambah/berkurang dari transaksi.</p>

            <div class="flex items-center justify-end gap-2 lg:gap-3 pt-2.5 lg:pt-3.5 border-t border-[#F4F5F9]">
                <a href="{{ route('accounts.index') }}" class="btn-secondary text-xs lg:text-sm">
                    Batal
                </a>
                <button type="submit" class="btn-primary text-xs lg:text-sm">
                    Simpan Rekening
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
