@php
    $selectedType = old('type', $transaction->type ?? ($defaultType ?? 'expense'));
    $selectedCategoryId = old('category_id', $transaction->category_id ?? '');
    $selectedAccountId = old('account_id', $transaction->account_id ?? ($defaultAccountId ?? ''));
    $selectedDate = old('date', isset($transaction) ? $transaction->date->format('Y-m-d') : now()->format('Y-m-d'));
@endphp

<div class="space-y-3.5 lg:space-y-5">
    {{-- Pilihan Tipe Transaksi (2 Kolom di Mobile) --}}
    <div>
        <label class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1.5 lg:mb-2">Tipe Transaksi <span class="text-[#FF6B8B]">*</span></label>
        <div class="grid grid-cols-2 gap-2.5 lg:gap-4 xs-single-col">
            <label class="cursor-pointer">
                <input
                    type="radio"
                    name="type"
                    value="income"
                    class="peer sr-only"
                    @checked($selectedType === 'income')
                    onchange="filterCategoriesByType(this.value)"
                >
                <div class="p-2.5 lg:p-3.5 rounded-[12px] border-2 border-[#F4F5F9] bg-[#F4F5F9] peer-checked:border-[#2ED47A] peer-checked:bg-[#2ED47A]/10 text-center transition">
                    <span class="block text-xs md:text-sm lg:text-base font-bold text-[#1A1D20] peer-checked:text-[#2ED47A]">+ Pemasukan (Income)</span>
                    <span class="text-[11px] lg:text-sm text-[#7E869E]">Menambah saldo</span>
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
                <div class="p-2.5 lg:p-3.5 rounded-[12px] border-2 border-[#F4F5F9] bg-[#F4F5F9] peer-checked:border-[#FF6B8B] peer-checked:bg-[#FF6B8B]/10 text-center transition">
                    <span class="block text-xs md:text-sm lg:text-base font-bold text-[#1A1D20] peer-checked:text-[#FF6B8B]">- Pengeluaran (Expense)</span>
                    <span class="text-[11px] lg:text-sm text-[#7E869E]">Mengurangi saldo</span>
                </div>
            </label>
        </div>
        @error('type')
            <p class="text-[11px] lg:text-sm text-[#FF6B8B] mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tanggal & Nominal (2 Kolom di Mobile) --}}
    <div class="grid grid-cols-2 gap-2.5 sm:gap-3.5 lg:gap-4 xs-single-col">
        <div>
            <label for="date" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Tanggal Transaksi <span class="text-[#FF6B8B]">*</span></label>
            <input
                type="date"
                id="date"
                name="date"
                required
                value="{{ $selectedDate }}"
                class="input-field text-xs md:text-sm lg:text-base"
            >
            @error('date')
                <p class="text-[11px] lg:text-sm text-[#FF6B8B] mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="amount" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Nominal (Rp) <span class="text-[#FF6B8B]">*</span></label>
            <input
                type="number"
                id="amount"
                name="amount"
                min="1"
                step="1"
                required
                value="{{ old('amount', $transaction->amount ?? '') }}"
                placeholder="Contoh: 150000"
                class="input-field text-xs md:text-sm lg:text-base"
            >
            @error('amount')
                <p class="text-[11px] lg:text-sm text-[#FF6B8B] mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Kategori & Rekening/Dompet (2 Kolom di Mobile) --}}
    <div class="grid grid-cols-2 gap-2.5 sm:gap-3.5 lg:gap-4 xs-single-col">
        <div>
            <label for="category_id" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Kategori <span class="text-[#FF6B8B]">*</span></label>
            <select
                id="category_id"
                name="category_id"
                required
                class="input-field text-xs md:text-sm lg:text-base"
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
            <p class="text-[11px] lg:text-sm text-[#7E869E] mt-0.5">Sesuai tipe transaksi.</p>
            @error('category_id')
                <p class="text-[11px] lg:text-sm text-[#FF6B8B] mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="account_id" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Rekening / Dompet <span class="text-[#FF6B8B]">*</span></label>
            <select
                id="account_id"
                name="account_id"
                required
                class="input-field text-xs md:text-sm lg:text-base"
            >
                <option value="">-- Pilih Rekening --</option>
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}" @selected((string) $selectedAccountId === (string) $account->id)>
                        {{ $account->name }} ({{ $account->formatted_balance }})
                    </option>
                @endforeach
            </select>
            <p class="text-[11px] lg:text-sm text-[#7E869E] mt-0.5">Saldo tidak boleh minus.</p>
            @error('account_id')
                <p class="text-[11px] lg:text-sm text-[#FF6B8B] mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Deskripsi Opsional (Lebar Penuh) --}}
    <div>
        <label for="description" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Deskripsi / Catatan (Opsional)</label>
        <textarea
            id="description"
            name="description"
            rows="2"
            maxlength="255"
            placeholder="Tambahkan keterangan transaksi (maksimal 255 karakter)"
            class="input-field text-xs md:text-sm lg:text-base"
        >{{ old('description', $transaction->description ?? '') }}</textarea>
        @error('description')
            <p class="text-[11px] lg:text-sm text-[#FF6B8B] mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Bukti Pembayaran Opsional (Lebar Penuh) --}}
    <div>
        <label for="proof_image" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">Bukti Pembayaran / Struk Gambar (Opsional)</label>

        @if (isset($transaction) && $transaction->proof_image)
            <div class="mb-2.5 p-2.5 lg:p-3.5 rounded-[12px] bg-[#F4F5F9] flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <img src="{{ route('transactions.proof', $transaction) }}" alt="Bukti Transaksi" class="w-12 h-12 object-cover rounded-[8px]">
                    <div class="text-[11px] lg:text-sm">
                        <p class="font-semibold text-[#1A1D20]">Bukti pembayaran tersedia</p>
                        <p class="text-[#7E869E]">Unggah gambar baru jika ingin mengganti bukti lama.</p>
                    </div>
                </div>
                <label class="inline-flex items-center gap-1.5 text-[11px] lg:text-sm font-medium text-[#FF6B8B] cursor-pointer">
                    <input type="checkbox" name="remove_proof_image" value="1" class="rounded border-[#A3AABF] text-[#FF6B8B]">
                    <span>Hapus bukti</span>
                </label>
            </div>
        @endif

        <input
            type="file"
            id="proof_image"
            name="proof_image"
            accept="image/jpeg,image/png,image/webp,image/gif"
            class="w-full rounded-[12px] border border-dashed border-[#A3AABF] bg-[#F4F5F9] px-3 py-2 lg:px-3.5 lg:py-2.5 text-xs md:text-sm lg:text-base text-[#7E869E] file:mr-3 file:py-1 lg:file:py-1.5 file:px-3 lg:file:px-4 file:rounded-full file:border-0 file:text-[11px] lg:file:text-sm file:font-semibold file:bg-[#5D56F3] file:text-white hover:file:bg-[#4B45D3]"
        >
        <p class="text-[11px] lg:text-sm text-[#7E869E] mt-0.5">Boleh dikosongkan. Format: JPG, JPEG, PNG, WEBP, GIF (Maks. 2MB).</p>
        @error('proof_image')
            <p class="text-[11px] lg:text-sm text-[#FF6B8B] mt-1">{{ $message }}</p>
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
