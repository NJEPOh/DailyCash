@props([
    'title',
    'value',
    'subtitle' => null,
    'variant' => 'default',
    'progress' => null,
    'actionUrl' => null,
    'actionText' => null,
])

@php
    $styles = match ($variant) {
        'primary', 'dark' => [
            'card' => 'bento-card-dark',
            'label' => 'text-white/70',
            'value' => 'text-white',
            'subtitle' => 'text-[#DDF296]',
            'badge' => 'bg-white/10 text-[#DDF296]',
            'ringTrack' => 'rgba(255,255,255,0.16)',
            'ringStroke' => '#DDF296',
            'ringText' => 'text-white',
        ],
        'income', 'lime' => [
            'card' => 'bento-card bento-card-hover',
            'label' => 'text-[#8E9299]',
            'value' => 'text-[#18191B]',
            'subtitle' => 'text-[#4B6411]',
            'badge' => 'bg-[#ECF8C2] text-[#4B6411]',
            'ringTrack' => '#ECECE9',
            'ringStroke' => '#9AB847',
            'ringText' => 'text-[#18191B]',
        ],
        'expense', 'coral' => [
            'card' => 'bento-card bento-card-hover',
            'label' => 'text-[#8E9299]',
            'value' => 'text-[#C23B3B]',
            'subtitle' => 'text-[#8E9299]',
            'badge' => 'bg-[#FCEAE8] text-[#C23B3B]',
            'ringTrack' => '#ECECE9',
            'ringStroke' => '#C23B3B',
            'ringText' => 'text-[#C23B3B]',
        ],
        'yellow' => [
            'card' => 'bento-card bento-card-hover',
            'label' => 'text-[#8E9299]',
            'value' => 'text-[#18191B]',
            'subtitle' => 'text-[#D99B26]',
            'badge' => 'bg-[#FDF3D7] text-[#D99B26]',
            'ringTrack' => '#ECECE9',
            'ringStroke' => '#F7C948',
            'ringText' => 'text-[#18191B]',
        ],
        default => [
            'card' => 'bento-card bento-card-hover',
            'label' => 'text-[#8E9299]',
            'value' => 'text-[#18191B]',
            'subtitle' => 'text-[#8E9299]',
            'badge' => 'bg-[#E1EFFA] text-[#2C5E8A]',
            'ringTrack' => '#ECECE9',
            'ringStroke' => '#DDF296',
            'ringText' => 'text-[#18191B]',
        ],
    };

    $circumference = 125.66;
    $clampedProgress = $progress !== null ? max(0, min(100, (float) $progress)) : null;
    $dashOffset = $clampedProgress !== null ? round($circumference - ($clampedProgress / 100) * $circumference, 2) : null;
@endphp

<div class="min-w-0 flex flex-col justify-between gap-3 {{ $styles['card'] }}">
    <div class="flex items-start justify-between gap-2.5">
        <div class="space-y-1 min-w-0 flex-1">
            <p class="bento-label truncate {{ $styles['label'] }}">{{ $title }}</p>
            <p class="text-[19px] sm:text-[22px] lg:text-[26px] font-bold tracking-tight leading-snug break-words {{ $styles['value'] }}">{{ $value }}</p>
        </div>

        @if ($clampedProgress !== null)
            <div class="relative w-12 h-12 shrink-0 flex items-center justify-center">
                <svg class="w-12 h-12 -rotate-90" viewBox="0 0 48 48" aria-hidden="true">
                    <circle cx="24" cy="24" r="20" fill="none" stroke="{{ $styles['ringTrack'] }}" stroke-width="4" />
                    <circle
                        cx="24"
                        cy="24"
                        r="20"
                        fill="none"
                        stroke="{{ $styles['ringStroke'] }}"
                        stroke-width="4"
                        stroke-linecap="round"
                        stroke-dasharray="{{ $circumference }}"
                        stroke-dashoffset="{{ $dashOffset }}"
                    />
                </svg>
                <span class="absolute text-[10px] font-bold {{ $styles['ringText'] }}">{{ round($clampedProgress) }}%</span>
            </div>
        @elseif (isset($icon))
            <div class="bento-squircle hidden sm:inline-flex {{ $styles['badge'] }}">
                {{ $icon }}
            </div>
        @endif
    </div>

    @if ($subtitle || ($actionUrl && $actionText))
        <div class="flex items-center justify-between gap-2 pt-1">
            @if ($subtitle)
                <p class="text-[11px] lg:text-[12px] font-medium truncate {{ $styles['subtitle'] }}">{{ $subtitle }}</p>
            @endif
            @if ($actionUrl && $actionText)
                <a href="{{ $actionUrl }}" class="btn-lime !py-1 !px-3 text-[11px] shrink-0">
                    {{ $actionText }}
                </a>
            @endif
        </div>
    @endif
</div>
