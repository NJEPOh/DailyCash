@php
    $selectedType = old('type', $transaction->type ?? ($defaultType ?? 'expense'));
    $selectedCategoryId = old('category_id', $transaction->category_id ?? '');
    $selectedAccountId = old('account_id', $transaction->account_id ?? ($defaultAccountId ?? ''));
    $selectedDate = old('date', isset($transaction) ? $transaction->date->format('Y-m-d') : now()->format('Y-m-d'));
@endphp

<div class="space-y-5">
    {{-- Pilihan Tipe Transaksi --}}
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Tipe Transaksi <span class="text-rose-500">*</span></label>
        <div class="grid grid-cols-2 gap-3">
            <label class="cursor-pointer">
                <input
                    type="radio"
                    name="type"
                    value="income"
                    class="peer sr-only"
                    @checked($selectedType === 'income')
                    onchange="filterCategoriesByType(this.value)"
                >
                <div class="p-3.5 rounded-xl border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/70 text-center transition">
                    <span class="block text-sm font-bold text-slate-900 peer-checked:text-emerald-700">+ Pemasukan (Income)</span>
                    <span class="text-xs text-slate-500">Menambah saldo rekening</span>
                </div>
            </label>

            <label class="cursor-pointer">
                <input
                    type="radio"
                    name="type"
                    value="expense"
                    class="peer sr-only"
                    @checked($selectedType === 'expense')
                    onchange="filterCategoriesByType(this.value)"
                >
                <div class="p-3.5 rounded-xl border-2 border-slate-200 peer-checked:border-rose-500 peer-checked:bg-rose-50/70 text-center transition">
                    <span class="block text-sm font-bold text-slate-900 peer-checked:text-rose-700">- Pengeluaran (Expense)</span>
                    <span class="text-xs text-slate-500">Mengurangi saldo rekening</span>
                </div>
            </label>
        </div>
        @error('type')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tanggal & Nominal --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="date" class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Transaksi <span class="text-rose-500">*</span></label>
            <input
                type="date"
                id="date"
                name="date"
                required
                value="{{ $selectedDate }}"
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-slate-900 focus:outline-none"
            >
            @error('date')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="amount" class="block text-sm font-semibold text-slate-700 mb-1.5">Nominal Transaksi (Rp) <span class="text-rose-500">*</span></label>
            <input
                type="number"
                id="amount"
                name="amount"
                min="1"
                step="1"
                required
                value="{{ old('amount', $transaction->amount ?? '') }}"
                placeholder="Contoh: 150000"
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-slate-900 focus:outline-none"
            >
            @error('amount')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Kategori & Rekening/Dompet --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori <span class="text-rose-500">*</span></label>
            <select
                id="category_id"
                name="category_id"
                required
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm bg-white focus:border-slate-900 focus:outline-none"
            >
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        data-type="{{ $category->type }}"
                        @selected((string) $selectedCategoryId === (string) $category->id)
                    >
                        [{{ $category->type_label }}] {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-slate-500 mt-1">Pilih kategori yang sesuai dengan tipe transaksi.</p>
            @error('category_id')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="account_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Rekening / Dompet <span class="text-rose-500">*</span></label>
            <select
                id="account_id"
                name="account_id"
                required
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm bg-white focus:border-slate-900 focus:outline-none"
            >
                <option value="">-- Pilih Rekening / Dompet --</option>
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}" @selected((string) $selectedAccountId === (string) $account->id)>
                        {{ $account->name }} (Saldo: {{ $account->formatted_balance }})
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-slate-500 mt-1">Pengeluaran tidak boleh melebihi saldo rekening yang dipilih.</p>
            @error('account_id')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Deskripsi Opsional --}}
    <div>
        <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi / Catatan (Opsional)</label>
        <textarea
            id="description"
            name="description"
            rows="3"
            maxlength="255"
            placeholder="Tambahkan keterangan transaksi (maksimal 255 karakter)"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-slate-900 focus:outline-none"
        >{{ old('description', $transaction->description ?? '') }}</textarea>
        @error('description')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Bukti Pembayaran Opsional --}}
    <div>
        <label for="proof_image" class="block text-sm font-semibold text-slate-700 mb-1.5">Bukti Pembayaran / Struk Gambar (Opsional)</label>

        @if (isset($transaction) && $transaction->proof_image)
            <div class="mb-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <img src="{{ route('transactions.proof', $transaction) }}" alt="Bukti Transaksi" class="w-16 h-16 object-cover rounded-lg border border-slate-300">
                    <div class="text-xs">
                        <p class="font-semibold text-slate-800">Bukti pembayaran saat ini tersedia</p>
                        <p class="text-slate-500">Unggah gambar baru di bawah jika ingin mengganti bukti lama.</p>
                    </div>
                </div>
                <label class="inline-flex items-center gap-2 text-xs font-medium text-rose-600 cursor-pointer">
                    <input type="checkbox" name="remove_proof_image" value="1" class="rounded border-slate-300 text-rose-600">
                    <span>Hapus bukti saat ini</span>
                </label>
            </div>
        @endif

        <input
            type="file"
            id="proof_image"
            name="proof_image"
            accept="image/jpeg,image/png,image/webp,image/gif"
            class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800"
        >
        <p class="text-xs text-slate-500 mt-1">Boleh dikosongkan. Format yang didukung: JPG, JPEG, PNG, WEBP, GIF (Maks. 2MB).</p>
        @error('proof_image')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<script>
    function filterCategoriesByType(selectedType) {
        const select = document.getElementById('category_id');
        if (!select) return;

        const options = select.querySelectorAll('option[data-type]');
        options.forEach((option) => {
            const matches = option.getAttribute('data-type') === selectedType;
            option.hidden = !matches;
        });

        const currentSelected = select.options[select.selectedIndex];
        if (currentSelected && currentSelected.hasAttribute('data-type') && currentSelected.getAttribute('data-type') !== selectedType) {
            select.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkedRadio = document.querySelector('input[name="type"]:checked');
        if (checkedRadio) {
            filterCategoriesByType(checkedRadio.value);
        }
    });
</script>
