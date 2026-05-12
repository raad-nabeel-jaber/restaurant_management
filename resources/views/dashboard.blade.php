@push('head')
    <script>
        window.__DASHBOARD__ = @json($dashboardClient);
    </script>
@endpush

<x-dashboard-layout
    :title="__('نظرة عامة')"
    :restaurant="$restaurant"
    :menu-url="$menuUrl"
    :pending-orders-count="$pendingOrdersCount"
>
    @include('dashboard.partials.page-header')
    @include('dashboard.partials.stats')
    @include('dashboard.partials.chart-qr')
    @include('dashboard.partials.bottom-row')
</x-dashboard-layout>
