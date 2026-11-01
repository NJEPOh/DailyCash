<x-layouts.app :title="'Detail Transaksi #' . $transaction->id">
    <x-page-header
        :title="'Detail Transaksi #' . $transaction->id"
        subtitle="Informasi lengkap catatan transaksi dan bukti pembayaran."
        :backUrl="route('transactions.index')"
        backText="Kembali ke Daftar Transaksi"
    >
        <a href="{{ route('transactions.edit', $transaction) }}" class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-medium text-slate-700 hover:bg-slate-50">
            Edit Transaksi
        </a>
        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Saldo rekening akan dikembalikan.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold transition">
                Hapus Transaksi
            </button>
        </form>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3.5 sm:gap-4">
        {{-- Rincian Informasi Transaksi (Grid 2 Kolom di Mobile) --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-3.5 sm:p-5 shadow-2xs space-y-4">
            <div class="flex items-center justify-between gap-3 pb-3.5 border-b border-slate-100">
                <div>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Nominal Transaksi</span>
                    <p class="text-[20px] sm:text-2xl font-bold {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }} mt-0.5">
                        {{ $transaction->type === 'income' ? '+' : '-' }}{{ $transaction->formatted_amount }}
                    </p>
                </div>
                <div>
                    <x-badge-type :type="$transaction->type" />
                </div>
            </div>

            <dl class="grid grid-cols-2 gap-2.5 sm:gap-3.5 text-xs xs-single-col">
                <div class="p-3 rounded-lg bg-slate-50 border border-slate-100 min-w-0">
                    <dt class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Tanggal Transaksi</dt>
                    <dd class="font-semibold text-slate-900 mt-0.5">{{ $transaction->date->translatedFormat('d M Y') }} ({{ $transaction->date->format('Y-m-d') }})</dd>
                </div>

                <div class="p-3 rounded-lg bg-slate-50 border border-slate-100 min-w-0">
                    <dt class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Kategori</dt>
                    <dd class="font-semibold text-slate-900 mt-0.5 truncate">
                        @if ($transaction->category)
                            <a href="{{ route('categories.show', $transaction->category) }}" class="hover:text-emerald-600 underline underline-offset-2">
                                {{ $transaction->category->name }}
                            </a>
                        @else
                            -
                        @endif
                    </dd>
                </div>

                <div class="p-3 rounded-lg bg-slate-50 border border-slate-100 min-w-0">
                    <dt class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Rekening / Dompet</dt>
                    <dd class="font-semibold text-slate-900 mt-0.5 truncate">
                        @if ($transaction->account)
                            <a href="{{ route('accounts.show', $transaction->account) }}" class="hover:text-emerald-600 underline underline-offset-2">
                                {{ $transaction->account->name }}
                            </a>
                            <span class="block text-[11px] text-slate-500 font-normal mt-0.5 truncate">Saldo: {{ $transaction->account->formatted_balance }}</span>
                        @else
                            -
                        @endif
                    </dd>
                </div>

                <div class="p-3 rounded-lg bg-slate-50 border border-slate-100 min-w-0">
                    <dt class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Waktu Pencatatan</dt>
                    <dd class="font-semibold text-slate-900 mt-0.5">{{ $transaction->created_at?->format('d/m/Y H:i') }}</dd>
                    <dd class="text-[11px] text-slate-500 mt-0.5">Update: {{ $transaction->updated_at?->format('d/m/Y H:i') }}</dd>
                </div>

                <div class="col-span-2 p-3 rounded-lg bg-slate-50 border border-slate-100">
                    <dt class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Deskripsi / Keterangan</dt>
                    <dd class="text-slate-800 mt-1 whitespace-pre-line break-words">{{ $transaction->description ?: 'Tidak ada deskripsi tambahan.' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Bukti Pembayaran Gambar --}}
        <div class="bg-white rounded-xl border border-slate-200 p-3.5 sm:p-5 shadow-2xs flex flex-col justify-between space-y-3">
            <div>
                <h2 class="text-sm sm:text-base font-bold text-slate-900">Bukti Pembayaran</h2>
                <p class="text-[11px] text-slate-500">Lampiran foto struk atau bukti transfer</p>
            </div>

            @if ($transaction->proof_image)
                <div class="space-y-2.5">
                    <div class="rounded-lg overflow-hidden border border-slate-200 bg-slate-100">
                        <img
                            src="{{ route('transactions.proof', $transaction) }}"
                            alt="Bukti Pembayaran Transaksi #{{ $transaction->id }}"
                            class="w-full max-h-64 object-contain mx-auto"
                        >
                    </div>
                    <a
                        href="{{ route('transactions.proof', $transaction) }}"
                        target="_blank"
                        class="w-full inline-flex items-center justify-center px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
                    >
                        Buka Ukuran Penuh &nearr;
                    </a>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-center py-6 px-3 rounded-lg border border-dashed border-slate-300 bg-slate-50">
                    <svg class="w-8 h-8 text-slate-400 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xs font-medium text-slate-700">Tidak Ada Bukti Gambar</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Transaksi ini disimpan tanpa lampiran bukti pembayaran.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
