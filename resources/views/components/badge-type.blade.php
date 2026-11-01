@props([
    'type',
])

@if ($type === 'income')
    <span class="badge badge-green">
        <span class="w-1.5 h-1.5 lg:w-2 lg:h-2 rounded-full bg-[#2ED47A]"></span>
        Pemasukan
    </span>
@else
    <span class="badge badge-pink">
        <span class="w-1.5 h-1.5 lg:w-2 lg:h-2 rounded-full bg-[#FF6B8B]"></span>
        Pengeluaran
    </span>
@endif
