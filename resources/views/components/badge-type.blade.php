@props([
    'type',
])

@if ($type === 'income')
    <span class="inline-flex items-center gap-1 lg:gap-1.5 px-2 py-0.5 lg:px-2.5 lg:py-1 rounded-md lg:rounded-lg text-[11px] lg:text-sm font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
        <span class="w-1.5 h-1.5 lg:w-2 lg:h-2 rounded-full bg-emerald-500"></span>
        Pemasukan
    </span>
@else
    <span class="inline-flex items-center gap-1 lg:gap-1.5 px-2 py-0.5 lg:px-2.5 lg:py-1 rounded-md lg:rounded-lg text-[11px] lg:text-sm font-semibold bg-rose-50 text-rose-700 border border-rose-200 whitespace-nowrap">
        <span class="w-1.5 h-1.5 lg:w-2 lg:h-2 rounded-full bg-rose-500"></span>
        Pengeluaran
    </span>
@endif
