<x-dashboard-layout title="إضافة منتج">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#f0ece3]">{{ __('إضافة منتج جديد') }}</h1>
            <p class="mt-1 text-sm text-[#9a9690]">{{ __('أدخل تفاصيل المنتج لإضافته إلى قائمة الطعام') }}</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-sm font-medium text-[#9a9690] transition-colors hover:text-[#f0ece3]">
            &larr; {{ __('العودة للمنتجات') }}
        </a>
    </div>

    <div class="dash-card dash-card-hover overflow-hidden rounded-2xl">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('اسم المنتج') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="field-input mt-1" required autofocus>
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('السعر (د.أ)') }}</label>
                    <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price') }}" class="field-input mt-1" required>
                    @error('price')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('التصنيف') }}</label>
                    <select name="category_id" id="category_id" class="field-input mt-1" required>
                        <option value="">{{ __('اختر التصنيف...') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('صورة المنتج') }}</label>
                    <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full cursor-pointer text-sm text-[#9a9690] file:ml-4 file:rounded-lg file:border-0 file:bg-[#f5a623] file:px-4 file:py-2 file:text-sm file:font-bold file:text-[#1a1000] hover:file:bg-[#fbb935]">
                    @error('image')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="description" class="mb-1.5 block text-sm font-medium text-[#f0ece3]">{{ __('الوصف') }}</label>
                <textarea name="description" id="description" rows="3" class="field-input mt-1">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <div class="flex items-start gap-3">
                    <div class="flex h-5 items-center">
                        <input type="checkbox" name="is_available" id="is_available" value="1" class="h-4 w-4 rounded border-white/20 bg-[#131519] text-[#f5a623] focus:ring-[#f5a623]/40" {{ old('is_available', true) ? 'checked' : '' }}>
                    </div>
                    <div class="text-sm">
                        <label for="is_available" class="font-medium text-[#f0ece3]">{{ __('المنتج متاح') }}</label>
                        <p class="mt-0.5 text-[#9a9690]">{{ __('إذا كان المنتج غير متاح، فلن يظهر في قائمة الطعام للعملاء.') }}</p>
                    </div>
                </div>
                @error('is_available')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="inline-flex justify-center rounded-xl bg-[#f5a623] px-6 py-2.5 text-sm font-bold text-[#1a1000] shadow-md transition-all duration-300 ease-in-out hover:scale-[1.02] hover:bg-[#fbb935] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#f5a623]/50">
                    {{ __('حفظ المنتج') }}
                </button>
            </div>
        </form>
    </div>
</x-dashboard-layout>
