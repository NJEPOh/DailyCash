<x-layouts.app title="Tambah Transaksi">
    <x-page-header
        title="Catat Transaksi"
        accent="Baru"
        subtitle="Tambahkan transaksi pemasukan atau pengeluaran. Saldo rekening akan diperbarui secara otomatis."
        :backUrl="route('transactions.index')"
        backText="Kembali ke Daftar Transaksi"
    />

    <div class="max-w-3xl bento-card">
        <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            @include('transactions._form')

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-[#ECECE9]">
                <a href="{{ route('transactions.index') }}" class="btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn-bento-dark">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
