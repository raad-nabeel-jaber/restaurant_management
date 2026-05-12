@push('head')
    <script>
        window.__DASHBOARD__ = @json($dashboardClient);
    </script>
@endpush

<x-dashboard-layout
    :title="__('الباركود')"
    :restaurant="$restaurant"
    :menu-url="$menuUrl"
    :pending-orders-count="$pendingOrdersCount"
>
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-white">{{ __('باركود المنيو') }}</h1>
        <p class="mt-1 text-sm text-gray-400">{{ __('شارك رابط المنيو أو اطبع الرمز للطاولات') }}</p>
    </div>

    <div class="mx-auto max-w-md">
        @include('dashboard.partials.qr-card')
    </div>
</x-dashboard-layout>
