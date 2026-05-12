<x-guest-layout :title="__('استعادة كلمة المرور').' — '.config('app.name')">
    <div class="auth-card-header">
        <h1 class="auth-title">{{ __('نسيت كلمة المرور؟') }}</h1>
        <p class="auth-subtitle">
            {{ __('أدخل بريدك الإلكتروني وسنرسل لك رابطاً لاختيار كلمة مرور جديدة.') }}
        </p>
    </div>

    <x-auth-session-status class="auth-flash-success" :status="session('status')" />

    <form class="auth-form" method="POST" action="{{ route('password.email') }}">
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
                placeholder="name@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        <button type="submit" class="auth-btn-primary">{{ __('إرسال رابط إعادة التعيين') }}</button>

        <p class="auth-switch">
            <a href="{{ route('login') }}">{{ __('العودة لتسجيل الدخول') }}</a>
        </p>

        @if (app()->environment('local'))
            @if (config('mail.default') === 'log')
                <p class="auth-dev-hint">
                    {{ __('وضع التطوير: البريد يُسجَّل في الملف وليس يُرسل — راجع') }}
                    <code class="auth-dev-hint-code">storage/logs/laravel.log</code>
                </p>
            @elseif (in_array(config('mail.mailers.smtp.host'), ['mailpit', '127.0.0.1'], true) && (int) config('mail.mailers.smtp.port') === 1025)
                <p class="auth-dev-hint">
                    {{ __('وضع التطوير (Mailpit): الرسائل لا تصل لصندوقك الحقيقي. افتح صندوق التجارب:') }}
                    <a href="http://localhost:8025" target="_blank" rel="noopener noreferrer" class="auth-dev-hint-link">localhost:8025</a>
                </p>
            @endif
        @endif
    </form>
</x-guest-layout>
