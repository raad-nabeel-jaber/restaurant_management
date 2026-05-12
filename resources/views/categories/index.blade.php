<x-dashboard-layout title="الأقسام">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#f0ece3]">{{ __('الأقسام') }}</h1>
            <p class="mt-1 text-sm text-[#9a9690]">{{ __('إدارة تصنيفات قائمة الطعام الخاصة بك') }}</p>
        </div>
        <a href="{{ route('categories.create') }}" class="inline-flex items-center justify-center rounded-xl bg-[#f5a623] px-4 py-2 text-sm font-bold text-[#1a1000] shadow-md transition-all duration-300 ease-in-out hover:scale-[1.02] hover:bg-[#fbb935] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#f5a623]/50">
            <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('إضافة قسم') }}
        </a>
    </div>



    <div class="dash-card dash-card-hover overflow-hidden rounded-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-sm text-[#9a9690]">
                <thead class="bg-[#1e2028] text-xs font-bold uppercase tracking-wide text-[#9a9690]">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold">{{ __('اسم القسم') }}</th>
                        <th scope="col" class="px-6 py-4 font-bold">{{ __('الترتيب') }}</th>
                        <th scope="col" class="px-6 py-4 text-center font-bold">{{ __('الإجراءات') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.07]">
                    @forelse($categories as $category)
                        <tr class="bg-[#17191f] transition-all duration-200 ease-in-out hover:bg-white/[0.06] hover:shadow-inner">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-[#f0ece3]">{{ $category->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full border border-white/[0.07] bg-[#1e2028] px-2.5 py-0.5 text-xs font-medium text-[#9a9690]">
                                    {{ $category->sort_order ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-3 space-x-reverse">
                                    <a href="{{ route('categories.edit', $category->id) }}" class="text-[#f5a623] transition-colors hover:text-[#fbb935]" title="{{ __('تعديل') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <button type="button" onclick="openDeleteModal('{{ route('categories.destroy', $category->id) }}', '{{ __('هل أنت متأكد من حذف هذا القسم؟ قد يتم حذف المنتجات المرتبطة به أو إخفائها.') }}')" class="text-red-400 hover:text-red-300 transition-colors" title="{{ __('حذف') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-[#17191f]">
                            <td colspan="3" class="px-6 py-8 text-center text-[#9a9690]">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="mb-3 h-12 w-12 text-[#55524f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <p class="text-base text-[#f0ece3]">{{ __('لا توجد أقسام مضافة بعد.') }}</p>
                                    <a href="{{ route('categories.create') }}" class="mt-3 text-sm font-medium text-[#f5a623] hover:text-[#fbb935]">
                                        {{ __('أضف قسمك الأول') }} &rarr;
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-delete-modal />
</x-dashboard-layout>
