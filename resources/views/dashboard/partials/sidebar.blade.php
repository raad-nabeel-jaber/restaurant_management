{{-- قسم: الشريط الجانبي (سطح المكتب + Flowbite Drawer للجوال) --}}

{{-- سطح المكتب --}}
<aside id="dashboard-sidebar-desktop"
       class="hidden md:flex w-64 flex-shrink-0 flex-col h-full overflow-y-auto dashboard-sidebar-panel">
    @include('dashboard.partials.sidebar-inner', ['menuIdSuffix' => ''])
</aside>

{{-- جوال: درج Flowbite من جهة اليمين (مناسب لـ RTL) --}}
<div id="dashboard-drawer"
     class="md:hidden fixed z-40 h-screen w-[260px] overflow-y-auto dashboard-sidebar-panel border-l border-white/5 transition-transform translate-x-full"
     tabindex="-1"
     aria-labelledby="dashboard-drawer-title">
    <span id="dashboard-drawer-title" class="sr-only">{{ __('قائمة التنقل') }}</span>
    <div class="flex h-full flex-col">
        @include('dashboard.partials.sidebar-inner', ['menuIdSuffix' => '-m'])
    </div>
</div>
