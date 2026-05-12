<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-[#f0ece3]">{{ __('تغيير كلمة المرور') }}</h2>
        <p class="mt-1 text-sm text-[#9a9690]">
            {{ __('استخدم كلمة مرور طويلة وقوية لحماية حسابك.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('كلمة المرور الحالية') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="field-input" autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('كلمة المرور الجديدة') }}</label>
            <input id="update_password_password" name="password" type="password" class="field-input" autocomplete="new-password">
            @error('password', 'updatePassword')
                <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('تأكيد كلمة المرور') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="field-input" autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-wrap items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-[#f5a623] px-5 py-2.5 text-sm font-bold text-[#1a1000] transition hover:bg-[#fbb935] focus:outline-none focus:ring-2 focus:ring-[#f5a623]/40">
                {{ __('تحديث كلمة المرور') }}
            </button>

            @if (session('status') === 'password-updated')
                <p class="text-sm font-medium text-[#25d366]">{{ __('تم الحفظ.') }}</p>
            @endif
        </div>
    </form>
</section>
