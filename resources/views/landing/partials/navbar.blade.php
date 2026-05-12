<nav>
    <a href="{{ url('/') }}" class="nav-logo">
        <div class="logo-icon">M</div>
        <span class="logo-text">Menu<span>Snap</span></span>
    </a>
    <ul class="nav-links">
        <li><a href="#how">كيف يعمل</a></li>
        <li><a href="#features">المميزات</a></li>
        <li><a href="#pricing">الأسعار</a></li>
    </ul>
    <div class="nav-cta">
        @auth
            <a href="{{ route('dashboard') }}" class="btn-primary">لوحة التحكم</a>
        @else
            <a href="{{ route('login') }}" class="btn-ghost">تسجيل الدخول</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-primary">ابدأ مجاناً</a>
            @endif
        @endauth
    </div>
</nav>
