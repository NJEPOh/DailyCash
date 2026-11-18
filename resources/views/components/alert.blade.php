@if (session('success'))
    <div class="bento-card !py-3 !px-4 flex items-center gap-3 text-[#18191B]" role="alert">
        <span class="w-8 h-8 rounded-[10px] bg-[#ECF8C2] text-[#4B6411] flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
            </svg>
        </span>
        <span class="text-[12px] lg:text-[13.5px] font-semibold">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="bento-card !py-3 !px-4 flex items-center gap-3 text-[#18191B]" role="alert">
        <span class="w-8 h-8 rounded-[10px] bg-[#FCEAE8] text-[#C23B3B] flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </span>
        <span class="text-[12px] lg:text-[13.5px] font-semibold">{{ session('error') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="bento-card !py-3.5 !px-4 text-[#18191B]" role="alert">
        <div class="flex items-center gap-2.5 font-semibold text-[#C23B3B] mb-1.5">
            <span class="w-7 h-7 rounded-[10px] bg-[#FCEAE8] text-[#C23B3B] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </span>
            <span class="text-[12px] lg:text-[13.5px]">Terdapat kesalahan pada isian formulir:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 text-[11px] lg:text-[12px] text-[#8E9299] ml-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
