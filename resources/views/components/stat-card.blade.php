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

<div class="rounded-2xl border p-5 shadow-xs {{ $styles['card'] }}">
    <div class="flex items-start justify-between gap-4">
        <div class="space-y-1 min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider {{ $styles['title'] }}">{{ $title }}</p>
            <p class="text-2xl sm:text-3xl font-bold tracking-tight truncate {{ $styles['value'] }}">{{ $value }}</p>
            @if ($subtitle)
                <p class="text-xs {{ $styles['subtitle'] }} pt-1">{{ $subtitle }}</p>
            @endif
        </div>
        @if (isset($icon))
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 {{ $styles['badge'] }}">
                {{ $icon }}
            </div>
        @endif
    </div>
</div>
