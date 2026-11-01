<x-layouts.app title="Rekening & Dompet">
    <x-page-header
        title="Rekening & Dompet"
        subtitle="Kelola sumber dana dan pantau saldo setiap rekening atau dompet secara real-time."
    >
        <a href="{{ route('accounts.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
            <span>+ Tambah Rekening</span>
        </a>
    </x-page-header>

    {{-- Statistik Ringkas 2 Kolom di Mobile --}}
    <div class="grid grid-cols-2 gap-2.5 sm:gap-3.5 xs-single-col">
        <x-stat-card
            title="Total Saldo Seluruh Rekening"
            :value="'Rp' . number_format($totalBalance, 0, ',', '.')"
            subtitle="Dana tersedia saat ini"
            variant="primary"
        />
        <x-stat-card
            title="Jumlah Akun Aktif"
            :value="$accounts->count() . ' Rekening'"
            subtitle="Bank, E-Wallet, & Kas Tunai"
            variant="default"
        />
    </div>

    @if ($accounts->isEmpty())
        <x-empty-state
            title="Belum ada rekening atau dompet"
            description="Tambahkan rekening bank, dompet digital (e-wallet), atau kas tunai untuk mulai mencatat transaksi."
            :actionUrl="route('accounts.create')"
            actionText="+ Tambah Rekening Pertama"
        />
    @else
        {{-- Grid 2 Kolom pada Mobile, 3 Kolom pada Tablet, 4 Kolom pada Desktop --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-2.5 sm:gap-3.5 xs-single-col">
            @foreach ($accounts as $account)
                <div class="bg-white rounded-xl border border-slate-200 p-3 sm:p-4 shadow-2xs flex flex-col justify-between space-y-3 min-w-0">
                    <div class="space-y-2 min-w-0">
                        <div class="flex items-start justify-between gap-1.5">
                            <div class="min-w-0">
                                <a href="{{ route('accounts.show', $account) }}" class="text-sm font-bold text-slate-900 hover:text-emerald-600 transition block truncate">
                                    {{ $account->name }}
                                </a>
                                <p class="text-[11px] text-slate-400">{{ $account->transactions_count }} transaksi</p>
                            </div>
                        </div>

                        <div class="pt-1">
                            <p class="text-[11px] text-slate-500">Saldo Saat Ini</p>
                            <p class="text-[20px] sm:text-2xl font-bold text-slate-900 leading-snug break-words">{{ $account->formatted_balance }}</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 pt-2 border-t border-slate-100 text-[11px]">
                            <div class="truncate">
                                <span class="text-slate-400">Masuk: </span>
                                <span class="font-semibold text-emerald-600">+Rp{{ number_format((int) ($account->total_income ?? 0), 0, ',', '.') }}</span>
                            </div>
                            <div class="truncate">
                                <span class="text-slate-400">Keluar: </span>
                                <span class="font-semibold text-rose-600">-Rp{{ number_format((int) ($account->total_expense ?? 0), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-1.5 pt-2.5 border-t border-slate-100">
                        <a href="{{ route('accounts.show', $account) }}" class="text-[11px] font-semibold text-slate-700 hover:text-slate-900">
                            Detail &rarr;
                        </a>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('accounts.edit', $account) }}" class="px-2 py-1 rounded border border-slate-200 text-[11px] font-medium text-slate-700 hover:bg-slate-50">
                                Ubah
                            </a>
                            <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline" onsubmit="return confirm('Hapus rekening {{ addslashes($account->name) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 rounded border border-rose-200 text-[11px] font-medium text-rose-600 hover:bg-rose-50">
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
