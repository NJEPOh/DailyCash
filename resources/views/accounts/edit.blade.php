<x-layouts.app title="Edit Rekening/Dompet">
    <x-page-header
        title="Ubah Rekening:"
        :accent="$account->name"
        subtitle="Perbarui nama rekening atau dompet penyimpanan dana Anda."
        :backUrl="route('accounts.index')"
        backText="Kembali ke Rekening & Dompet"
    />

    <div class="max-w-2xl bento-card">
        <form action="{{ route('accounts.update', $account) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

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
                    value="{{ old('name', $account->name) }}"
                    class="input-field"
                >
                @error('name')
                    <p class="text-[11px] lg:text-[12px] font-semibold text-[#C23B3B] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-3 xs-single-col">
                <div class="p-3.5 rounded-[16px] bg-[#F9F9F8]">
                    <span class="bento-label block">Saldo Saat Ini</span>
                    <strong class="text-[15px] lg:text-[16px] text-[#18191B] mt-1 block">{{ $account->formatted_balance }}</strong>
                </div>
                <div class="p-3.5 rounded-[16px] bg-[#F9F9F8]">
                    <span class="bento-label block">Kebijakan Saldo</span>
                    <span class="text-[13px] lg:text-[14px] font-semibold text-[#18191B] mt-1 block">Otomatis dari transaksi</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3.5 border-t border-[#ECECE9]">
                <a href="{{ route('accounts.index') }}" class="btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn-bento-dark">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
