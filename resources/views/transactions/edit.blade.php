<x-layouts.app title="Edit Transaksi">
    <x-page-header
        :title="'Edit Transaksi #' . $transaction->id"
        subtitle="Perubahan nominal, tipe, atau rekening akan menyesuaikan saldo lama dan saldo baru secara konsisten."
        :backUrl="route('transactions.index')"
        backText="Kembali ke Daftar Transaksi"
    />

    <div class="max-w-3xl bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
        <form action="{{ route('transactions.update', $transaction) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            @include('transactions._form', ['transaction' => $transaction])

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('transactions.show', $transaction) }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
