<footer class="site-footer">
    <div class="footer-main">
        <div class="footer-brand">
            <a href="{{ url('/') }}" class="nav-logo">
                <div class="logo-icon">M</div>
                <span class="logo-text">Menu<span>Snap</span></span>
            </a>
            <p class="footer-tagline">رقمنة المطاعم العربية بدون عمولات.<br>مطعمك في جيب كل زبون.</p>
        </div>
        
        <div class="footer-nav">
            <div class="footer-nav-col">
                <h4 class="footer-nav-title">الروابط السريعة</h4>
                <ul>
                    <li><a href="#how">كيف يعمل</a></li>
                    <li><a href="#features">المميزات</a></li>
                    <li><a href="#pricing">الأسعار</a></li>
                </ul>
            </div>
            <div class="footer-nav-col">
                <h4 class="footer-nav-title">المساعدة</h4>
                <ul>
                    <li><a href="https://wa.me/962795105700" target="_blank">تواصل معنا عبر واتساب</a></li>
                    <li><a href="#">الأسئلة الشائعة</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <div class="footer-copyright">
            © {{ now()->year }} {{ config('app.name', 'MenuSnap') }}. جميع الحقوق محفوظة.
        </div>
        <div class="footer-legal">
            <a href="#">سياسة الخصوصية</a>
            <a href="#">الشروط والأحكام</a>
        </div>
        <div class="footer-credit">
            تطوير: Raad Jaber
        </div>
    </div>
</footer>
