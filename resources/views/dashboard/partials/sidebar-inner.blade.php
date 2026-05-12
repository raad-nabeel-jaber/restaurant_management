{{-- محتوى الشريط الجانبي (مشترك بين سطح المكتب والـ drawer) --}}
@php
    $user = auth()->user();
    $initial = mb_substr($restaurant->name ?? $user->name, 0, 1);
    $suffix = $menuIdSuffix ?? '';
@endphp

<div class="flex items-center gap-3 px-5 py-5 border-b border-white/5">
    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-black text-lg flex-shrink-0 brand-gradient shadow-brand">
        M
    </div>
    <div>
        <div class="font-black text-base leading-tight text-menusnap-text">Menu<span class="text-brand-500">Snap</span></div>
        <div class="text-xs text-menusnap-muted">{{ __('لوحة التحكم') }}</div>
    </div>
</div>

<nav class="flex-1 p-3 pt-4">
    <div class="sec-label">{{ __('الرئيسية') }}</div>
    <a href="{{ route('dashboard') }}" @class(['nav-item', 'active' => request()->routeIs('dashboard')])>
        <span class="nav-icon">📊</span> {{ __('نظرة عامة') }}
    </a>
    <a href="{{ route('orders.index') }}" @class(['nav-item', 'active' => request()->routeIs('orders.index')]) data-drawer-hide="dashboard-drawer">
        <span class="nav-icon">🛒</span> {{ __('الطلبات') }}
        @if(($pendingOrdersCount ?? 0) > 0)
            <span class="mr-auto text-xs font-bold px-2 py-0.5 rounded-full bg-brand-500/15 text-brand-500">{{ $pendingOrdersCount }}</span>
        @endif
    </a>
    <a href="{{ route('products.index') }}" @class(['nav-item', 'active' => request()->routeIs('products.*')])>
        <span class="nav-icon">📋</span> {{ __('المنيو') }}
    </a>
    <a href="{{ route('categories.index') }}" @class(['nav-item', 'active' => request()->routeIs('categories.*')])>
        <span class="nav-icon">🏷️</span> {{ __('الأقسام') }}
    </a>

    <div class="sec-label mt-3">{{ __('الإعدادات') }}</div>
    <a href="{{ route('barcode.show') }}" @class(['nav-item', 'active' => request()->routeIs('barcode.show')]) data-drawer-hide="dashboard-drawer">
        <span class="nav-icon">📱</span> {{ __('الباركود') }}
    </a>
    <a href="{{ route('profile.edit') }}" @class(['nav-item', 'active' => request()->routeIs('profile.*')])>
        <span class="nav-icon">🏪</span> {{ __('بيانات المطعم') }}
    </a>
</nav>

<div class="p-3 border-t border-white/5">
    <button id="dashboard-user-menu{{ $suffix }}" type="button"
            data-dropdown-toggle="dashboard-user-dropdown{{ $suffix }}"
            data-dropdown-placement="top"
            class="flex w-full items-center gap-3 p-3 rounded-xl cursor-pointer transition-colors bg-white/[0.03] hover:bg-white/[0.06] text-start">
        <div class="w-9 h-9 rounded-full flex items-center justify-center text-base font-bold flex-shrink-0 bg-brand-500/15 text-brand-500">
            {{ $initial }}
        </div>
        <div class="flex-1 min-w-0">
            <div class="text-sm font-bold truncate text-menusnap-text">{{ $restaurant->name ?? $user->name }}</div>
            <div class="text-xs truncate text-menusnap-muted">{{ $user->email }}</div>
        </div>
        <span class="text-menusnap-muted text-lg shrink-0">⋯</span>
    </button>
    <div id="dashboard-user-dropdown{{ $suffix }}"
         class="z-50 hidden min-w-[11rem] rounded-xl border border-white/10 bg-[#1e2028] py-1 shadow-xl">
        <a href="{{ route('profile.edit') }}"
           class="block px-4 py-2.5 text-sm font-medium text-menusnap-text hover:bg-white/5">
            {{ __('بيانات المطعم') }}
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full px-4 py-2.5 text-start text-sm font-medium text-red-400 hover:bg-white/5">
                {{ __('تسجيل الخروج') }}
            </button>
        </form>
    </div>
</div>
