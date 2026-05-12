<div id="deleteModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900/80 backdrop-blur-sm transition-opacity">
    <div class="relative p-4 w-full max-w-md max-h-full mx-auto mt-20">
        <!-- Modal content -->
        <div class="relative bg-gray-800 rounded-xl shadow-lg border border-gray-700 transform transition-all">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-700 rounded-t">
                <h3 class="text-lg font-semibold text-white">
                    {{ __('تأكيد الحذف') }}
                </h3>
                <button type="button" onclick="closeDeleteModal()" class="text-gray-400 bg-transparent hover:bg-gray-700 hover:text-white rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">{{ __('إغلاق') }}</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 text-center">
                <div class="mx-auto mb-4 flex items-center justify-center w-12 h-12 rounded-full bg-red-900/30">
                    <svg class="text-red-500 w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <h3 id="deleteModalMessage" class="mb-5 text-base font-normal text-gray-300">
                    {{ __('هل أنت متأكد من عملية الحذف؟') }}
                </h3>
                <form id="deleteModalForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-center gap-3">
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-900 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center transition-colors">
                            {{ __('نعم، احذف') }}
                        </button>
                        <button type="button" onclick="closeDeleteModal()" class="text-gray-300 bg-gray-700 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-600 rounded-lg border border-gray-600 text-sm font-medium px-5 py-2.5 hover:text-white focus:z-10 transition-colors">
                            {{ __('إلغاء') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(actionUrl, message) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteModalForm');
        const messageEl = document.getElementById('deleteModalMessage');
        
        form.action = actionUrl;
        if (message) {
            messageEl.textContent = message;
        }
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Add tiny delay for animation
        setTimeout(() => {
            modal.firstElementChild.classList.add('scale-100', 'opacity-100');
            modal.firstElementChild.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        
        modal.firstElementChild.classList.remove('scale-100', 'opacity-100');
        modal.firstElementChild.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
    
    // Initial state for animation
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('deleteModal');
        if (modal) {
            modal.firstElementChild.classList.add('scale-95', 'opacity-0', 'transition-all', 'duration-200');
        }
    });
</script>
