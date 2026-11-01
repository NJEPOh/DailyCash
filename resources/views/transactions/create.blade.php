<x-layouts.app title="Tambah Transaksi">
    <x-page-header
        title="Catat Transaksi Baru"
        subtitle="Tambahkan transaksi pemasukan atau pengeluaran. Saldo rekening akan diperbarui secara otomatis."
        :backUrl="route('transactions.index')"
        backText="Kembali ke Daftar Transaksi"
    />

    <div class="max-w-2xl bg-white rounded-xl border border-slate-200 p-3.5 sm:p-5 shadow-2xs">
        <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            @include('transactions._form')

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <a href="{{ route('transactions.index') }}" class="px-3.5 py-1.5 rounded-lg border border-slate-300 text-xs font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
