{{-- Flowbite: Drawer + إشعارات + زر --}}
<header class="flex h-16 flex-shrink-0 items-center justify-between border-b border-white/10 bg-[#13151a]/95 px-6 backdrop-blur supports-[backdrop-filter]:bg-[#13151a]/85">
    <div class="flex items-center gap-4">
        <button type="button"
                class="inline-flex items-center rounded-lg p-2 text-xl text-[#9a9690] hover:bg-white/5 focus:outline-none focus:ring-2 focus:ring-[#f5a623]/30 md:hidden"
                data-drawer-target="dashboard-drawer"
                data-drawer-toggle="dashboard-drawer"
                data-drawer-placement="right"
                data-drawer-backdrop="true"
                aria-controls="dashboard-drawer">
            ☰
        </button>
        <div class="flex items-center gap-2">
            <div class="live-dot"></div>
            <span class="text-sm font-bold text-green-400">{{ __('مباشر') }}</span>
            <span class="text-sm text-[#9a9690]">— {{ __('تحديث الطلبات من الخادم') }}</span>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <div class="relative">
            <button id="dashboard-notif-trigger"
                    type="button"
                    data-dropdown-toggle="dashboard-notif-dropdown"
                    data-dropdown-placement="bottom-end"
                    class="relative inline-flex h-10 w-10 items-center justify-center rounded-lg border border-white/10 bg-[#1a1d23] text-lg text-[#9a9690] hover:bg-white/5 focus:outline-none focus:ring-2 focus:ring-[#f5a623]/30">
                🔔
                <span id="dashboard-notif-badge"
                      class="notif-badge @if(($pendingOrdersCount ?? 0) === 0) hidden @endif"
                      aria-live="polite">{{ ($pendingOrdersCount ?? 0) > 9 ? '9+' : (string) ($pendingOrdersCount ?? 0) }}</span>
            </button>
            <div id="dashboard-notif-dropdown"
                 class="z-50 hidden w-56 divide-y divide-white/10 rounded-lg border border-white/10 bg-[#1a1d23] py-2 shadow-lg shadow-black/30">
                <div id="dashboard-notif-panel">
                    @if(($pendingOrdersCount ?? 0) > 0)
                        <p class="px-4 py-2 text-sm text-[#f0ece3]">
                            {{ __('لديك :count طلبات معلقة', ['count' => $pendingOrdersCount]) }}
                        </p>
                        <a href="{{ route('orders.index') }}"
                           class="block px-4 py-2 text-sm font-medium text-[#f5a623] hover:bg-white/5">
                            {{ __('عرض الطلبات') }} →
                        </a>
                    @else
                        <p class="px-4 py-3 text-sm text-[#9a9690]">{{ __('لا إشعارات جديدة') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <a href="{{ $menuUrl }}"
           target="_blank"
           rel="noopener noreferrer"
           class="hidden items-center gap-2 rounded-lg bg-[#f5a623] px-4 py-2 text-sm font-bold text-[#1a1000] hover:bg-[#fbb935] focus:outline-none focus:ring-2 focus:ring-[#f5a623]/35 sm:inline-flex">
            <span>📱</span> {{ __('منيوي') }}
        </a>
    </div>
</header>
