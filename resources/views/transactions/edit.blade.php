<x-layouts.app title="Edit Transaksi">
    <x-page-header
        :title="'Edit Transaksi'"
        :accent="'#' . $transaction->id"
        subtitle="Perubahan nominal, tipe, atau rekening akan menyesuaikan saldo lama dan saldo baru secara konsisten."
        :backUrl="route('transactions.index')"
        backText="Kembali ke Daftar Transaksi"
    />

    <div class="max-w-3xl bento-card">
        <form action="{{ route('transactions.update', $transaction) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            @include('transactions._form', ['transaction' => $transaction])

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-[#ECECE9]">
                <a href="{{ route('transactions.show', $transaction) }}" class="btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn-bento-dark">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
