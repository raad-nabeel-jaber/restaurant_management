@php
    $restaurant = $restaurant ?? auth()->user()->getOrCreateRestaurant();
    $menuBase = url('/menu');
@endphp
<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-[#f0ece3]">{{ __('بيانات المطعم والحساب') }}</h2>
        <p class="mt-1 text-sm text-[#9a9690]">
            {{ __('اسم المطعم ورابط المنيو يظهران للزبائن. بريدك واسمك للوحة التحكم فقط.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="border-b border-white/10 pb-6">
            <h3 class="mb-4 text-sm font-bold uppercase tracking-wider text-[#f5a623]">{{ __('المطعم') }}</h3>
            <div class="space-y-5">
                <div>
                    <label for="restaurant_name" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('اسم المطعم') }}</label>
                    <input id="restaurant_name" name="restaurant_name" type="text" class="field-input" value="{{ old('restaurant_name', $restaurant->name) }}" required autocomplete="organization">
                    @error('restaurant_name')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('رابط المنيو (بالإنجليزي)') }}</label>
                    <div class="flex flex-wrap items-center gap-2 sm:flex-nowrap">
                        <span class="shrink-0 rounded-lg border border-white/10 bg-[#131519] px-3 py-2.5 text-xs text-[#9a9690]">{{ $menuBase }}/</span>
                        <input id="slug" name="slug" type="text" class="field-input min-w-0 flex-1 font-mono text-sm" value="{{ old('slug', $restaurant->slug) }}" required pattern="[a-z0-9]+(-[a-z0-9]+)*" title="{{ __('أحرف إنجليزية صغيرة وأرقام وشرطة فقط') }}" autocomplete="off">
                    </div>
                    <p class="mt-1.5 text-xs text-[#5c5955]">{{ __('يُحوّل تلقائياً لصيغة آمنة للرابط. تغييره يغيّر عنوان منيوك.') }}</p>
                    @error('slug')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="whatsapp_number" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('رقم واتساب') }}</label>
                    <input id="whatsapp_number" name="whatsapp_number" type="text" class="field-input" dir="ltr" value="{{ old('whatsapp_number', $restaurant->whatsapp_number) }}" placeholder="9665xxxxxxxx" autocomplete="tel">
                    @error('whatsapp_number')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="order_method" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('طريقة استلام الطلبات من الزبائن') }}</label>
                    <select id="order_method" name="order_method" class="field-input">
                        <option value="whatsapp" @selected(old('order_method', $restaurant->order_method ?? 'whatsapp') === 'whatsapp')>{{ __('واتساب (يفتح رابط واتساب بعد الطلب)') }}</option>
                        <option value="dashboard" @selected(old('order_method', $restaurant->order_method ?? 'whatsapp') === 'dashboard')>{{ __('لوحة التحكم مباشرة (طلب صامت)') }}</option>
                    </select>
                    @error('order_method')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-xs text-[#5c5955]">{{ __('مع «لوحة التحكم» يُحفظ الطلب ويصلك تنبيه في صفحة الطلبات دون إجبار الزبون على واتساب.') }}</p>
                </div>

                <div class="flex items-start gap-3 rounded-lg border border-white/10 bg-[#131519]/80 p-4">
                    <input type="hidden" name="whatsapp_orders_enabled" value="0">
                    <input id="whatsapp_orders_enabled" name="whatsapp_orders_enabled" type="checkbox" value="1" class="mt-0.5 h-4 w-4 shrink-0 rounded border-white/20 bg-[#131519] text-[#25d366] focus:ring-[#25d366]/40" @checked((string) old('whatsapp_orders_enabled', ($restaurant->whatsapp_orders_enabled ?? true) ? '1' : '0') === '1')>
                    <div class="min-w-0">
                        <label for="whatsapp_orders_enabled" class="block text-sm font-medium text-[#f0ece3]">{{ __('تفعيل إرسال الطلبات عبر واتساب') }}</label>
                        <p class="mt-1 text-xs text-[#5c5955]">{{ __('عند الإيقاف لا يُفتح واتساب للزبون حتى لو اخترت «واتساب» أعلاه؛ يُعامل الطلب كطلب للوحة التحكم.') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input id="is_active" name="is_active" type="checkbox" value="1" class="h-4 w-4 rounded border-white/20 bg-[#131519] text-[#f5a623] focus:ring-[#f5a623]/40" @checked((string) old('is_active', $restaurant->is_active ? '1' : '0') === '1')>
                    <label for="is_active" class="text-sm font-medium text-[#f0ece3]">{{ __('المطعم نشط (المنيو يظهر للزبائن)') }}</label>
                </div>
            </div>
        </div>

        <div>
            <h3 class="mb-4 text-sm font-bold uppercase tracking-wider text-[#f5a623]">{{ __('حساب الدخول') }}</h3>
            <div class="space-y-5">
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('اسمك في اللوحة') }}</label>
                    <input id="name" name="name" type="text" class="field-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('البريد الإلكتروني') }}</label>
                    <input id="email" name="email" type="email" class="field-input" dir="ltr" value="{{ old('email', $user->email) }}" required autocomplete="username">
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-3 rounded-lg border border-amber-500/25 bg-amber-500/10 p-3">
                            <p class="text-sm text-[#f0ece3]">
                                {{ __('بريدك غير مؤكد.') }}
                                <button form="send-verification" type="submit" class="font-bold text-[#f5a623] underline hover:text-[#fbb935]">
                                    {{ __('أعد إرسال رابط التأكيد') }}
                                </button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-sm font-medium text-[#25d366]">{{ __('تم إرسال رابط جديد إلى بريدك.') }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-[#f5a623] px-5 py-2.5 text-sm font-bold text-[#1a1000] transition hover:bg-[#fbb935] focus:outline-none focus:ring-2 focus:ring-[#f5a623]/40">
                {{ __('حفظ التغييرات') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm font-medium text-[#25d366]">{{ __('تم الحفظ.') }}</p>
            @endif
        </div>
    </form>
</section>
