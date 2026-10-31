<x-layouts.app title="Tambah Transaksi">
    <x-page-header
        title="Catat Transaksi Baru"
        subtitle="Tambahkan transaksi pemasukan atau pengeluaran. Saldo rekening akan diperbarui secara otomatis."
        :backUrl="route('transactions.index')"
        backText="Kembali ke Daftar Transaksi"
    />

    <div class="max-w-3xl bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
        <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            @include('transactions._form')

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('transactions.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
