@props([
    'title' => 'Belum ada data',
    'description' => 'Silakan tambahkan data baru untuk mulai mencatat keuangan.',
    'actionUrl' => null,
    'actionText' => null,
])

<div class="bento-dashed-zone py-7 lg:py-10 px-4">
    <div class="bento-plus-circle mb-2.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 5v14m-7-7h14"/>
        </svg>
    </div>
    <h3 class="bento-h3">{{ $title }}</h3>
    <p class="bento-meta max-w-md mx-auto mt-1">{{ $description }}</p>

    @if ($actionUrl && $actionText)
        <div class="mt-3.5">
            <a href="{{ $actionUrl }}" class="btn-lime">
                <span>{{ $actionText }}</span>
            </a>
        </div>
    @endif
</div>
