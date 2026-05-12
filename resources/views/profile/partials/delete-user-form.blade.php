<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-[#f0ece3]">{{ __('حذف الحساب') }}</h2>
        <p class="mt-1 text-sm text-[#9a9690]">
            {{ __('بعد الحذف تُحذف كل بياناتك والمطعم والطلبات بشكل دائم. تأكد أنك لا تحتاج أي نسخة احتياطية.') }}
        </p>
    </header>

    <button
        type="button"
        class="inline-flex items-center justify-center rounded-lg border border-red-500/40 bg-red-500/10 px-4 py-2.5 text-sm font-bold text-red-400 transition hover:bg-red-500/20 focus:outline-none focus:ring-2 focus:ring-red-500/30"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('حذف الحساب نهائياً') }}</button>

    <x-modal
        name="confirm-user-deletion"
        :show="$errors->userDeletion->isNotEmpty()"
        focusable
        panel-class="mb-6 rounded-xl border border-white/10 bg-[#1a1d23] overflow-hidden shadow-2xl shadow-black/40 transform transition-all sm:w-full sm:max-w-md sm:mx-auto"
    >
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-[#f0ece3]">
                {{ __('تأكيد حذف الحساب؟') }}
            </h2>

            <p class="mt-2 text-sm text-[#9a9690]">
                {{ __('أدخل كلمة مرورك لتأكيد الحذف الدائم.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">{{ __('كلمة المرور') }}</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="field-input w-full sm:w-3/4"
                    placeholder="{{ __('كلمة المرور') }}"
                    autocomplete="current-password"
                >
        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-400" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="rounded-lg border border-white/10 bg-[#131519] px-4 py-2.5 text-sm font-bold text-[#f0ece3] hover:bg-white/5" x-on:click="$dispatch('close')">
                    {{ __('إلغاء') }}
                </button>
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/40">
                    {{ __('حذف نهائي') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
