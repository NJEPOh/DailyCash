@props([
    'title',
    'subtitle' => null,
    'backUrl' => null,
    'backText' => 'Kembali',
])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        @if ($backUrl)
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-slate-800 mb-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span>{{ $backText }}</span>
            </a>
        @endif
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-sm text-slate-500 mt-0.5">{{ $subtitle }}</p>
        @endif
    </div>

    @if (! $slot->isEmpty())
        <div class="flex flex-wrap items-center gap-2">
            {{ $slot }}
        </div>
    @endif
</div>
