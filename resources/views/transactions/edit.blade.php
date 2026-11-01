<x-layouts.app title="Edit Transaksi">
    <x-page-header
        :title="'Edit Transaksi #' . $transaction->id"
        subtitle="Perubahan nominal, tipe, atau rekening akan menyesuaikan saldo lama dan saldo baru secara konsisten."
        :backUrl="route('transactions.index')"
        backText="Kembali ke Daftar Transaksi"
    />

    <div class="max-w-2xl lg:max-w-3xl card">
        <form action="{{ route('transactions.update', $transaction) }}" method="POST" enctype="multipart/form-data" class="space-y-4 lg:space-y-5">
            @csrf
            @method('PUT')

            @include('transactions._form', ['transaction' => $transaction])

            <div class="flex items-center justify-end gap-2 lg:gap-3 pt-3 lg:pt-4 border-t border-[#F4F5F9]">
                <a href="{{ route('transactions.show', $transaction) }}" class="btn-secondary text-xs lg:text-sm">
                    Batal
                </a>
                <button type="submit" class="btn-primary text-xs lg:text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
