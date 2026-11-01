<x-layouts.app title="Rekening & Dompet">
    <x-page-header
        title="Rekening & Dompet"
        subtitle="Kelola sumber dana dan pantau saldo setiap rekening atau dompet secara real-time."
    >
        <a href="{{ route('accounts.create') }}" class="btn-primary text-xs lg:text-sm">
            <span>+ Tambah Rekening</span>
        </a>
    </x-page-header>

    {{-- Statistik Ringkas 2 Kolom di Mobile --}}
    <div class="grid grid-cols-2 gap-2.5 sm:gap-3.5 lg:gap-5 xs-single-col">
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
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-2.5 sm:gap-3.5 lg:gap-5 xs-single-col">
            @foreach ($accounts as $account)
                <div class="card flex flex-col justify-between space-y-3 lg:space-y-4 min-w-0">
                    <div class="space-y-2 min-w-0">
                        <div class="flex items-start justify-between gap-1.5">
                            <div class="min-w-0">
                                <a href="{{ route('accounts.show', $account) }}" class="text-sm sm:text-base lg:text-xl font-bold text-[#1A1D20] hover:text-[#5D56F3] transition block truncate">
                                    {{ $account->name }}
                                </a>
                                <p class="text-[11px] lg:text-sm text-[#7E869E]">{{ $account->transactions_count }} transaksi</p>
                            </div>
                        </div>

                        <div class="pt-1">
                            <p class="text-[11px] lg:text-sm text-[#7E869E]">Saldo Saat Ini</p>
                            <p class="text-[20px] sm:text-2xl lg:text-[28px] font-bold text-[#5D56F3] leading-snug break-words">{{ $account->formatted_balance }}</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 pt-2 border-t border-[#F4F5F9] text-[11px] lg:text-sm">
                            <div class="truncate">
                                <span class="text-[#A3AABF]">Masuk: </span>
                                <span class="font-semibold text-[#2ED47A]">+Rp{{ number_format((int) ($account->total_income ?? 0), 0, ',', '.') }}</span>
                            </div>
                            <div class="truncate">
                                <span class="text-[#A3AABF]">Keluar: </span>
                                <span class="font-semibold text-[#FF6B8B]">-Rp{{ number_format((int) ($account->total_expense ?? 0), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-1.5 pt-2.5 border-t border-[#F4F5F9]">
                        <a href="{{ route('accounts.show', $account) }}" class="text-xs lg:text-sm font-semibold text-[#5D56F3] hover:text-[#4B45D3]">
                            Detail &rarr;
                        </a>
                        <div class="flex items-center gap-1 lg:gap-1.5">
                            <a href="{{ route('accounts.edit', $account) }}" class="px-2.5 py-1 lg:px-3 lg:py-1.5 rounded-full bg-[#EEEDFE] text-xs lg:text-sm font-medium text-[#5D56F3] hover:opacity-85">
                                Ubah
                            </a>
                            <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline" onsubmit="return confirm('Hapus rekening {{ addslashes($account->name) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 lg:px-3 lg:py-1.5 rounded-full bg-[#FF6B8B]/12 text-xs lg:text-sm font-medium text-[#FF6B8B] hover:bg-[#FF6B8B]/20 cursor-pointer">
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
