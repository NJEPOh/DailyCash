<x-layouts.app title="Tambah Transaksi">
    <x-page-header
        title="Catat Transaksi Baru"
        subtitle="Tambahkan transaksi pemasukan atau pengeluaran. Saldo rekening akan diperbarui secara otomatis."
        :backUrl="route('transactions.index')"
        backText="Kembali ke Daftar Transaksi"
    />

    <div class="max-w-2xl lg:max-w-3xl card">
        <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 lg:space-y-5">
            @csrf

            @include('transactions._form')

            <div class="flex items-center justify-end gap-2 lg:gap-3 pt-3 lg:pt-4 border-t border-[#F4F5F9]">
                <a href="{{ route('transactions.index') }}" class="btn-secondary text-xs lg:text-sm">
                    Batal
                </a>
                <button type="submit" class="btn-primary text-xs lg:text-sm">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
