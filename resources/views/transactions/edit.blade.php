<x-layouts.app title="Edit Transaksi">
    <x-page-header
        :title="'Edit Transaksi #' . $transaction->id"
        subtitle="Perubahan nominal, tipe, atau rekening akan menyesuaikan saldo lama dan saldo baru secara konsisten."
        :backUrl="route('transactions.index')"
        backText="Kembali ke Daftar Transaksi"
    />

    <div class="max-w-2xl lg:max-w-3xl bg-white rounded-xl lg:rounded-2xl border border-slate-200 p-3.5 sm:p-5 lg:p-6 shadow-2xs">
        <form action="{{ route('transactions.update', $transaction) }}" method="POST" enctype="multipart/form-data" class="space-y-4 lg:space-y-5">
            @csrf
            @method('PUT')

            @include('transactions._form', ['transaction' => $transaction])

            <div class="flex items-center justify-end gap-2 lg:gap-3 pt-3 lg:pt-4 border-t border-slate-100">
                <a href="{{ route('transactions.show', $transaction) }}" class="px-3.5 py-1.5 lg:px-4 lg:py-2.5 rounded-lg lg:rounded-xl border border-slate-300 text-xs lg:text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-1.5 lg:px-5 lg:py-2.5 rounded-lg lg:rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs lg:text-sm font-semibold transition cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
