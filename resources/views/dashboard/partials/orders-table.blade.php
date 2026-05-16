{{-- Flowbite: بطاقة + جدول + مجموعة أزرار للفلترة --}}
<div id="live-orders"
     class="dash-card dash-card-hover mb-6 p-4 md:p-6">
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <h2 class="text-base font-bold text-[#f0ece3]">{{ __('الطلبات الحية') }}</h2>
            <span class="inline-flex items-center gap-2 rounded-full border border-[#25d366]/25 bg-[#25d366]/10 px-3 py-1 text-xs font-medium text-[#25d366]">
                <span class="live-dot h-1.5 w-1.5"></span>
                {{ __('مباشر') }}
            </span>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="inline-flex rounded-xl bg-[#101118]/90 p-1 shadow-inner ring-1 ring-white/[0.05]"
                 role="group"
                 data-order-filters>
                <button type="button"
                        data-filter="all"
                        class="filter-tab rounded-lg px-3 py-1.5 text-xs font-bold shadow-sm transition-all duration-300 ease-in-out hover:scale-[1.02] hover:shadow-md bg-[#f5a623] text-[#1a1000]">
                    {{ __('الكل') }}
                </button>
                <button type="button"
                        data-filter="pending"
                        class="filter-tab rounded-lg px-3 py-1.5 text-xs font-bold text-[#9a9690] transition-all duration-300 ease-in-out hover:scale-[1.02] hover:bg-white/5 hover:shadow-sm">
                    {{ __('معلق') }}
                </button>
                <button type="button"
                        data-filter="accepted"
                        class="filter-tab rounded-lg px-3 py-1.5 text-xs font-bold text-[#9a9690] transition-all duration-300 ease-in-out hover:scale-[1.02] hover:bg-white/5 hover:shadow-sm">
                    {{ __('مقبول') }}
                </button>
                <button type="button"
                        data-filter="cancelled"
                        class="filter-tab rounded-lg px-3 py-1.5 text-xs font-bold text-[#9a9690] transition-all duration-300 ease-in-out hover:scale-[1.02] hover:bg-white/5 hover:shadow-sm">
                    {{ __('ملغى') }}
                </button>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto rounded-xl bg-[#0e0f14]/95 shadow-md ring-1 ring-white/[0.05]">
        <table class="w-full table-fixed text-left text-sm text-[#9a9690] rtl:text-right">
            <thead class="bg-[#1e2028] text-xs font-bold uppercase tracking-wide text-[#9a9690]">
                <tr>
                    <th scope="col" class="w-[6%] min-w-0 px-2 py-3"># {{ __('الطلب') }}</th>
                    <th scope="col" class="w-[17%] min-w-0 px-3 py-3">{{ __('الزبون') }}</th>
                    <th scope="col" class="w-[22%] min-w-0 px-3 py-3">{{ __('الأصناف') }}</th>
                    <th scope="col" class="w-[11%] min-w-0 px-2 py-3">{{ __('النوع') }}</th>
                    <th scope="col" class="w-[9%] min-w-0 px-2 py-3">{{ __('المجموع') }}</th>
                    <th scope="col" class="w-[8%] min-w-0 px-2 py-3">{{ __('الوقت') }}</th>
                    <th scope="col" class="w-[10%] min-w-0 px-2 py-3">{{ __('الحالة') }}</th>
                    <th scope="col" class="w-[17%] min-w-0 px-2 py-3 text-center">{{ __('الإجراءات') }}</th>
                </tr>
            </thead>
            <tbody id="ordersBody">
                <tr class="border-b border-white/[0.07] bg-[#17191f]">
                    <td colspan="8" class="px-6 py-8 text-center text-[#9a9690]">{{ __('جاري التحميل...') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-4 sm:flex-row">
        <span class="text-sm text-[#9a9690]" data-orders-footer>{{ __('—') }}</span>
        <nav aria-label="Page navigation" class="hidden" id="orders-pagination">
            <ul class="inline-flex -space-x-px text-sm">
                <li>
                    <button type="button" data-paginate="prev" class="flex h-8 items-center justify-center rounded-r-lg border border-white/10 bg-[#17191f] px-3 leading-tight text-[#9a9690] hover:bg-white/5 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed">
                        السابق
                    </button>
                </li>
                <li id="pagination-pages" class="flex">
                    <!-- Page numbers injected by JS -->
                </li>
                <li>
                    <button type="button" data-paginate="next" class="flex h-8 items-center justify-center rounded-l-lg border border-white/10 bg-[#17191f] px-3 leading-tight text-[#9a9690] hover:bg-white/5 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed">
                        التالي
                    </button>
                </li>
            </ul>
        </nav>
    </div>
</div>
