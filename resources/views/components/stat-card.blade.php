@props([
    'title',
    'value',
    'subtitle' => null,
    'variant' => 'default',
])

@php
    $styles = match ($variant) {
        'primary' => [
            'card' => 'card-premium',
            'title' => 'text-white/80',
            'value' => 'text-white',
            'subtitle' => 'text-[#2ED47A]',
            'badge' => 'bg-white/20 text-white',
        ],
        'income' => [
            'card' => 'card',
            'title' => 'text-[#7E869E]',
            'value' => 'text-[#2ED47A]',
            'subtitle' => 'text-[#A3AABF]',
            'badge' => 'bg-[#2ED47A]/12 text-[#2ED47A]',
        ],
        'expense' => [
            'card' => 'card',
            'title' => 'text-[#7E869E]',
            'value' => 'text-[#FF6B8B]',
            'subtitle' => 'text-[#A3AABF]',
            'badge' => 'bg-[#FF6B8B]/12 text-[#FF6B8B]',
        ],
        default => [
            'card' => 'card',
            'title' => 'text-[#7E869E]',
            'value' => 'text-[#1A1D20]',
            'subtitle' => 'text-[#A3AABF]',
            'badge' => 'bg-[#EEEDFE] text-[#5D56F3]',
        ],
    };
@endphp

<div class="min-w-0 {{ $styles['card'] }}">
    <div class="flex items-start justify-between gap-2 lg:gap-3">
        <div class="space-y-0.5 lg:space-y-1 min-w-0 flex-1">
            <p class="text-[11px] lg:text-sm font-semibold uppercase tracking-wide leading-tight truncate {{ $styles['title'] }}">{{ $title }}</p>
            <p class="text-[20px] sm:text-2xl lg:text-[30px] font-bold tracking-tight leading-snug break-words {{ $styles['value'] }}">{{ $value }}</p>
            @if ($subtitle)
                <p class="text-[11px] lg:text-sm leading-tight truncate {{ $styles['subtitle'] }} pt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        @if (isset($icon))
            <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-[12px] hidden sm:flex items-center justify-center shrink-0 {{ $styles['badge'] }}">
                {{ $icon }}
            </div>
        @endif
    </div>
</div>
