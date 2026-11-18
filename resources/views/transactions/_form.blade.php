@php
    $selectedType = old('type', $transaction->type ?? ($defaultType ?? 'expense'));
    $selectedCategoryId = old('category_id', $transaction->category_id ?? '');
    $selectedAccountId = old('account_id', $transaction->account_id ?? ($defaultAccountId ?? ''));
    $selectedDate = old('date', isset($transaction) ? $transaction->date->format('Y-m-d') : now()->format('Y-m-d'));
@endphp

<div class="space-y-4 lg:space-y-5">
    {{-- Pilihan Tipe Transaksi (2 Kolom Bento Cards) --}}
    <div>
        <label class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-2">
            Tipe Transaksi <span class="text-[#C23B3B]">*</span>
        </label>
        <div class="grid grid-cols-2 gap-3 xs-single-col">
            <label class="cursor-pointer">
                <input
                    type="radio"
                    name="type"
                    value="income"
                    class="peer sr-only"
                    @checked($selectedType === 'income')
                    onchange="filterCategoriesByType(this.value)"
                >
                <div class="p-3.5 rounded-[18px] bg-[#F9F9F8] border-2 border-transparent peer-checked:border-[#18191B] peer-checked:bg-[#ECF8C2] text-center transition">
                    <span class="block text-[13px] lg:text-[14px] font-bold text-[#18191B]">+ Pemasukan (Income)</span>
                    <span class="bento-meta">Menambah saldo rekening</span>
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
                <div class="p-3.5 rounded-[18px] bg-[#F9F9F8] border-2 border-transparent peer-checked:border-[#18191B] peer-checked:bg-[#FDF3D7] text-center transition">
                    <span class="block text-[13px] lg:text-[14px] font-bold text-[#18191B]">- Pengeluaran (Expense)</span>
                    <span class="bento-meta">Mengurangi saldo rekening</span>
                </div>
            </label>
        </div>
        @error('type')
            <p class="text-[11px] lg:text-[12px] font-semibold text-[#C23B3B] mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tanggal & Nominal (2 Kolom) --}}
    <div class="grid grid-cols-2 gap-3 lg:gap-4 xs-single-col">
        <div>
            <label for="date" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
                Tanggal Transaksi <span class="text-[#C23B3B]">*</span>
            </label>
            <input
                type="date"
                id="date"
                name="date"
                required
                value="{{ $selectedDate }}"
                class="input-field"
            >
            @error('date')
                <p class="text-[11px] lg:text-[12px] font-semibold text-[#C23B3B] mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="amount" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
                Nominal (Rp) <span class="text-[#C23B3B]">*</span>
            </label>
            <input
                type="number"
                id="amount"
                name="amount"
                min="1"
                step="1"
                required
                value="{{ old('amount', $transaction->amount ?? '') }}"
                placeholder="Contoh: 150000"
                class="input-field"
            >
            @error('amount')
                <p class="text-[11px] lg:text-[12px] font-semibold text-[#C23B3B] mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Kategori & Rekening/Dompet (2 Kolom) --}}
    <div class="grid grid-cols-2 gap-3 lg:gap-4 xs-single-col">
        <div>
            <label for="category_id" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
                Kategori <span class="text-[#C23B3B]">*</span>
            </label>
            <select id="category_id" name="category_id" required class="input-field">
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
            <p class="bento-meta mt-1">Sesuai tipe transaksi yang dipilih.</p>
            @error('category_id')
                <p class="text-[11px] lg:text-[12px] font-semibold text-[#C23B3B] mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="account_id" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
                Rekening / Dompet <span class="text-[#C23B3B]">*</span>
            </label>
            <select id="account_id" name="account_id" required class="input-field">
                <option value="">-- Pilih Rekening --</option>
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}" @selected((string) $selectedAccountId === (string) $account->id)>
                        {{ $account->name }} ({{ $account->formatted_balance }})
                    </option>
                @endforeach
            </select>
            <p class="bento-meta mt-1">Saldo rekening tidak boleh negatif.</p>
            @error('account_id')
                <p class="text-[11px] lg:text-[12px] font-semibold text-[#C23B3B] mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Deskripsi Opsional --}}
    <div>
        <label for="description" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
            Deskripsi / Catatan (Opsional)
        </label>
        <textarea
            id="description"
            name="description"
            rows="2"
            maxlength="255"
            placeholder="Tambahkan keterangan transaksi (maksimal 255 karakter)"
            class="input-field"
        >{{ old('description', $transaction->description ?? '') }}</textarea>
        @error('description')
            <p class="text-[11px] lg:text-[12px] font-semibold text-[#C23B3B] mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Bukti Pembayaran Opsional --}}
    <div>
        <label for="proof_image" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
            Bukti Pembayaran / Struk Gambar (Opsional)
        </label>

        @if (isset($transaction) && $transaction->proof_image)
            <div class="mb-3 p-3 rounded-[16px] bg-[#F9F9F8] flex flex-wrap items-center justify-between gap-2.5">
                <div class="flex items-center gap-3">
                    <img src="{{ route('transactions.proof', $transaction) }}" alt="Bukti Transaksi" class="w-12 h-12 object-cover rounded-[10px]">
                    <div>
                        <p class="text-[12px] lg:text-[13px] font-semibold text-[#18191B]">Bukti pembayaran tersedia</p>
                        <p class="bento-meta">Unggah gambar baru jika ingin mengganti bukti lama.</p>
                    </div>
                </div>
                <label class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-[#C23B3B] cursor-pointer">
                    <input type="checkbox" name="remove_proof_image" value="1" class="rounded border-[#D5D7DC] accent-[#1E1F21]">
                    <span>Hapus bukti</span>
                </label>
            </div>
        @endif

        <input
            type="file"
            id="proof_image"
            name="proof_image"
            accept="image/jpeg,image/png,image/webp,image/gif"
            class="w-full rounded-[16px] border-[1.5px] border-dashed border-[#D5D7DC] bg-[#F9F9F8] px-3.5 py-2.5 text-[12px] lg:text-[13px] text-[#8E9299] file:mr-3 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-[12px] file:font-semibold file:bg-[#DDF296] file:text-[#18191B] hover:file:bg-[#D2EC7B] cursor-pointer"
        >
        <p class="bento-meta mt-1">Boleh dikosongkan. Format: JPG, JPEG, PNG, WEBP, GIF (Maks. 2MB).</p>
        @error('proof_image')
            <p class="text-[11px] lg:text-[12px] font-semibold text-[#C23B3B] mt-1">{{ $message }}</p>
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
