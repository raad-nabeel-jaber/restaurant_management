{{-- Flowbite: بطاقة باركود --}}
<div id="restaurant-qr"
     class="dash-card dash-card-hover flex scroll-mt-24 flex-col items-center justify-between gap-4 p-6">
    <div class="text-center">
        <h2 class="mb-1 text-base font-bold text-[#f0ece3]">{{ __('باركود مطعمك') }}</h2>
        <p class="text-xs text-[#9a9690]">{{ __('اطبعه وضعه على الطاولات') }}</p>
    </div>

    <div class="rounded-2xl bg-white p-4 ring-1 ring-white/15">
        <img src="{{ $qrCodeUrl }}" alt="{{ __('رمز الاستجابة السريعة للمنيو') }}" class="h-28 w-28 object-contain" width="112" height="112">
    </div>

    <div class="w-full text-center">
        <div class="mb-1 text-xs font-bold text-[#9a9690]">{{ parse_url($menuUrl, PHP_URL_HOST) ?? config('app.url') }}/</div>
        <div class="mb-3 text-sm font-black text-[#f5a623]">{{ $restaurant->slug }}</div>
        <div class="flex gap-2">
            <button type="button"
                    data-copy-menu-url="{{ $menuUrl }}"
                    class="flex-1 rounded-xl bg-[#101118]/90 px-3 py-2 text-xs font-bold text-[#f0ece3] shadow-md ring-1 ring-white/[0.06] transition-all duration-300 ease-in-out hover:scale-[1.02] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#f5a623]/50">
                📋 {{ __('نسخ الرابط') }}
            </button>
            <button type="button"
               onclick="downloadQRPdf()"
               class="flex-1 rounded-xl bg-[#f5a623] px-3 py-2 text-center text-xs font-bold text-[#1a1000] shadow-md transition-all duration-300 ease-in-out hover:scale-[1.02] hover:bg-[#fbb935] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#f5a623]/50">
                ⬇ {{ __('تحميل PDF') }}
            </button>
        </div>
    </div>
</div>

<!-- Container for PDF rendering (Hidden from view but accessible for rendering) -->
<div style="width: 0; height: 0; overflow: hidden;">
    <!-- Safe dimensions for single page A4 -->
    <div id="pdf-content" style="width: 750px; height: 1060px; background-color: #ffffff; padding: 40px; box-sizing: border-box; font-family: 'Cairo', sans-serif;">
        <div style="border: 4px solid #f5a623; border-radius: 30px; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; box-sizing: border-box; background-color: #fafafa;">
            
            <!-- Restaurant Name -->
            <h1 style="font-size: 50px; font-weight: 900; color: #111827; margin: 0 0 15px 0; text-align: center; line-height: 1.2;">
                {{ $restaurant->name }}
            </h1>
            
            <!-- Subtitle -->
            <p style="font-size: 26px; color: #4b5563; margin: 0 0 50px 0; text-align: center;">
                {{ __('امسح الباركود لمشاهدة المنيو والطلب') }}
            </p>
            
            <!-- QR Code Wrapper -->
            <div style="background: #ffffff; padding: 25px; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); margin-bottom: 50px; border: 1px solid #e5e7eb;">
                <div id="pdf-qrcode" style="width: 330px; height: 330px; display: flex; align-items: center; justify-content: center;"></div>
            </div>
            
            <!-- URL Badge -->
            <div style="background: #f5a623; color: #ffffff; padding: 15px 40px; border-radius: 50px; font-size: 22px; font-weight: bold; text-align: center; box-shadow: 0 4px 15px rgba(245, 166, 35, 0.4);">
                {{ parse_url($menuUrl, PHP_URL_HOST) ?? config('app.url') }}/{{ $restaurant->slug }}
            </div>
            
            <p style="font-size: 16px; color: #9ca3af; margin-top: auto; text-align: center; font-weight: 600;">
                {{ __('Powered by MenuSnap') }}
            </p>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
let qrGenerated = false;

function generateQR() {
    if (qrGenerated) return;
    const qrContainer = document.getElementById("pdf-qrcode");
    qrContainer.innerHTML = ''; // clear if any
    new QRCode(qrContainer, {
        text: "{{ $menuUrl }}",
        width: 330,
        height: 330,
        colorDark : "#111827",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
    qrGenerated = true;
}

function downloadQRPdf() {
    generateQR();
    
    if (typeof showToast === 'function') {
        showToast('{{ __('جاري تجهيز ملف الـ PDF...') }}');
    }

    setTimeout(() => {
        const element = document.getElementById('pdf-content');
        const opt = {
            margin:       0,
            filename:     '{{ $restaurant->slug }}-barcode.pdf',
            image:        { type: 'jpeg', quality: 1 },
            html2canvas:  { scale: 2, logging: false, useCORS: true },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save().then(() => {
            if (typeof showToast === 'function') {
                showToast('{{ __('تم تحميل الملف بنجاح ✓') }}');
            }
        });
    }, 300);
}
</script>
