<x-layouts.app :title="'Detail Transaksi #' . $transaction->id">
    <x-page-header
        :title="'Detail Transaksi #' . $transaction->id"
        subtitle="Informasi lengkap catatan transaksi dan bukti pembayaran."
        :backUrl="route('transactions.index')"
        backText="Kembali ke Daftar Transaksi"
    >
        <a href="{{ route('transactions.edit', $transaction) }}" class="btn-secondary text-xs lg:text-sm">
            Edit Transaksi
        </a>
        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Saldo rekening akan dikembalikan.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger text-xs lg:text-sm">
                Hapus Transaksi
            </button>
        </form>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3.5 sm:gap-4 lg:gap-6">
        {{-- Rincian Informasi Transaksi (Grid 2 Kolom di Mobile) --}}
        <div class="lg:col-span-2 card space-y-4 lg:space-y-5">
            <div class="flex items-center justify-between gap-3 pb-3.5 lg:pb-4 border-b border-[#F4F5F9]">
                <div>
                    <span class="text-[11px] lg:text-sm font-semibold uppercase tracking-wider text-[#7E869E]">Nominal Transaksi</span>
                    <p class="text-[20px] sm:text-2xl lg:text-[32px] font-bold {{ $transaction->type === 'income' ? 'text-[#2ED47A]' : 'text-[#FF6B8B]' }} mt-0.5">
                        {{ $transaction->type === 'income' ? '+' : '-' }}{{ $transaction->formatted_amount }}
                    </p>
                </div>
                <div>
                    <x-badge-type :type="$transaction->type" />
                </div>
            </div>

            <dl class="grid grid-cols-2 gap-2.5 sm:gap-3.5 lg:gap-4 text-xs md:text-sm lg:text-base xs-single-col">
                <div class="p-3 lg:p-4 rounded-[12px] bg-[#F4F5F9] min-w-0">
                    <dt class="text-[11px] lg:text-sm font-semibold uppercase tracking-wide text-[#7E869E]">Tanggal Transaksi</dt>
                    <dd class="font-semibold text-[#1A1D20] mt-0.5">{{ $transaction->date->translatedFormat('d M Y') }} ({{ $transaction->date->format('Y-m-d') }})</dd>
                </div>

                <div class="p-3 lg:p-4 rounded-[12px] bg-[#F4F5F9] min-w-0">
                    <dt class="text-[11px] lg:text-sm font-semibold uppercase tracking-wide text-[#7E869E]">Kategori</dt>
                    <dd class="font-semibold text-[#1A1D20] mt-0.5 truncate">
                        @if ($transaction->category)
                            <a href="{{ route('categories.show', $transaction->category) }}" class="hover:text-[#5D56F3] underline underline-offset-2">
                                {{ $transaction->category->name }}
                            </a>
                        @else
                            -
                        @endif
                    </dd>
                </div>

                <div class="p-3 lg:p-4 rounded-[12px] bg-[#F4F5F9] min-w-0">
                    <dt class="text-[11px] lg:text-sm font-semibold uppercase tracking-wide text-[#7E869E]">Rekening / Dompet</dt>
                    <dd class="font-semibold text-[#1A1D20] mt-0.5 truncate">
                        @if ($transaction->account)
                            <a href="{{ route('accounts.show', $transaction->account) }}" class="hover:text-[#5D56F3] underline underline-offset-2">
                                {{ $transaction->account->name }}
                            </a>
                            <span class="block text-[11px] lg:text-sm text-[#7E869E] font-normal mt-0.5 truncate">Saldo: {{ $transaction->account->formatted_balance }}</span>
                        @else
                            -
                        @endif
                    </dd>
                </div>

                <div class="p-3 lg:p-4 rounded-[12px] bg-[#F4F5F9] min-w-0">
                    <dt class="text-[11px] lg:text-sm font-semibold uppercase tracking-wide text-[#7E869E]">Waktu Pencatatan</dt>
                    <dd class="font-semibold text-[#1A1D20] mt-0.5">{{ $transaction->created_at?->format('d/m/Y H:i') }}</dd>
                    <dd class="text-[11px] lg:text-sm text-[#7E869E] mt-0.5">Update: {{ $transaction->updated_at?->format('d/m/Y H:i') }}</dd>
                </div>

                <div class="col-span-2 p-3 lg:p-4 rounded-[12px] bg-[#F4F5F9]">
                    <dt class="text-[11px] lg:text-sm font-semibold uppercase tracking-wide text-[#7E869E]">Deskripsi / Keterangan</dt>
                    <dd class="text-[#1A1D20] mt-1 whitespace-pre-line break-words">{{ $transaction->description ?: 'Tidak ada deskripsi tambahan.' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Bukti Pembayaran Gambar --}}
        <div class="card flex flex-col justify-between space-y-3 lg:space-y-4">
            <div>
                <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-[#1A1D20]">Bukti Pembayaran</h2>
                <p class="text-[11px] lg:text-sm text-[#7E869E]">Lampiran foto struk atau bukti transfer</p>
            </div>

            @if ($transaction->proof_image)
                <div class="space-y-2.5 lg:space-y-3">
                    <div class="rounded-[12px] overflow-hidden border border-[#F4F5F9] bg-[#F4F5F9]">
                        <img
                            src="{{ route('transactions.proof', $transaction) }}"
                            alt="Bukti Pembayaran Transaksi #{{ $transaction->id }}"
                            class="w-full max-h-64 lg:max-h-72 object-contain mx-auto"
                        >
                    </div>
                    <a
                        href="{{ route('transactions.proof', $transaction) }}"
                        target="_blank"
                        class="btn-secondary w-full text-xs lg:text-sm"
                    >
                        Buka Ukuran Penuh &nearr;
                    </a>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-center py-6 lg:py-8 px-3 rounded-[12px] border border-dashed border-[#A3AABF]/50 bg-[#F4F5F9]">
                    <svg class="w-8 h-8 lg:w-10 lg:h-10 text-[#A3AABF] mb-1.5 lg:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xs md:text-sm lg:text-base font-medium text-[#1A1D20]">Tidak Ada Bukti Gambar</p>
                    <p class="text-[11px] lg:text-sm text-[#7E869E] mt-0.5">Transaksi ini disimpan tanpa lampiran bukti pembayaran.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
