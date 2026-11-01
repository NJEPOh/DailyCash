@if (session('success'))
    <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-3 py-2.5 text-xs text-emerald-800 flex items-start justify-between gap-2.5" role="alert">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="rounded-xl bg-rose-50 border border-rose-200 px-3 py-2.5 text-xs text-rose-800 flex items-start justify-between gap-2.5" role="alert">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="rounded-xl bg-rose-50 border border-rose-200 px-3 py-2.5 text-xs text-rose-800" role="alert">
        <div class="flex items-center gap-1.5 font-semibold mb-1">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span>Terdapat kesalahan pada isian formulir:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700 ml-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
