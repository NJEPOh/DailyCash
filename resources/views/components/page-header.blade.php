@props([
    'title',
    'accent' => null,
    'subtitle' => null,
    'backUrl' => null,
    'backText' => 'Kembali',
])

<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
    <div class="min-w-0">
        @if ($backUrl)
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-[#8E9299] hover:text-[#18191B] text-[11px] lg:text-[12px] font-semibold mb-2 shadow-xs transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span>{{ $backText }}</span>
            </a>
        @endif
        <h1 class="bento-h1">
            {{ $title }}
            @if ($accent)
                <span class="bento-h1-accent">{{ $accent }}</span>
            @endif
        </h1>
        @if ($subtitle)
            <p class="bento-meta mt-1.5 leading-relaxed">{{ $subtitle }}</p>
        @endif
    </div>

    @if (! $slot->isEmpty())
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            {{ $slot }}
        </div>
    @endif
</div>
