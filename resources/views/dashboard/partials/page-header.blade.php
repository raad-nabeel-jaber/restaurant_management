{{-- Flowbite: عنوان + حقول نموذج (معطّلة مؤقتاً) --}}
@php
    $now = now()->locale(app()->getLocale());
@endphp

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-[#f0ece3]">{{ __('نظرة عامة') }} 👋</h1>
        <p class="mt-1 text-sm text-[#9a9690]">
            {{ $now->translatedFormat('l، j F Y') }}
        </p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <select class="block cursor-not-allowed rounded-lg border border-white/10 bg-[#1a1d23] p-2.5 text-sm text-[#9a9690]"
                disabled
                title="{{ __('قريباً') }}">
            <option>{{ __('اليوم') }}</option>
            <option>{{ __('هذا الأسبوع') }}</option>
            <option>{{ __('هذا الشهر') }}</option>
        </select>
        <button type="button"
                disabled
                class="inline-flex items-center gap-2 rounded-lg bg-[#f5a623] px-5 py-2.5 text-center text-sm font-bold text-[#1a1000] opacity-50"
                title="{{ __('قريباً') }}">
            <span>⬇</span> {{ __('تصدير') }}
        </button>
    </div>
</div>
