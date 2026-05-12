<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }} — {{ __('لوحة التحكم') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

    @stack('head')
    @auth
        <script>
            window.__DASHBOARD__ = Object.assign(
                {
                    checkNewOrdersUrl: @json(route('orders.checkNew')),
                    initialPendingCount: {{ (int) ($pendingOrdersCount ?? 0) }},
                    ordersPageUrl: @json(route('orders.index')),
                },
                window.__DASHBOARD__ || {}
            );
        </script>
    @endauth
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
</head>
<body class="flex h-screen overflow-hidden bg-[#07080b] font-cairo text-[#f0ece3] antialiased">

    @include('dashboard.partials.sidebar')

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        @include('dashboard.partials.topbar')

        <main class="flex-1 overflow-y-auto bg-[#0a0b0f] p-6">
            {{ $slot }}
        </main>
    </div>

    @include('dashboard.partials.order-modal')
    @include('dashboard.partials.toast')

    @stack('scripts')
</body>
</html>
