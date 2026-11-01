<x-layouts.app title="Edit Rekening/Dompet">
    <x-page-header
        title="Ubah Rekening: {{ $account->name }}"
        subtitle="Perbarui nama rekening atau dompet."
        :backUrl="route('accounts.index')"
        backText="Kembali ke Rekening & Dompet"
    />

    <div class="max-w-xl lg:max-w-2xl bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-3.5 sm:p-5 lg:p-6 shadow-2xs">
        <form action="{{ route('accounts.update', $account) }}" method="POST" class="space-y-3.5 lg:space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-slate-700 mb-1 lg:mb-1.5">Nama Rekening / Dompet <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    maxlength="50"
                    required
                    value="{{ old('name', $account->name) }}"
                    class="w-full rounded-lg lg:rounded-xl border border-slate-300 px-3 py-2 lg:px-3.5 lg:py-2.5 text-xs md:text-sm lg:text-base focus:border-slate-900 focus:outline-none"
                >
                @error('name')
                    <p class="text-[11px] lg:text-sm text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-2.5 lg:gap-4 text-[11px] lg:text-sm text-slate-600 xs-single-col">
                <div class="p-2.5 lg:p-3.5 rounded-lg lg:rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-slate-400 block">Saldo Saat Ini</span>
                    <strong class="text-xs md:text-sm lg:text-base text-slate-900">{{ $account->formatted_balance }}</strong>
                </div>
                <div class="p-2.5 lg:p-3.5 rounded-lg lg:rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-slate-400 block">Kebijakan Saldo</span>
                    <span class="text-slate-700">Otomatis dari transaksi</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 lg:gap-3 pt-2.5 lg:pt-3.5 border-t border-slate-100">
                <a href="{{ route('accounts.index') }}" class="px-3.5 py-1.5 lg:px-4 lg:py-2.5 rounded-lg lg:rounded-xl border border-slate-300 text-xs lg:text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-1.5 lg:px-5 lg:py-2.5 rounded-lg lg:rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs lg:text-sm font-semibold transition cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
