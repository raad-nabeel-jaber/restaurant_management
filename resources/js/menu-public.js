const state = { barcode: null, restaurant: null, categories: [], cart: {}, type: 'delivery' };
const emojiPool = ['🍔', '🍟', '🥤', '🍰', '🥗', '🌮', '🍕', '🍹'];

const money = (n) => `${Number(n).toFixed(3)} د.أ`;
const cartItems = () => Object.values(state.cart);
const cartCount = () => cartItems().reduce((s, i) => s + i.qty, 0);
const cartTotal = () => cartItems().reduce((s, i) => s + i.qty * Number(i.price), 0);

function renderRestaurant() {
    document.getElementById('restaurantName').textContent = state.restaurant?.name || 'المنيو';
    document.getElementById('restaurantSubtext').textContent = 'مفتوح الآن';
    const cover = document.getElementById('restaurantCover');
    cover.src = state.restaurant?.logo || 'https://placehold.co/900x400?text=Menu';
}

function renderTabs() {
    const wrap = document.getElementById('catScroll');
    wrap.innerHTML = '';
    state.categories.forEach((c, idx) => {
        const btn = document.createElement('button');
        btn.className = `cat-tab ${idx === 0 ? 'active' : ''}`;
        btn.dataset.target = `sec-${c.id}`;
        btn.textContent = c.name;
        wrap.appendChild(btn);
    });
}

function counterHtml(productId) {
    const item = state.cart[productId];
    if (!item) return `<button class="add-btn" data-action="add" data-id="${productId}">+</button>`;
    return `<div class="product-counter"><button class="counter-btn" data-action="minus" data-id="${productId}">−</button><span class="counter-num">${item.qty}</span><button class="counter-btn" data-action="add" data-id="${productId}">+</button></div>`;
}

function renderMenu() {
    const content = document.getElementById('menuContent');
    if (!state.categories.length) {
        content.innerHTML = '<div class="section-head"><span class="section-head-title">لا يوجد أصناف حالياً.</span></div>';
        return;
    }

    content.innerHTML = state.categories.map((cat, i) => `
        <div class="section-head" id="sec-${cat.id}">
            <span class="section-head-emoji">${emojiPool[i % emojiPool.length]}</span>
            <span class="section-head-title">${cat.name}</span>
            <span class="section-count">${cat.products.length} أصناف</span>
        </div>
        <div class="products">
            ${cat.products.map((p) => `
                <div class="product-card" data-id="${p.id}">
                    <img class="product-img" src="${p.image || 'https://placehold.co/140x140?text=Item'}" alt="${p.name}">
                    <div class="product-info">
                        <div>
                            <div class="product-name">${p.name}</div>
                            <div class="product-desc">${p.description || ''}</div>
                        </div>
                        <div class="product-footer">
                            <div class="product-price">${money(p.price)} <span></span></div>
                            <div id="ctrl-${p.id}">${counterHtml(p.id)}</div>
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
        <div class="spacer"></div>
    `).join('') + '<div class="scroll-pad"></div>';
}

function refreshCounters() {
    state.categories.flatMap((c) => c.products).forEach((p) => {
        const el = document.getElementById(`ctrl-${p.id}`);
        if (el) el.innerHTML = counterHtml(p.id);
    });
}

function updateBar() {
    const bar = document.getElementById('cartBar');
    const count = cartCount();
    document.getElementById('cartCount').textContent = count;
    document.getElementById('cartTotal').textContent = money(cartTotal());
    bar.classList.toggle('visible', count > 0);
}

function renderCartItems() {
    const list = document.getElementById('cartItemsList');
    const items = cartItems();
    if (!items.length) {
        list.innerHTML = '<div class="empty-cart">السلة فارغة</div>';
        return;
    }
    list.innerHTML = items.map((i) => `
        <div class="cart-item">
            <div class="cart-item-emoji">${i.emoji}</div>
            <div class="cart-item-body">
                <div class="cart-item-top">
                    <span class="cart-item-name">${i.name}</span>
                    <button type="button" class="cart-item-remove" data-action="remove" data-id="${i.id}" aria-label="حذف من السلة">🗑</button>
                </div>
                <div class="cart-item-bottom">
                    <span class="cart-item-qty">الكمية ×${i.qty}</span>
                    <span class="cart-item-price">${money(i.qty * i.price)}</span>
                </div>
            </div>
        </div>
    `).join('');
}

function updateSummary() {
    document.getElementById('summarySubtotal').textContent = money(cartTotal());
    document.getElementById('summaryTotal').textContent = money(cartTotal());
}

function openModal() {
    renderCartItems();
    updateSummary();
    document.getElementById('modalOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

function upsertCart(productId, delta) {
    const product = state.categories.flatMap((c) => c.products).find((p) => p.id === Number(productId));
    if (!product) return;
    const item = state.cart[productId] || { id: product.id, name: product.name, price: Number(product.price), emoji: '🍽️', qty: 0 };
    item.qty += delta;
    if (item.qty <= 0) delete state.cart[productId];
    else state.cart[productId] = item;
    refreshCounters();
    updateBar();
    renderCartItems();
    updateSummary();
}

function setType(type) {
    state.type = type;
    document.querySelectorAll('.type-btn').forEach((btn) => btn.classList.toggle('active', btn.dataset.type === type));
    document.getElementById('tableField').classList.toggle('show', type === 'dine_in');
    document.getElementById('addressField').style.display = type === 'delivery' ? 'flex' : 'none';
}

/** @returns {'whatsapp'|'dashboard'} */
function checkoutMethod() {
    const r = state.restaurant;
    if (!r) return 'dashboard';
    if (r.checkout_method === 'whatsapp' || r.checkout_method === 'dashboard') {
        return r.checkout_method;
    }
    if (r.whatsapp_orders_enabled === false) return 'dashboard';
    return r.order_method === 'dashboard' ? 'dashboard' : 'whatsapp';
}

function updateCheckoutSubmitLabel() {
    const btn = document.getElementById('submitBtn');
    if (!btn || !state.restaurant) return;
    const isDashboard = checkoutMethod() === 'dashboard';
    btn.innerHTML = isDashboard
        ? '<span class="submit-icon">🛒</span> إرسال الطلب للمطعم'
        : '<span class="submit-icon">💬</span> أرسل الطلب عبر واتساب';
}

function showOrderSuccessToast(message) {
    let el = document.getElementById('orderToast');
    if (!el) {
        el = document.createElement('div');
        el.id = 'orderToast';
        el.className = 'order-toast';
        el.setAttribute('role', 'status');
        el.setAttribute('aria-live', 'polite');
        document.body.appendChild(el);
    }
    el.textContent = message;
    el.classList.add('order-toast--visible');
    window.clearTimeout(showOrderSuccessToast._t);
    showOrderSuccessToast._t = window.setTimeout(() => {
        el.classList.remove('order-toast--visible');
    }, 4200);
}

async function submitOrder(event) {
    event.preventDefault();
    const errorEl = document.getElementById('checkoutError');
    const btn = document.getElementById('submitBtn');
    errorEl.style.display = 'none';
    if (!cartCount()) return;

    const payload = {
        customer_name: document.getElementById('custName').value.trim(),
        customer_phone: document.getElementById('custPhone').value.trim(),
        delivery_type: state.type,
        table_number: state.type === 'dine_in' ? document.getElementById('custTable').value.trim() : null,
        items: cartItems().map((i) => ({ product_id: i.id, quantity: i.qty })),
    };

    btn.disabled = true;
    try {
        const res = await fetch(`/menu/${state.barcode}/order`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify(payload),
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'تعذر إرسال الطلب');

        if (data.status === 'success' && data.method === 'dashboard') {
            showOrderSuccessToast(data.message || 'تم استلام طلبك بنجاح وجاري تجهيزه!');
            state.cart = {};
            document.getElementById('checkoutForm').reset();
            setType('delivery');
            refreshCounters();
            updateBar();
            renderCartItems();
            updateSummary();
            closeModal();
            return;
        }

        if (data.status === 'success' && data.method === 'whatsapp' && data.redirect_url) {
            document.getElementById('orderFormWrap').classList.add('hide');
            document.getElementById('successState').classList.add('show');
            document.getElementById('successOrderNum').textContent = `طلب #${data.order_id}`;
            const sub = document.getElementById('successSubtext');
            if (sub) sub.textContent = 'سيتم تحويلك لواتساب لإرسال الطلب للمطعم مباشرة.';
            window.open(data.redirect_url, '_blank');
            return;
        }

        // استجابة بدون method (توافق مع نسخ قديمة)
        if (data.redirect_url || data.whatsapp_url) {
            const url = data.redirect_url || data.whatsapp_url;
            document.getElementById('orderFormWrap').classList.add('hide');
            document.getElementById('successState').classList.add('show');
            document.getElementById('successOrderNum').textContent = `طلب #${data.order_id}`;
            const sub = document.getElementById('successSubtext');
            if (sub) sub.textContent = 'سيتم تحويلك لواتساب لإرسال الطلب للمطعم مباشرة.';
            window.open(url, '_blank');
            return;
        }

        throw new Error(data.message || 'تعذر إرسال الطلب');
    } catch (e) {
        errorEl.textContent = e.message;
        errorEl.style.display = 'block';
    } finally {
        btn.disabled = false;
    }
}

function resetOrder() {
    state.cart = {};
    document.getElementById('checkoutForm').reset();
    document.getElementById('orderFormWrap').classList.remove('hide');
    document.getElementById('successState').classList.remove('show');
    setType('delivery');
    refreshCounters();
    updateBar();
    closeModal();
}

async function loadMenu() {
    const res = await fetch(`/menu/${state.barcode}`, { headers: { Accept: 'application/json' } });
    const data = await res.json();
    if (!res.ok) throw new Error(data.message || 'تعذر التحميل');
    state.restaurant = data.restaurant;
    state.categories = data.categories || [];
    renderRestaurant();
    updateCheckoutSubmitLabel();
    renderTabs();
    renderMenu();
}

document.addEventListener('DOMContentLoaded', async () => {
    state.barcode = document.body.dataset.menuBarcode;
    try { await loadMenu(); } catch (e) { document.getElementById('menuContent').innerHTML = `<div class="section-head"><span class="section-head-title">${e.message}</span></div>`; }

    document.getElementById('catScroll').addEventListener('click', (e) => {
        const btn = e.target.closest('.cat-tab'); if (!btn) return;
        document.querySelectorAll('.cat-tab').forEach((t) => t.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(btn.dataset.target)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    document.getElementById('menuContent').addEventListener('click', (e) => {
        const add = e.target.closest('[data-action="add"]');
        const minus = e.target.closest('[data-action="minus"]');
        if (add) upsertCart(add.dataset.id, 1);
        if (minus) upsertCart(minus.dataset.id, -1);
    });

    document.getElementById('cartItemsList').addEventListener('click', (e) => {
        const remove = e.target.closest('[data-action="remove"]');
        if (remove) upsertCart(remove.dataset.id, -999);
    });

    document.getElementById('cartBar').addEventListener('click', openModal);
    document.getElementById('closeModalBtn').addEventListener('click', closeModal);
    document.getElementById('modalOverlay').addEventListener('click', (e) => { if (e.target.id === 'modalOverlay') closeModal(); });
    document.querySelectorAll('.type-btn').forEach((btn) => btn.addEventListener('click', () => setType(btn.dataset.type)));
    document.getElementById('checkoutForm').addEventListener('submit', submitOrder);
    document.getElementById('resetOrderBtn').addEventListener('click', resetOrder);
    document.getElementById('shareBtn').addEventListener('click', async () => {
        if (navigator.share) await navigator.share({ title: state.restaurant?.name || 'المنيو', url: window.location.href });
        else { await navigator.clipboard.writeText(window.location.href); alert('تم نسخ الرابط'); }
    });
});
