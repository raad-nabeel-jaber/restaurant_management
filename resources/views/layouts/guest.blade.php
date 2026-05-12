<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'MenuSnap') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/css/auth.css', 'resources/js/app.js'])
    </head>
    <body @class(['auth-page', 'auth-page--register' => Route::is('register')])>
        <header class="auth-header">
            <a href="{{ url('/') }}" class="auth-brand">
                <span class="logo-icon" aria-hidden="true">M</span>
                <span class="logo-text">{{ config('app.name', 'MenuSnap') }}</span>
            </a>
            <div class="auth-header-actions">
                @if (Route::is('login'))
                    <a href="{{ route('register') }}" class="btn-auth-secondary">{{ __('إنشاء حساب') }}</a>
                @elseif (Route::is('register'))
                    <a href="{{ route('login') }}" class="btn-auth-secondary">{{ __('تسجيل الدخول') }}</a>
                @endif
                <a href="{{ url('/') }}" class="btn-auth-ghost">{{ __('الرئيسية') }}</a>
            </div>
        </header>

        <main class="auth-main">
            <div @class(['auth-card', 'auth-card--wide' => Route::is('register')])>
                {{ $slot }}
            </div>
        </main>
    </body>
</html>
