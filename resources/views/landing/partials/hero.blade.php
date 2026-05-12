<section class="hero">
    <div class="orb orb-amber"></div>
    <div class="orb orb-green"></div>
    <div class="orb orb-small"></div>
    <div class="hero-grid"></div>
    <div class="hero-badge"><span class="badge-dot"></span> رقمي · سريع · بدون عمولات</div>
    <h1>مطعمك في جيب<br><span class="highlight">كل زبون</span></h1>
    <p>منيو رقمي ذكي + طلبات عبر واتساب مباشرة.<br>لا تطبيق، لا عمولات، لا تعقيد — فقط باركود واحد يغير كل شيء.</p>
    <div class="hero-actions">
        @auth
            <a href="{{ route('dashboard') }}" class="btn-hero-primary">🚀 اذهب إلى لوحة التحكم</a>
        @else
            <a href="{{ route('register') }}" class="btn-hero-primary">🚀 ابدأ مجاناً الآن</a>
        @endauth
        <a href="#how" class="btn-hero-secondary">▶ شاهد كيف يعمل</a>
    </div>

    <div class="hero-visual">
        <div class="phone-mockup">
            <div class="phone-bar">
                <div class="phone-dots"><span></span><span></span><span></span></div>
                <div class="phone-url">menusnap.app/burger-house</div>
            </div>
            <div class="phone-screen" style="position:relative; padding-bottom: 60px;">
                <div class="menu-card"><div class="menu-card-img bg1">🍔</div><div class="menu-card-name">برغر كلاسيك</div><div class="menu-card-price">4.5 د.أ</div><button class="menu-card-add">+ أضف</button></div>
                <div class="menu-card"><div class="menu-card-img bg2">🥗</div><div class="menu-card-name">سلطة فريش</div><div class="menu-card-price">3.2 د.أ</div><button class="menu-card-add">+ أضف</button></div>
                <div class="menu-card"><div class="menu-card-img bg3">🌮</div><div class="menu-card-name">تاكو مكسيكي</div><div class="menu-card-price">5.0 د.أ</div><button class="menu-card-add">+ أضف</button></div>
                <div class="menu-card"><div class="menu-card-img bg4">🍕</div><div class="menu-card-name">بيتزا مارغريتا</div><div class="menu-card-price">7.5 د.أ</div><button class="menu-card-add">+ أضف</button></div>
                <div class="menu-card"><div class="menu-card-img bg5">🧃</div><div class="menu-card-name">عصير طازج</div><div class="menu-card-price">2.0 د.أ</div><button class="menu-card-add">+ أضف</button></div>
                <div class="menu-card"><div class="menu-card-img bg6">🍰</div><div class="menu-card-name">كيك الشوكولاتة</div><div class="menu-card-price">3.8 د.أ</div><button class="menu-card-add">+ أضف</button></div>
                <div class="cart-float">📲 اطلب عبر واتساب — 2 عناصر · 9.7 د.أ</div>
            </div>
        </div>
    </div>

    <div class="hero-stats">
        <div class="stat-item"><span class="stat-num">+500</span><div class="stat-label">مطعم يستخدم المنصة</div></div>
        <div class="stat-divider"></div>
        <div class="stat-item"><span class="stat-num">0%</span><div class="stat-label">عمولة على الطلبات</div></div>
        <div class="stat-divider"></div>
        <div class="stat-item"><span class="stat-num">3 دقائق</span><div class="stat-label">وقت الإعداد</div></div>
        <div class="stat-divider"></div>
        <div class="stat-item"><span class="stat-num">24/7</span><div class="stat-label">متاح دائماً</div></div>
    </div>
</section>
