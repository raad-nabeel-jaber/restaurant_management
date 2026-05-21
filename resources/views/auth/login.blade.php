<x-guest-layout :title="__('تسجيل الدخول').' — '.config('app.name')">
    <div class="auth-card-header">
        <h1 class="auth-title">{{ __('تسجيل الدخول') }}</h1>
        <p class="auth-subtitle">{{ __('أدخل بريدك وكلمة المرور للمتابعة إلى لوحة التحكم.') }}</p>
    </div>

    <x-auth-session-status class="auth-flash-success" :status="session('status')" />

    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email" class="auth-label">{{ __('البريد الإلكتروني') }}</label>
            <input
                id="email"
                class="auth-input"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        <div>
            <label for="password" class="auth-label">{{ __('كلمة المرور') }}</label>
            <input
                id="password"
                class="auth-input"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        <label class="auth-check">
            <input id="remember_me" type="checkbox" name="remember" value="1">
            <span>{{ __('تذكرني') }}</span>
        </label>

        <div class="auth-actions-row">
            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">{{ __('هل نسيت كلمة المرور؟') }}</a>
            @endif
            <button type="submit" class="auth-btn-primary">{{ __('تسجيل الدخول') }}</button>
        </div>
    </form>

    @if (Route::has('register'))
        <p class="auth-switch">
            {{ __('ليس لديك حساب؟') }}
            <a href="{{ route('register') }}">{{ __('إنشاء حساب') }}</a>
        </p>
    @endif
</x-guest-layout>
