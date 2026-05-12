<footer>
    <div class="footer-left">
        <div class="logo-icon">M</div>
        <span class="footer-text">{{ config('app.name', 'MenuSnap') }} — رقمنة المطاعم العربية</span>
    </div>
    <ul class="footer-links">
        <li><a href="#">سياسة الخصوصية</a></li>
        <li><a href="#">الشروط والأحكام</a></li>
        <li><a href="#">تواصل معنا</a></li>
    </ul>
    <div class="footer-meta">
        <span class="footer-text">© {{ now()->year }} <a href="{{ url('/') }}">{{ config('app.name', 'MenuSnap') }}</a></span>
        <span class="footer-text footer-credit">مبرمج المشروع: Raad Jaber</span>
    </div>
</footer>
