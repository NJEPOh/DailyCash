<x-layouts.app title="Rekening & Dompet">
    <x-page-header
        title="Rekening &"
        accent="Dompet"
        subtitle="Kelola sumber dana dan pantau saldo setiap rekening atau dompet secara real-time."
    >
        <a href="{{ route('accounts.create') }}" class="btn-bento-dark">
            <span>+ Tambah Rekening</span>
        </a>
    </x-page-header>

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-2 gap-3.5 xs-single-col">
        <x-stat-card
            title="Total Saldo Seluruh Rekening"
            :value="'Rp' . number_format($totalBalance, 0, ',', '.')"
            subtitle="Dana tersedia saat ini"
            variant="dark"
        />
        <x-stat-card
            title="Jumlah Akun Aktif"
            :value="$accounts->count() . ' Rekening'"
            subtitle="Bank, E-Wallet, & Kas Tunai"
            variant="lime"
            :progress="$accounts->count() > 0 ? min(100, $accounts->count() * 25) : 0"
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
        {{-- Bento Grid Rekening --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 xs-single-col">
            @foreach ($accounts as $index => $account)
                @php
                    $isPrimaryCard = $index === 0;
                    $share = $totalBalance > 0 ? round(($account->balance / $totalBalance) * 100, 1) : 0;
                @endphp
                <div class="{{ $isPrimaryCard ? 'bento-card-dark' : 'bento-card bento-card-hover' }} flex flex-col justify-between gap-4 min-w-0">
                    <div class="space-y-3 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="bento-label {{ $isPrimaryCard ? 'text-white/60' : '' }}">
                                    {{ $isPrimaryCard ? 'Rekening Utama' : 'Sumber Dana' }}
                                </p>
                                <a href="{{ route('accounts.show', $account) }}" class="text-[15px] lg:text-[16px] font-bold {{ $isPrimaryCard ? 'text-white' : 'text-[#18191B]' }} hover:underline block truncate mt-0.5">
                                    {{ $account->name }}
                                </a>
                            </div>
                            <span class="badge badge-yellow shrink-0">{{ $share }}%</span>
                        </div>

                        <div>
                            <p class="text-[11px] {{ $isPrimaryCard ? 'text-white/65' : 'text-[#8E9299]' }}">Saldo Saat Ini</p>
                            <p class="text-[20px] sm:text-[22px] lg:text-[24px] font-bold tracking-tight {{ $isPrimaryCard ? 'text-[#DDF296]' : 'text-[#18191B]' }} leading-snug break-words">
                                {{ $account->formatted_balance }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 pt-2.5 border-t {{ $isPrimaryCard ? 'border-white/10' : 'border-[#ECECE9]' }} text-[11px] lg:text-[12px]">
                            <div class="truncate">
                                <span class="{{ $isPrimaryCard ? 'text-white/60' : 'text-[#8E9299]' }}">Masuk: </span>
                                <span class="font-semibold {{ $isPrimaryCard ? 'text-[#ECF8C2]' : 'text-[#4B6411]' }}">+Rp{{ number_format((int) ($account->total_income ?? 0), 0, ',', '.') }}</span>
                            </div>
                            <div class="truncate">
                                <span class="{{ $isPrimaryCard ? 'text-white/60' : 'text-[#8E9299]' }}">Keluar: </span>
                                <span class="font-semibold {{ $isPrimaryCard ? 'text-[#FCEAE8]' : 'text-[#C23B3B]' }}">-Rp{{ number_format((int) ($account->total_expense ?? 0), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-2 pt-2.5 border-t {{ $isPrimaryCard ? 'border-white/10' : 'border-[#ECECE9]' }}">
                        <a href="{{ route('accounts.show', $account) }}" class="text-[12px] font-semibold {{ $isPrimaryCard ? 'text-white hover:text-[#DDF296]' : 'text-[#18191B] hover:underline' }}">
                            Detail ({{ $account->transactions_count }}) &rarr;
                        </a>
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('accounts.edit', $account) }}" class="badge {{ $isPrimaryCard ? '!bg-white/15 !text-white hover:!bg-white/25' : 'badge-blue' }}">
                                Ubah
                            </a>
                            <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline" onsubmit="return confirm('Hapus rekening {{ addslashes($account->name) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="badge badge-coral cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Dashed Add Wallet Card (css.md 5.B.4) --}}
            <a href="{{ route('accounts.create') }}" class="bento-dashed-zone min-h-[200px] gap-2">
                <span class="bento-plus-circle">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 5v14m-7-7h14"/>
                    </svg>
                </span>
                <span class="bento-h3">Tambah Rekening</span>
                <span class="bento-meta">Buat dompet atau bank baru</span>
            </a>
        </div>
    @endif
</x-layouts.app>
