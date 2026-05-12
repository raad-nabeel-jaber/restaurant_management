<x-dashboard-layout title="تعديل قسم">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#f0ece3]">{{ __('تعديل القسم') }}</h1>
            <p class="mt-1 text-sm text-[#9a9690]">{{ __('تحديث بيانات واسم القسم') }}</p>
        </div>
        <a href="{{ route('categories.index') }}" class="text-sm font-medium text-[#9a9690] transition-colors hover:text-[#f0ece3]">
            &larr; {{ __('العودة للأقسام') }}
        </a>
    </div>

    <div class="max-w-3xl dash-card dash-card-hover overflow-hidden rounded-2xl">
        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('اسم القسم') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="field-input mt-1" required autofocus>
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sort_order" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('ترتيب العرض') }}</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" class="field-input mt-1">
                    <p class="mt-1 text-xs text-[#9a9690]">{{ __('رقم يحدد ترتيب ظهور القسم في المنيو (الأقل يظهر أولاً).') }}</p>
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="inline-flex justify-center rounded-xl bg-[#f5a623] px-6 py-2.5 text-sm font-bold text-[#1a1000] shadow-md transition-all duration-300 ease-in-out hover:scale-[1.02] hover:bg-[#fbb935] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#f5a623]/50">
                    {{ __('تحديث القسم') }}
                </button>
            </div>
        </form>
    </div>
</x-dashboard-layout>
