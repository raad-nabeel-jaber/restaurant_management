{{-- Flowbite: Toast بسيط --}}
<div id="toast"
     class="pointer-events-none fixed top-5 end-5 z-[200] flex px-4 opacity-0 transition-all duration-300 invisible -translate-y-4"
     role="status"
     aria-live="polite">
    <div class="pointer-events-auto flex items-center gap-3 rounded-xl bg-gradient-to-r from-[#e8821a] to-[#f5a623] p-4 shadow-2xl shadow-[#f5a623]/25 min-w-[280px]">
        <div class="inline-flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-black/10 text-[#1a1000]">
            <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <span id="toast-text" class="flex-1 text-sm font-bold text-[#1a1000] tracking-wide"></span>
    </div>
</div>

@if(session('success') || session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const msg = @json(session('success') ?? session('error'));
            // Since dashboard.js has showToast function globally or might not, let's just implement the logic here for the session msg.
            const root = document.getElementById('toast');
            const text = document.getElementById('toast-text');
            if (root && text) {
                text.textContent = msg;
                root.classList.remove('invisible', 'opacity-0', '-translate-y-4');
                root.classList.add('opacity-100', 'translate-y-0');
                setTimeout(() => {
                    root.classList.add('invisible', 'opacity-0', '-translate-y-4');
                    root.classList.remove('opacity-100', 'translate-y-0');
                }, 3000);
            }
        });
    </script>
@endif
