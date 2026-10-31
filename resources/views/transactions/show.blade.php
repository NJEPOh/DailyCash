<x-layouts.app :title="'Detail Transaksi #' . $transaction->id">
    <x-page-header
        :title="'Detail Transaksi #' . $transaction->id"
        subtitle="Informasi lengkap catatan transaksi dan bukti pembayaran."
        :backUrl="route('transactions.index')"
        backText="Kembali ke Daftar Transaksi"
    >
        <a href="{{ route('transactions.edit', $transaction) }}" class="px-4 py-2 rounded-xl border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50">
            Edit Transaksi
        </a>
        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Saldo rekening akan dikembalikan.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold transition">
                Hapus Transaksi
            </button>
        </form>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Rincian Informasi Transaksi --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                <div class="space-y-1">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nominal Transaksi</span>
                    <p class="text-3xl font-bold {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $transaction->type === 'income' ? '+' : '-' }}{{ $transaction->formatted_amount }}
                    </p>
                </div>
                <div>
                    <x-badge-type :type="$transaction->type" />
                </div>
            </div>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tanggal Transaksi</dt>
                    <dd class="font-semibold text-slate-900 mt-1">{{ $transaction->date->translatedFormat('d F Y') }} ({{ $transaction->date->format('Y-m-d') }})</dd>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kategori</dt>
                    <dd class="font-semibold text-slate-900 mt-1">
                        @if ($transaction->category)
                            <a href="{{ route('categories.show', $transaction->category) }}" class="hover:text-emerald-600 underline underline-offset-2">
                                {{ $transaction->category->name }}
                            </a>
                        @else
                            -
                        @endif
                    </dd>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Rekening / Dompet</dt>
                    <dd class="font-semibold text-slate-900 mt-1">
                        @if ($transaction->account)
                            <a href="{{ route('accounts.show', $transaction->account) }}" class="hover:text-emerald-600 underline underline-offset-2">
                                {{ $transaction->account->name }}
                            </a>
                            <span class="block text-xs text-slate-500 font-normal mt-0.5">Saldo saat ini: {{ $transaction->account->formatted_balance }}</span>
                        @else
                            -
                        @endif
                    </dd>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Waktu Pencatatan</dt>
                    <dd class="font-semibold text-slate-900 mt-1">{{ $transaction->created_at?->format('d/m/Y H:i') }}</dd>
                    <dd class="text-xs text-slate-500 mt-0.5">Diperbarui: {{ $transaction->updated_at?->format('d/m/Y H:i') }}</dd>
                </div>

                <div class="sm:col-span-2 p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Deskripsi / Keterangan</dt>
                    <dd class="text-slate-800 mt-1 whitespace-pre-line">{{ $transaction->description ?: 'Tidak ada deskripsi tambahan.' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Bukti Pembayaran Gambar --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between space-y-4">
            <div>
                <h2 class="text-base font-bold text-slate-900">Bukti Pembayaran</h2>
                <p class="text-xs text-slate-500">Lampiran foto struk atau bukti transfer</p>
            </div>

            @if ($transaction->proof_image)
                <div class="space-y-3">
                    <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-100">
                        <img
                            src="{{ route('transactions.proof', $transaction) }}"
                            alt="Bukti Pembayaran Transaksi #{{ $transaction->id }}"
                            class="w-full max-h-80 object-contain mx-auto"
                        >
                    </div>
                    <a
                        href="{{ route('transactions.proof', $transaction) }}"
                        target="_blank"
                        class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
                    >
                        Buka Ukuran Penuh &nearr;
                    </a>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-center py-10 px-4 rounded-xl border border-dashed border-slate-300 bg-slate-50">
                    <svg class="w-10 h-10 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm font-medium text-slate-700">Tidak Ada Bukti Gambar</p>
                    <p class="text-xs text-slate-500 mt-1">Transaksi ini disimpan tanpa lampiran gambar bukti pembayaran.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
