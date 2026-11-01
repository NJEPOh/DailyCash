@props([
    'type',
])

@if ($type === 'income')
    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
        Pemasukan
    </span>
@else
    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 whitespace-nowrap">
        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
        Pengeluaran
    </span>
@endif
