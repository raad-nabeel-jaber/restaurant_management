<x-guest-layout :title="__('إنشاء حساب').' — '.config('app.name')">
    <div class="auth-card-header">
        <h1 class="auth-title">{{ __('إنشاء حساب جديد') }}</h1>
        <p class="auth-subtitle">{{ __('أنشئ حساب المطعم وابدأ بإدارة قائمتك وطلباتك بسهولة.') }}</p>
    </div>

    <form class="auth-form auth-form--register" method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name" class="auth-label">{{ __('الاسم الكامل') }}</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                class="auth-input"
                placeholder="{{ __('أدخل اسمك الكامل') }}"
            />
            @error('name')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="auth-label">{{ __('البريد الإلكتروني') }}</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                class="auth-input"
                placeholder="name@example.com"
            />
            @error('email')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="restaurant_name" class="auth-label">{{ __('اسم المطعم') }}</label>
            <input
                id="restaurant_name"
                name="restaurant_name"
                type="text"
                value="{{ old('restaurant_name') }}"
                required
                class="auth-input"
                placeholder="{{ __('مثال: مطعم الشيف') }}"
            />
            @error('restaurant_name')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="whatsapp_number" class="auth-label">{{ __('رقم الواتساب') }}</label>
            <input
                id="whatsapp_number"
                name="whatsapp_number"
                type="text"
                value="{{ old('whatsapp_number') }}"
                required
                class="auth-input"
                placeholder="{{ __('مثال: 0790000000') }}"
            />
            @error('whatsapp_number')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="auth-label">{{ __('كلمة المرور') }}</label>
            <input
                id="password"
                name="password"
                type="password"
                required
                autocomplete="new-password"
                class="auth-input"
                placeholder="••••••••"
            />
            @error('password')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="auth-label">{{ __('تأكيد كلمة المرور') }}</label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                class="auth-input"
                placeholder="••••••••"
            />
            @error('password_confirmation')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="auth-btn-primary">{{ __('تسجيل') }}</button>

        <p class="auth-switch">
            {{ __('لديك حساب بالفعل؟') }}
            <a href="{{ route('login') }}">{{ __('تسجيل الدخول') }}</a>
        </p>
    </form>
</x-guest-layout>
