@props([
    'title',
    'subtitle' => null,
    'backUrl' => null,
    'backText' => 'Kembali',
])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 lg:gap-4">
    <div class="min-w-0">
        @if ($backUrl)
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-1 text-[11px] lg:text-sm font-medium text-[#7E869E] hover:text-[#5D56F3] mb-1 transition">
                <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span>{{ $backText }}</span>
            </a>
        @endif
        <h1 class="text-[18px] sm:text-xl lg:text-[30px] font-bold tracking-tight text-[#1A1D20] leading-tight">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-[11px] lg:text-sm text-[#7E869E] mt-0.5 lg:mt-1 leading-normal">{{ $subtitle }}</p>
        @endif
    </div>

    @if (! $slot->isEmpty())
        <div class="flex flex-wrap items-center gap-1.5 lg:gap-2.5 shrink-0">
            {{ $slot }}
        </div>
    @endif
</div>
