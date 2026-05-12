<x-dashboard-layout :title="__('بيانات المطعم والحساب')" :restaurant="$restaurant" :menu-url="$menuUrl" :pending-orders-count="$pendingOrdersCount">
    <div class="mx-auto max-w-3xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#f0ece3]">{{ __('بيانات المطعم والحساب') }}</h1>
            <p class="mt-1 text-sm text-[#9a9690]">{{ __('حدّث اسم المطعم، رابط المنيو، واتساب، وحساب الدخول.') }}</p>
        </div>

        <div class="dash-card dash-card-hover rounded-2xl p-6 sm:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="dash-card dash-card-hover rounded-2xl p-6 sm:p-8">
            @include('profile.partials.update-password-form')
        </div>

        <div class="dash-card dash-card-hover rounded-2xl p-6 sm:p-8">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-dashboard-layout>
