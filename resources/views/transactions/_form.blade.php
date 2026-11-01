@php
    $selectedType = old('type', $transaction->type ?? ($defaultType ?? 'expense'));
    $selectedCategoryId = old('category_id', $transaction->category_id ?? '');
    $selectedAccountId = old('account_id', $transaction->account_id ?? ($defaultAccountId ?? ''));
    $selectedDate = old('date', isset($transaction) ? $transaction->date->format('Y-m-d') : now()->format('Y-m-d'));
@endphp

<div class="space-y-3.5">
    {{-- Pilihan Tipe Transaksi (2 Kolom di Mobile) --}}
    <div>
        <label class="block text-[11px] font-semibold text-slate-700 mb-1.5">Tipe Transaksi <span class="text-rose-500">*</span></label>
        <div class="grid grid-cols-2 gap-2.5 xs-single-col">
            <label class="cursor-pointer">
                <input
                    type="radio"
                    name="type"
                    value="income"
                    class="peer sr-only"
                    @checked($selectedType === 'income')
                    onchange="filterCategoriesByType(this.value)"
                >
                <div class="p-2.5 rounded-lg border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/70 text-center transition">
                    <span class="block text-xs font-bold text-slate-900 peer-checked:text-emerald-700">+ Pemasukan (Income)</span>
                    <span class="text-[11px] text-slate-500">Menambah saldo</span>
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
                <div class="p-2.5 rounded-lg border-2 border-slate-200 peer-checked:border-rose-500 peer-checked:bg-rose-50/70 text-center transition">
                    <span class="block text-xs font-bold text-slate-900 peer-checked:text-rose-700">- Pengeluaran (Expense)</span>
                    <span class="text-[11px] text-slate-500">Mengurangi saldo</span>
                </div>
            </label>
        </div>
        @error('type')
            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tanggal & Nominal (2 Kolom di Mobile) --}}
    <div class="grid grid-cols-2 gap-2.5 sm:gap-3.5 xs-single-col">
        <div>
            <label for="date" class="block text-[11px] font-semibold text-slate-700 mb-1">Tanggal Transaksi <span class="text-rose-500">*</span></label>
            <input
                type="date"
                id="date"
                name="date"
                required
                value="{{ $selectedDate }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:border-slate-900 focus:outline-none"
            >
            @error('date')
                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="amount" class="block text-[11px] font-semibold text-slate-700 mb-1">Nominal (Rp) <span class="text-rose-500">*</span></label>
            <input
                type="number"
                id="amount"
                name="amount"
                min="1"
                step="1"
                required
                value="{{ old('amount', $transaction->amount ?? '') }}"
                placeholder="Contoh: 150000"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:border-slate-900 focus:outline-none"
            >
            @error('amount')
                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Kategori & Rekening/Dompet (2 Kolom di Mobile) --}}
    <div class="grid grid-cols-2 gap-2.5 sm:gap-3.5 xs-single-col">
        <div>
            <label for="category_id" class="block text-[11px] font-semibold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
            <select
                id="category_id"
                name="category_id"
                required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs bg-white focus:border-slate-900 focus:outline-none"
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
            <p class="text-[11px] text-slate-500 mt-0.5">Sesuai tipe transaksi.</p>
            @error('category_id')
                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="account_id" class="block text-[11px] font-semibold text-slate-700 mb-1">Rekening / Dompet <span class="text-rose-500">*</span></label>
            <select
                id="account_id"
                name="account_id"
                required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs bg-white focus:border-slate-900 focus:outline-none"
            >
                <option value="">-- Pilih Rekening --</option>
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}" @selected((string) $selectedAccountId === (string) $account->id)>
                        {{ $account->name }} ({{ $account->formatted_balance }})
                    </option>
                @endforeach
            </select>
            <p class="text-[11px] text-slate-500 mt-0.5">Saldo tidak boleh minus.</p>
            @error('account_id')
                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Deskripsi Opsional (Lebar Penuh) --}}
    <div>
        <label for="description" class="block text-[11px] font-semibold text-slate-700 mb-1">Deskripsi / Catatan (Opsional)</label>
        <textarea
            id="description"
            name="description"
            rows="2"
            maxlength="255"
            placeholder="Tambahkan keterangan transaksi (maksimal 255 karakter)"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:border-slate-900 focus:outline-none"
        >{{ old('description', $transaction->description ?? '') }}</textarea>
        @error('description')
            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Bukti Pembayaran Opsional (Lebar Penuh) --}}
    <div>
        <label for="proof_image" class="block text-[11px] font-semibold text-slate-700 mb-1">Bukti Pembayaran / Struk Gambar (Opsional)</label>

        @if (isset($transaction) && $transaction->proof_image)
            <div class="mb-2.5 p-2.5 rounded-lg bg-slate-50 border border-slate-200 flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <img src="{{ route('transactions.proof', $transaction) }}" alt="Bukti Transaksi" class="w-12 h-12 object-cover rounded border border-slate-300">
                    <div class="text-[11px]">
                        <p class="font-semibold text-slate-800">Bukti pembayaran tersedia</p>
                        <p class="text-slate-500">Unggah gambar baru jika ingin mengganti bukti lama.</p>
                    </div>
                </div>
                <label class="inline-flex items-center gap-1.5 text-[11px] font-medium text-rose-600 cursor-pointer">
                    <input type="checkbox" name="remove_proof_image" value="1" class="rounded border-slate-300 text-rose-600">
                    <span>Hapus bukti</span>
                </label>
            </div>
        @endif

        <input
            type="file"
            id="proof_image"
            name="proof_image"
            accept="image/jpeg,image/png,image/webp,image/gif"
            class="w-full rounded-lg border border-dashed border-slate-300 bg-slate-50 px-3 py-2 text-xs text-slate-600 file:mr-3 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-[11px] file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800"
        >
        <p class="text-[11px] text-slate-500 mt-0.5">Boleh dikosongkan. Format: JPG, JPEG, PNG, WEBP, GIF (Maks. 2MB).</p>
        @error('proof_image')
            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
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
