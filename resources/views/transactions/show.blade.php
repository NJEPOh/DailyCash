<x-layouts.app :title="'Detail Transaksi #' . $transaction->id">
    <x-page-header
        :title="'Detail Transaksi'"
        :accent="'#' . $transaction->id"
        subtitle="Informasi lengkap catatan transaksi dan lampiran bukti pembayaran."
        :backUrl="route('transactions.index')"
        backText="Kembali ke Daftar Transaksi"
    >
        <a href="{{ route('transactions.edit', $transaction) }}" class="btn-secondary">
            Edit Transaksi
        </a>
        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Saldo rekening akan dikembalikan.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger">
                Hapus Transaksi
            </button>
        </form>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Rincian Informasi Transaksi --}}
        <div class="lg:col-span-2 bento-card space-y-5">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-[#ECECE9]">
                <div>
                    <span class="bento-label">Nominal Transaksi</span>
                    <p class="text-[24px] sm:text-[28px] lg:text-[32px] font-bold tracking-tight {{ $transaction->type === 'income' ? 'text-[#4B6411]' : 'text-[#C23B3B]' }} mt-0.5">
                        {{ $transaction->type === 'income' ? '+' : '-' }}{{ $transaction->formatted_amount }}
                    </p>
                </div>
                <x-badge-type :type="$transaction->type" />
            </div>

            <dl class="grid grid-cols-2 gap-3 lg:gap-4 xs-single-col">
                <div class="p-3.5 rounded-[16px] bg-[#F9F9F8] min-w-0">
                    <dt class="bento-label">Tanggal Transaksi</dt>
                    <dd class="bento-h3 mt-1">{{ $transaction->date->translatedFormat('d M Y') }} ({{ $transaction->date->format('Y-m-d') }})</dd>
                </div>

                <div class="p-3.5 rounded-[16px] bg-[#F9F9F8] min-w-0">
                    <dt class="bento-label">Kategori</dt>
                    <dd class="bento-h3 mt-1 truncate">
                        @if ($transaction->category)
                            <a href="{{ route('categories.show', $transaction->category) }}" class="hover:underline">
                                {{ $transaction->category->name }}
                            </a>
                        @else
                            -
                        @endif
                    </dd>
                </div>

                <div class="p-3.5 rounded-[16px] bg-[#F9F9F8] min-w-0">
                    <dt class="bento-label">Rekening / Dompet</dt>
                    <dd class="bento-h3 mt-1 truncate">
                        @if ($transaction->account)
                            <a href="{{ route('accounts.show', $transaction->account) }}" class="hover:underline">
                                {{ $transaction->account->name }}
                            </a>
                            <span class="block bento-meta mt-0.5 truncate">Saldo saat ini: {{ $transaction->account->formatted_balance }}</span>
                        @else
                            -
                        @endif
                    </dd>
                </div>

                <div class="p-3.5 rounded-[16px] bg-[#F9F9F8] min-w-0">
                    <dt class="bento-label">Waktu Pencatatan</dt>
                    <dd class="bento-h3 mt-1">{{ $transaction->created_at?->format('d/m/Y H:i') }}</dd>
                    <dd class="bento-meta mt-0.5">Update: {{ $transaction->updated_at?->format('d/m/Y H:i') }}</dd>
                </div>

                <div class="col-span-2 p-4 rounded-[16px] bg-[#F9F9F8]">
                    <dt class="bento-label">Deskripsi / Keterangan</dt>
                    <dd class="text-[13px] lg:text-[14px] font-medium text-[#18191B] mt-1 whitespace-pre-line break-words">{{ $transaction->description ?: 'Tidak ada deskripsi tambahan.' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Bukti Pembayaran Gambar --}}
        <div class="bento-card flex flex-col justify-between space-y-4">
            <div>
                <h2 class="bento-h2">Bukti Pembayaran</h2>
                <p class="bento-meta mt-0.5">Lampiran foto struk atau bukti transfer</p>
            </div>

            @if ($transaction->proof_image)
                <div class="space-y-3">
                    <div class="rounded-[18px] overflow-hidden bg-[#F9F9F8] p-2">
                        <img
                            src="{{ route('transactions.proof', $transaction) }}"
                            alt="Bukti Pembayaran Transaksi #{{ $transaction->id }}"
                            class="w-full max-h-64 lg:max-h-72 object-contain mx-auto rounded-[12px]"
                        >
                    </div>
                    <a
                        href="{{ route('transactions.proof', $transaction) }}"
                        target="_blank"
                        class="btn-lime w-full !py-2.5"
                    >
                        Buka Ukuran Penuh &nearr;
                    </a>
                </div>
            @else
                <div class="bento-dashed-zone flex-1 py-8 px-4">
                    <div class="bento-squircle bg-[#EFEFED] text-[#8E9299] mb-2.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="bento-h3">Tidak Ada Bukti Gambar</p>
                    <p class="bento-meta mt-1">Transaksi ini disimpan tanpa lampiran bukti pembayaran.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
