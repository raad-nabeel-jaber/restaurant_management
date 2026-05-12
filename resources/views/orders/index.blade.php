@push('head')
    <script>
        window.__DASHBOARD__ = @json($dashboardClient);
    </script>
@endpush

<x-dashboard-layout
    :title="__('الطلبات')"
    :restaurant="$restaurant"
    :menu-url="$menuUrl"
    :pending-orders-count="$pendingOrdersCount"
>
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-[#f0ece3]">{{ __('الطلبات الحية') }}</h1>
        <p class="mt-1 text-sm text-[#9a9690]">{{ __('متابعة الطلبات وتحديث الحالة') }}</p>
    </div>

    @include('dashboard.partials.orders-table')
</x-dashboard-layout>
