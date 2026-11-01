@props([
    'title',
    'value',
    'subtitle' => null,
    'variant' => 'default',
])

@php
    $styles = match ($variant) {
        'primary' => [
            'card' => 'bg-slate-900 text-white border-slate-800',
            'title' => 'text-slate-300',
            'value' => 'text-white',
            'subtitle' => 'text-emerald-400',
            'badge' => 'bg-slate-800 text-emerald-400',
        ],
        'income' => [
            'card' => 'bg-white text-slate-900 border-emerald-200',
            'title' => 'text-slate-500',
            'value' => 'text-emerald-600',
            'subtitle' => 'text-slate-500',
            'badge' => 'bg-emerald-50 text-emerald-600',
        ],
        'expense' => [
            'card' => 'bg-white text-slate-900 border-rose-200',
            'title' => 'text-slate-500',
            'value' => 'text-rose-600',
            'subtitle' => 'text-slate-500',
            'badge' => 'bg-rose-50 text-rose-600',
        ],
        default => [
            'card' => 'bg-white text-slate-900 border-slate-200',
            'title' => 'text-slate-500',
            'value' => 'text-slate-900',
            'subtitle' => 'text-slate-500',
            'badge' => 'bg-slate-100 text-slate-700',
        ],
    };
@endphp

<div class="rounded-xl lg:rounded-2xl border p-3 sm:p-4 lg:p-5 shadow-2xs min-w-0 {{ $styles['card'] }}">
    <div class="flex items-start justify-between gap-2 lg:gap-3">
        <div class="space-y-0.5 lg:space-y-1 min-w-0 flex-1">
            <p class="text-[11px] lg:text-sm font-semibold uppercase tracking-wide leading-tight truncate {{ $styles['title'] }}">{{ $title }}</p>
            <p class="text-[20px] sm:text-2xl lg:text-[30px] font-bold tracking-tight leading-snug break-words {{ $styles['value'] }}">{{ $value }}</p>
            @if ($subtitle)
                <p class="text-[11px] lg:text-sm leading-tight truncate {{ $styles['subtitle'] }} pt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        @if (isset($icon))
            <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-lg lg:rounded-xl hidden sm:flex items-center justify-center shrink-0 {{ $styles['badge'] }}">
                {{ $icon }}
            </div>
        @endif
    </div>
</div>
