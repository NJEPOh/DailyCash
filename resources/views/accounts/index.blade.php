<x-layouts.app title="Rekening & Dompet">
    <x-page-header
        title="Rekening & Dompet"
        subtitle="Kelola sumber dana dan pantau saldo setiap rekening atau dompet secara real-time."
    >
        <a href="{{ route('accounts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition">
            <span>+ Tambah Rekening/Dompet</span>
        </a>
    </x-page-header>

    <div class="rounded-2xl bg-slate-900 text-white p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Saldo Seluruh Rekening</p>
            <p class="text-3xl font-bold tracking-tight mt-1">Rp{{ number_format($totalBalance, 0, ',', '.') }}</p>
            <p class="text-xs text-emerald-400 mt-1">Terdiri dari {{ $accounts->count() }} rekening/dompet</p>
        </div>
        <a href="{{ route('transactions.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-bold transition">
            + Catat Transaksi Baru
        </a>
    </div>

    @if ($accounts->isEmpty())
        <x-empty-state
            title="Belum ada rekening atau dompet"
            description="Tambahkan rekening bank, dompet digital (e-wallet), atau kas tunai untuk mulai mencatat transaksi."
            :actionUrl="route('accounts.create')"
            actionText="+ Tambah Rekening Pertama"
        />
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($accounts as $account)
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between space-y-5">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <a href="{{ route('accounts.show', $account) }}" class="text-base font-bold text-slate-900 hover:text-emerald-600 transition">
                                    {{ $account->name }}
                                </a>
                                <p class="text-xs text-slate-400">{{ $account->transactions_count }} transaksi tercatat</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-semibold">
                                Aktif
                            </span>
                        </div>

                        <div class="pt-2">
                            <p class="text-xs text-slate-500">Saldo Saat Ini</p>
                            <p class="text-2xl font-bold text-slate-900 mt-0.5">{{ $account->formatted_balance }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-100 text-xs">
                            <div>
                                <span class="text-slate-400 block">Total Masuk</span>
                                <span class="font-semibold text-emerald-600">+Rp{{ number_format((int) ($account->total_income ?? 0), 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Total Keluar</span>
                                <span class="font-semibold text-rose-600">-Rp{{ number_format((int) ($account->total_expense ?? 0), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-2 pt-3 border-t border-slate-100">
                        <a href="{{ route('accounts.show', $account) }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">
                            Lihat Riwayat &rarr;
                        </a>
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('accounts.edit', $account) }}" class="px-2.5 py-1 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                Ubah Nama
                            </a>
                            <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekening {{ addslashes($account->name) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 rounded-lg border border-rose-200 text-xs font-medium text-rose-600 hover:bg-rose-50">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layouts.app>
