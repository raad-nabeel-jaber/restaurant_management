{{-- Flowbite: هيكل Modal + أزرار Flowbite --}}
<div id="order-detail-modal"
     tabindex="-1"
     aria-hidden="true"
     class="fixed inset-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-x-hidden overflow-y-auto p-4 md:inset-0">
    <div class="relative max-h-full w-full max-w-md p-0">
        <div class="relative rounded-xl border border-white/10 bg-[#1a1d23] p-6 shadow-xl shadow-black/40">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-lg font-bold text-[#f0ece3]" id="modalOrderTitle">{{ __('تفاصيل الطلب') }}</h3>
                <button type="button"
                        data-close-order-modal
                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-[#9a9690] hover:bg-white/5">
                    ✕
                </button>
            </div>
            <div id="modalContent"></div>
            <div class="mt-5 flex gap-3">
                <button type="button"
                        data-close-order-modal
                        class="flex-1 rounded-lg border border-white/10 bg-[#131519] px-5 py-2.5 text-sm font-bold text-[#f0ece3] hover:bg-white/5 focus:outline-none focus:ring-2 focus:ring-[#f5a623]/30">
                    {{ __('إغلاق') }}
                </button>
                <button type="button"
                        id="modalAcceptBtn"
                        class="flex-1 rounded-lg bg-[#f5a623] px-5 py-2.5 text-sm font-bold text-[#1a1000] hover:bg-[#fbb935] focus:outline-none focus:ring-2 focus:ring-[#f5a623]/35">
                    ✓ {{ __('قبول الطلب') }}
                </button>
            </div>
        </div>
    </div>
</div>
