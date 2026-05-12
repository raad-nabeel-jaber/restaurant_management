<x-guest-layout :title="__('كلمة مرور جديدة').' — '.config('app.name')">
    <div class="auth-card-header">
        <h1 class="auth-title">{{ __('تعيين كلمة مرور جديدة') }}</h1>
        <p class="auth-subtitle">{{ __('اختر كلمة مرور قوية لمطعمك.') }}</p>
    </div>

    <form class="auth-form" method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="auth-label">{{ __('البريد الإلكتروني') }}</label>
            <input
                id="email"
                class="auth-input auth-input-readonly"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                autocomplete="username"
                readonly
            />
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        <div>
            <label for="password" class="auth-label">{{ __('كلمة المرور الجديدة') }}</label>
            <input
                id="password"
                class="auth-input"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        <div>
            <label for="password_confirmation" class="auth-label">{{ __('تأكيد كلمة المرور') }}</label>
            <input
                id="password_confirmation"
                class="auth-input"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="auth-error" />
        </div>

        <button type="submit" class="auth-btn-primary">{{ __('تحديث كلمة المرور') }}</button>

        <p class="auth-switch">
            <a href="{{ route('login') }}">{{ __('العودة لتسجيل الدخول') }}</a>
        </p>
    </form>
</x-guest-layout>
