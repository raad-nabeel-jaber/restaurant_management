{{-- Flowbite: بطاقات للرسم والباركود --}}
<div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
    <div class="dash-card dash-card-hover p-6 lg:col-span-2">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-[#f0ece3]">{{ __('الطلبات خلال الأسبوع') }}</h2>
                <p class="mt-1 text-xs text-[#9a9690]">{{ __('مقارنة بالأسبوع الماضي') }}</p>
            </div>
        </div>
        <div class="h-[200px]">
            <canvas id="ordersChart"></canvas>
        </div>
    </div>

    @include('dashboard.partials.qr-card')
</div>
