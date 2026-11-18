@props([
    'type',
])

@if ($type === 'income')
    <span class="badge badge-lime">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 5v14m-7-7h14"/>
        </svg>
        Pemasukan
    </span>
@else
    <span class="badge badge-coral">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M5 12h14"/>
        </svg>
        Pengeluaran
    </span>
@endif
