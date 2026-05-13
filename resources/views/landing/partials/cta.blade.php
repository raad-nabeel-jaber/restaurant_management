<section class="cta-section">
    <div class="cta-bg"></div>
    <div class="section-label" style="justify-content: center;">ابدأ اليوم</div>
    <h2>مطعمك يستحق<br><span style="color: var(--amber);">أفضل من هذا</span></h2>
    <p>سجّل مطعمك الآن واحصل على أول شهر مجاناً. لا بطاقة بنكية مطلوبة.</p>
    <div class="cta-actions">
        @auth
            <a href="{{ route('dashboard') }}" class="btn-hero-primary">🚀 اذهب إلى لوحة التحكم</a>
        @else
            <a href="{{ route('register') }}" class="btn-hero-primary">🚀 ابدأ مجاناً — 30 يوم</a>
        @endauth
        <a href="https://wa.me/962795105700" target="_blank" class="whatsapp-btn">💬 تحدث معنا</a>
    </div>
</section>
