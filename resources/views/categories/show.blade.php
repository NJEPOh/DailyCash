<x-layouts.app :title="'Detail Kategori: ' . $category->name">
    <x-page-header
        :title="'Kategori: ' . $category->name"
        subtitle="Detail informasi kategori dan riwayat transaksi terkait."
        :backUrl="route('categories.index')"
        backText="Kembali ke Daftar Kategori"
    >
        <a href="{{ route('categories.edit', $category) }}" class="btn-secondary text-xs lg:text-sm">
            Edit Kategori
        </a>
    </x-page-header>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-2.5 sm:gap-3.5 lg:gap-5 xs-single-col">
        <div class="card">
            <p class="text-[11px] lg:text-sm font-semibold uppercase tracking-wide text-[#7E869E]">Tipe Kategori</p>
            <div class="mt-1.5 lg:mt-2">
                <x-badge-type :type="$category->type" />
            </div>
        </div>
        <div class="card">
            <p class="text-[11px] lg:text-sm font-semibold uppercase tracking-wide text-[#7E869E]">Jumlah Transaksi</p>
            <p class="text-[20px] sm:text-2xl lg:text-[30px] font-bold text-[#1A1D20] mt-0.5">{{ $category->transactions_count }} Tx</p>
        </div>
        <div class="col-span-2 md:col-span-1 card">
            <p class="text-[11px] lg:text-sm font-semibold uppercase tracking-wide text-[#7E869E]">Total Akumulasi Nominal</p>
            <p class="text-[20px] sm:text-2xl lg:text-[30px] font-bold {{ $category->type === 'income' ? 'text-[#2ED47A]' : 'text-[#FF6B8B]' }} mt-0.5">
                Rp{{ number_format($totalAmount, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="card space-y-3 lg:space-y-4">
        <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Riwayat Transaksi Kategori Ini</h2>

        @if ($transactions->isEmpty())
            <x-empty-state
                title="Belum ada transaksi pada kategori ini"
                description="Transaksi yang menggunakan kategori ini akan muncul di sini."
                :actionUrl="route('transactions.create', ['type' => $category->type])"
                actionText="+ Catat Transaksi"
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#F4F5F9] text-[11px] lg:text-sm font-semibold uppercase tracking-wider text-[#A3AABF]">
                            <th class="py-2 lg:py-3 pr-2.5">Tanggal</th>
                            <th class="py-2 lg:py-3 px-2.5">Rekening</th>
                            <th class="py-2 lg:py-3 px-2.5">Deskripsi</th>
                            <th class="py-2 lg:py-3 pl-2.5 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F4F5F9] text-xs md:text-sm lg:text-base">
                        @foreach ($transactions as $tx)
                            <tr class="hover:bg-[#F4F5F9]/60">
                                <td class="py-2 lg:py-3 pr-2.5 whitespace-nowrap text-[11px] lg:text-sm text-[#7E869E]">{{ $tx->date->format('d/m/Y') }}</td>
                                <td class="py-2 lg:py-3 px-2.5 font-medium text-[#1A1D20]">{{ $tx->account?->name ?? '-' }}</td>
                                <td class="py-2 lg:py-3 px-2.5 text-[#7E869E]">
                                    <a href="{{ route('transactions.show', $tx) }}" class="hover:text-[#5D56F3] hover:underline">
                                        {{ $tx->description ?: 'Tanpa deskripsi' }}
                                    </a>
                                </td>
                                <td class="py-2 lg:py-3 pl-2.5 text-right font-bold {{ $tx->type === 'income' ? 'text-[#2ED47A]' : 'text-[#FF6B8B]' }}">
                                    {{ $tx->formatted_amount }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pt-3">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
