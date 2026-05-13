{{-- شريط ملخص واحد بدل شبكة بطاقات متطابقة (Impeccable) --}}
<div class="mb-6">
    <div
        class="dash-card dash-card-hover overflow-hidden rounded-2xl p-0 ring-1 ring-white/[0.07]"
        style="background: var(--bg3)"
    >
        <div class="flex flex-col gap-6 p-5 sm:p-6 lg:flex-row lg:items-stretch lg:justify-between lg:gap-8">
            <div class="flex min-w-0 flex-1 flex-col gap-6 sm:flex-row sm:items-end sm:gap-10">
                <div class="min-w-0 flex-1">
                    <p
                        class="text-[11px] font-bold uppercase tracking-[0.2em]"
                        style="color: var(--text3)"
                    >
                        {{ __('طلبات اليوم') }}
                    </p>
                    <p
                        class="mt-1 text-4xl font-black tabular-nums tracking-tight"
                        style="color: var(--text)"
                    >
                        {{ $todayOrdersCount }}
                    </p>
                    <p class="mt-2 text-xs font-semibold" style="color: var(--text2)">
                        <span style="color: var(--green)">●</span>
                        {{ $acceptedOrdersCount }} {{ __('قيد التجهيز') }}
                    </p>
                </div>
                <div class="hidden h-16 w-px shrink-0 self-center bg-white/10 sm:block" aria-hidden="true"></div>
                <div class="min-w-0 flex-1">
                    <p
                        class="text-[11px] font-bold uppercase tracking-[0.2em]"
                        style="color: var(--text3)"
                    >
                        {{ __('إيرادات اليوم') }}
                    </p>
                    <p
                        class="mt-1 text-[2.1rem] font-black leading-none tabular-nums sm:text-4xl"
                        style="color: var(--amber)"
                    >
                        {{ number_format($todayRevenue, 1) }} <span class="text-xs font-bold opacity-70">د.أ</span>
                    </p>
                    <p class="mt-2 text-xs" style="color: var(--text2)">
                        {{ __('متوسط الطلب: :avg د.أ', ['avg' => number_format($todayAvgOrder, 1)]) }}
                    </p>
                </div>
            </div>

            <div
                class="flex shrink-0 flex-col gap-4 border-t border-white/10 pt-5 lg:w-72 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0"
            >
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p
                            class="text-[11px] font-bold uppercase tracking-[0.2em]"
                            style="color: var(--text3)"
                        >
                            {{ __('طلبات معلقة') }}
                        </p>
                        <p
                            class="mt-1 text-2xl font-black tabular-nums"
                            style="color: var(--text)"
                        >
                            {{ $pendingOrdersCount }}
                        </p>
                    </div>
                    @if ($pendingOrdersCount > 0)
                        <span
                            class="shrink-0 rounded-full px-2.5 py-1 text-[10px] font-bold"
                            style="background: var(--amber-dim); color: var(--amber)"
                        >
                            {{ __('يتطلب انتباهك') }}
                        </span>
                    @else
                        <span class="shrink-0 text-xs font-semibold" style="color: var(--text2)">
                            {{ __('لا يوجد طلبات معلقة') }}
                        </span>
                    @endif
                </div>

                <div class="h-px w-full bg-white/10" aria-hidden="true"></div>

                <div class="flex items-end justify-between gap-3">
                    <div class="min-w-0">
                        <p
                            class="text-[11px] font-bold uppercase tracking-[0.2em]"
                            style="color: var(--text3)"
                        >
                            {{ __('أصناف في المنيو') }}
                        </p>
                        <p
                            class="mt-1 text-2xl font-black tabular-nums"
                            style="color: var(--text)"
                        >
                            {{ $productsCount }}
                        </p>
                    </div>
                    <a
                        href="{{ route('products.create') }}"
                        class="shrink-0 text-sm font-semibold hover:underline"
                        style="color: var(--amber)"
                    >
                        {{ __('إضافة صنف') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
