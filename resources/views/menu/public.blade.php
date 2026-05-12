<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>المنيو | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/menu-public.css', 'resources/js/menu-public.js'])
</head>
<body data-menu-barcode="{{ $barcode }}">
    <div class="phone" id="app">
        <div class="cover">
            <img id="restaurantCover" class="cover-img" alt="cover" />
            <div class="cover-overlay"></div>
            <div class="cover-actions">
                <a href="{{ url('/') }}" class="cover-btn" title="رجوع">←</a>
                <button class="cover-btn" id="shareBtn" title="مشاركة">⬆</button>
            </div>
            <div class="cover-info">
                <div class="restaurant-logo" id="restaurantLogoEmoji">🍽️</div>
                <div class="restaurant-meta">
                    <div class="restaurant-name" id="restaurantName">جاري تحميل المنيو...</div>
                    <div class="restaurant-sub">
                        <span class="status-dot"></span>
                        <span id="restaurantSubtext">متاح الآن</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="cats">
            <div class="cats-scroll" id="catScroll"></div>
        </div>

        <div id="menuContent">
            <div class="section-head">
                <span class="section-head-title" id="menuMessage">جاري تحميل المنتجات...</span>
            </div>
        </div>

        <div class="cart-bar" id="cartBar">
            <div class="cart-left">
                <span class="cart-badge" id="cartCount">0</span>
                <span class="cart-label">عرض السلة</span>
            </div>
            <span class="cart-total" id="cartTotal">0.000 د.أ</span>
        </div>
    </div>

    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-sheet">
            <div class="modal-handle"></div>
            <div class="modal-head">
                <span class="modal-title">تفاصيل طلبك 🛒</span>
                <button class="modal-close" id="closeModalBtn">✕</button>
            </div>

            <div class="order-form-wrap" id="orderFormWrap">
                <div class="cart-items" id="cartItemsList"></div>
                <div class="modal-divider"></div>
                <div class="order-summary">
                    <div class="summary-row"><span>المجموع الفرعي</span><span id="summarySubtotal">0.000 د.أ</span></div>
                    <div class="summary-row"><span>التوصيل</span><span id="summaryDelivery" style="color:var(--green)">مجاني</span></div>
                    <div class="summary-row total"><span>الإجمالي</span><span id="summaryTotal">0.000 د.أ</span></div>
                </div>

                <form id="checkoutForm">
                    <div class="form-section">
                        <div class="form-label">بيانات الاستلام</div>
                        <div class="type-toggle">
                            <button type="button" class="type-btn active" data-type="delivery"><span class="type-btn-icon">🛵</span><span class="type-btn-label">توصيل لباب</span></button>
                            <button type="button" class="type-btn" data-type="dine_in"><span class="type-btn-icon">🪑</span><span class="type-btn-label">داخل المطعم</span></button>
                        </div>
                        <div class="input-group">
                            <div class="input-row two">
                                <div class="field"><label class="field-label">الاسم</label><input type="text" id="custName" name="customer_name" placeholder="اسمك الكريم" required></div>
                                <div class="field"><label class="field-label">رقم الهاتف</label><input type="tel" id="custPhone" name="customer_phone" placeholder="07X XXX XXXX" required></div>
                            </div>
                            <div class="field" id="addressField"><label class="field-label">عنوان التوصيل (اختياري)</label><input type="text" id="custAddress" placeholder="الحي، الشارع، رقم البيت..."></div>
                            <div class="field table-field" id="tableField"><label class="field-label">رقم الطاولة</label><input type="number" id="custTable" name="table_number" placeholder="مثال: 7" min="1"></div>
                            <div class="field"><label class="field-label">ملاحظات (اختياري)</label><textarea id="custNotes" placeholder="بدون بصل، إضافة صلصة حارة..."></textarea></div>
                        </div>
                    </div>
                    <div class="submit-area">
                        <p id="checkoutError" class="hidden-error"></p>
                        <button class="submit-btn" id="submitBtn" type="submit"><span class="submit-icon">💬</span> أرسل الطلب عبر واتساب</button>
                    </div>
                </form>
            </div>

            <div class="success-state" id="successState">
                <div class="success-icon">✅</div>
                <div class="success-title">تم إرسال طلبك!</div>
                <div class="success-num" id="successOrderNum">طلب #0000</div>
                <div class="success-sub" id="successSubtext">سيتم تحويلك لواتساب لإرسال الطلب للمطعم مباشرة.</div>
                <button type="button" id="resetOrderBtn" class="secondary-btn">طلب جديد</button>
            </div>
        </div>
    </div>
</body>
</html>
