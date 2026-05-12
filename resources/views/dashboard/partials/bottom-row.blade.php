{{-- Flowbite: بطاقات + أزرار --}}
@php
    $wa = $restaurant->whatsapp_number
        ? preg_replace('/\D+/', '', $restaurant->whatsapp_number)
        : null;
    $waUrl = $wa ? 'https://wa.me/'.$wa : '#';
    $waOrdersOn = $restaurant->whatsapp_orders_enabled ?? true;
@endphp

<div class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-2">
    <div class="dash-card dash-card-hover p-6">
        <h2 class="mb-5 text-base font-bold text-[#f0ece3]">{{ __('أكثر الأصناف طلباً') }} 🔥</h2>
        <div class="flex flex-col gap-3" id="topProducts">
            @forelse($topProducts as $p)
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-white/5 text-base">🍽️</div>
                    <div class="flex-1">
                        <div class="mb-1 flex justify-between">
                            <span class="text-sm font-bold text-[#f0ece3]">{{ $p['name'] }}</span>
                            <span class="text-xs font-bold text-[#9a9690]">{{ $p['sold'] }} {{ __('طلب') }}</span>
                        </div>
                        <div class="h-1 w-full rounded-full bg-white/10">
                            <div class="h-1 rounded-full transition-all duration-500" style="width: {{ $p['pct'] }}%; background: {{ $p['color'] }}"></div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-[#9a9690]">{{ __('لا توجد مبيعات بعد.') }}</p>
            @endforelse
        </div>
    </div>

    <div class="dash-card dash-card-hover p-6">
        <h2 class="mb-5 text-base font-bold text-[#f0ece3]">{{ __('إجراءات سريعة') }} ⚡</h2>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('products.create') }}"
               class="inline-flex flex-col items-center justify-center gap-2 rounded-xl bg-[#101118]/90 p-4 text-center text-sm font-bold text-[#f0ece3] shadow-md ring-1 ring-white/[0.05] transition-all duration-300 ease-in-out hover:scale-[1.02] hover:shadow-lg hover:ring-white/10">
                <span class="text-2xl">📋</span>
                {{ __('إضافة صنف جديد') }}
            </a>
            <a href="{{ route('categories.create') }}"
               class="inline-flex flex-col items-center justify-center gap-2 rounded-xl bg-[#101118]/90 p-4 text-center text-sm font-bold text-[#f0ece3] shadow-md ring-1 ring-white/[0.05] transition-all duration-300 ease-in-out hover:scale-[1.02] hover:shadow-lg hover:ring-white/10">
                <span class="text-2xl">🏷️</span>
                {{ __('إضافة قسم جديد') }}
            </a>
            <a href="{{ route('barcode.show') }}"
               class="inline-flex flex-col items-center justify-center gap-2 rounded-xl border border-[#f5a623]/30 bg-[#f5a623]/10 p-4 text-center text-sm font-bold text-[#f5a623] shadow-md transition-all duration-300 ease-in-out hover:scale-[1.02] hover:shadow-lg hover:bg-[#f5a623]/15">
                <span class="text-2xl">📱</span>
                {{ __('عرض الباركود') }}
            </a>
            @if($wa && $waOrdersOn)
                <a href="{{ $waUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex flex-col items-center justify-center gap-2 rounded-xl border border-[#25d366]/30 bg-[#25d366]/10 p-4 text-center text-sm font-bold text-[#25d366] shadow-md transition-all duration-300 ease-in-out hover:scale-[1.02] hover:shadow-lg hover:bg-[#25d366]/15">
                    <span class="text-2xl">💬</span>
                    {{ __('واتساب') }}
                </a>
            @elseif($wa)
                <span class="inline-flex cursor-not-allowed flex-col items-center justify-center gap-2 rounded-lg border border-white/10 bg-white/[0.02] p-4 text-center text-sm font-bold text-[#5c5955]" title="{{ __('طلبات واتساب معطّلة من الملف الشخصي') }}">
                    <span class="text-2xl">💬</span>
                    {{ __('واتساب') }}
                </span>
            @else
                <span class="inline-flex cursor-not-allowed flex-col items-center justify-center gap-2 rounded-lg border border-white/10 bg-white/[0.02] p-4 text-center text-sm font-bold text-[#5c5955]">
                    <span class="text-2xl">💬</span>
                    {{ __('واتساب') }}
                </span>
            @endif
        </div>

        @if(!$wa)
            <p class="mt-3 text-xs text-[#9a9690]">{{ __('أضف رقم واتساب من إعدادات الملف الشخصي.') }}</p>
        @elseif($wa && ! $waOrdersOn)
            <p class="mt-3 text-xs text-[#9a9690]">{{ __('تفعيل «إرسال الطلبات عبر واتساب» في الملف الشخصي لإظهار الرابط هنا وللزبائن.') }}</p>
        @endif
    </div>
</div>

<div class="h-8"></div>
