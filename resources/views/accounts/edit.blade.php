<x-layouts.app title="Edit Rekening/Dompet">
    <x-page-header
        title="Ubah Rekening: {{ $account->name }}"
        subtitle="Perbarui nama rekening atau dompet."
        :backUrl="route('accounts.index')"
        backText="Kembali ke Rekening & Dompet"
    />

    <div class="max-w-xl lg:max-w-2xl card">
        <form action="{{ route('accounts.update', $account) }}" method="POST" class="space-y-3.5 lg:space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Nama Rekening / Dompet <span class="text-[#FF6B8B]">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    maxlength="50"
                    required
                    value="{{ old('name', $account->name) }}"
                    class="input-field text-xs md:text-sm lg:text-base"
                >
                @error('name')
                    <p class="text-[11px] lg:text-sm text-[#FF6B8B] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-2.5 lg:gap-4 text-[11px] lg:text-sm text-[#7E869E] xs-single-col">
                <div class="p-3 lg:p-3.5 rounded-[12px] bg-[#F4F5F9]">
                    <span class="text-[#A3AABF] block">Saldo Saat Ini</span>
                    <strong class="text-xs md:text-sm lg:text-base text-[#5D56F3]">{{ $account->formatted_balance }}</strong>
                </div>
                <div class="p-3 lg:p-3.5 rounded-[12px] bg-[#F4F5F9]">
                    <span class="text-[#A3AABF] block">Kebijakan Saldo</span>
                    <span class="text-[#1A1D20]">Otomatis dari transaksi</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 lg:gap-3 pt-2.5 lg:pt-3.5 border-t border-[#F4F5F9]">
                <a href="{{ route('accounts.index') }}" class="btn-secondary text-xs lg:text-sm">
                    Batal
                </a>
                <button type="submit" class="btn-primary text-xs lg:text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
