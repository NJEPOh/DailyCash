<x-layouts.app title="Edit Rekening/Dompet">
    <x-page-header
        title="Ubah Rekening: {{ $account->name }}"
        subtitle="Perbarui nama rekening atau dompet."
        :backUrl="route('accounts.index')"
        backText="Kembali ke Rekening & Dompet"
    />

    <div class="max-w-xl bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
        <form action="{{ route('accounts.update', $account) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Rekening / Dompet <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    maxlength="50"
                    required
                    value="{{ old('name', $account->name) }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-slate-900 focus:outline-none"
                >
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600">
                <p><strong>Saldo Saat Ini:</strong> {{ $account->formatted_balance }}</p>
                <p class="mt-1">Perubahan saldo hanya dapat dilakukan melalui pencatatan, pembaruan, atau penghapusan transaksi agar histori tetap konsisten.</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <a href="{{ route('accounts.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
