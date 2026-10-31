@props([
    'title' => 'Belum ada data',
    'description' => 'Silakan tambahkan data baru untuk mulai mencatat keuangan.',
    'actionUrl' => null,
    'actionText' => null,
])

<div class="text-center py-12 px-4 rounded-2xl border border-dashed border-slate-300 bg-slate-50/60">
    <div class="w-12 h-12 rounded-2xl bg-slate-200/80 text-slate-500 flex items-center justify-center mx-auto mb-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
    </div>
    <h3 class="text-base font-semibold text-slate-800">{{ $title }}</h3>
    <p class="text-sm text-slate-500 max-w-md mx-auto mt-1">{{ $description }}</p>

    @if ($actionUrl && $actionText)
        <div class="mt-4">
            <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium transition">
                <span>{{ $actionText }}</span>
            </a>
        </div>
    @endif
</div>
