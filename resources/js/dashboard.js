import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import 'flowbite';
import { Modal } from 'flowbite';

window.Alpine = Alpine;
Alpine.start();

const cfg = window.__DASHBOARD__ || {};

/** Flowbite Modal (يُنشأ عند أول فتح؛ أزرار الإغلاق تستخدم data-close-order-modal) */
let orderDetailModalInstance = null;

function getOrderDetailModal() {
    if (!orderDetailModalInstance) {
        const el = document.getElementById('order-detail-modal');
        if (el) {
            orderDetailModalInstance = new Modal(el, {
                placement: 'center',
                backdrop: 'dynamic',
                backdropClasses:
                    'bg-black/70 dark:bg-black/70 fixed inset-0 z-40 backdrop-blur-sm',
                closable: true,
            });
        }
    }
    return orderDetailModalInstance;
}

const STATUS_MAP = {
    pending: { label: 'معلق', cls: 'badge-pending', dot: '#f5a623', icon: '⏳' },
    accepted: { label: 'مقبول', cls: 'badge-accepted', dot: '#60a5fa', icon: '👨‍🍳' },
    cancelled: { label: 'ملغى', cls: 'badge-cancelled', dot: '#f87171', icon: '❌' },
};

let orders = [];
let currentFilter = 'all';
let currentOrderId = null;
let ordersPollTimer = null;
let ordersMeta = { total: 0, limit: 50, returned: 0 };

/** فاصل تحديث جدول الطلبات (مباشر بدون زر تحديث) */
const ORDERS_POLL_MS = 8000;

function orderStatusUrl(id) {
    return (cfg.orderStatusUrl || '').replace('__ORDER_ID__', String(id));
}

function showToast(msg) {
    const root = document.getElementById('toast');
    const text = document.getElementById('toast-text');
    if (!root || !text) return;
    text.textContent = msg;
    root.classList.remove('invisible', 'opacity-0', '-translate-y-4');
    root.classList.add('opacity-100', 'translate-y-0');
    window.clearTimeout(showToast._hide);
    showToast._hide = window.setTimeout(() => {
        root.classList.add('invisible', 'opacity-0', '-translate-y-4');
        root.classList.remove('opacity-100', 'translate-y-0');
    }, 2500);
}

function esc(s) {
    if (s == null || s === undefined) return '';
    return String(s)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function escAttr(s) {
    return esc(s).replace(/'/g, '&#39;');
}

/** قائمة كاملة (مثلاً في نافذة التفاصيل) */
function formatItems(items) {
    if (!items?.length) return '—';
    return items.map((it) => `${esc(it.product_name)} × ${it.quantity}`).join('، ');
}

const ITEMS_PREVIEW_MAX = 3;

/** معاينة للجدول: أول N صنف ثم +الباقي، مع قص بصري */
function formatItemsTableCell(items) {
    if (!items?.length) {
        return '<span class="text-[#5c5955]">—</span>';
    }
    const parts = items.map((it) => `${esc(it.product_name)} × ${it.quantity}`);
    const fullTitle = parts.join('، ');
    if (parts.length <= ITEMS_PREVIEW_MAX) {
        return `<div class="order-items-preview max-h-[4.75rem] overflow-hidden break-words text-xs leading-snug text-[#9a9690]" title="${escAttr(fullTitle)}">${parts.join('، ')}</div>`;
    }
    const shown = parts.slice(0, ITEMS_PREVIEW_MAX).join('، ');
    const more = parts.length - ITEMS_PREVIEW_MAX;
    return `<div class="order-items-preview max-h-[4.75rem] overflow-hidden break-words text-xs leading-snug text-[#9a9690]" title="${escAttr(fullTitle)}">${shown} <span class="font-bold text-[#f5a623]">+${more}</span></div>`;
}

function formatType(o) {
    if (o.delivery_type === 'dine_in') {
        const t = o.table_number ? `طاولة ${o.table_number}` : 'داخل الصالة';
        return { icon: '🪑', text: t };
    }
    return { icon: '🛵', text: 'توصيل' };
}

function formatTime(iso) {
    if (!iso) return '—';
    const d = new Date(iso);
    if (Number.isNaN(d.getTime())) return '—';
    return d.toLocaleTimeString(document.documentElement.lang || 'ar', {
        hour: '2-digit',
        minute: '2-digit',
    });
}

function renderOrders() {
    const body = document.getElementById('ordersBody');
    const footer = document.querySelector('[data-orders-footer]');
    if (!body) return;

    const data = [...orders];

    if (!data.length) {
        const emptyMsg =
            ordersMeta.total > 0 ? 'لا طلبات في هذا التبويب' : 'لا طلبات بعد';
        body.innerHTML = `<tr class="border-b border-white/[0.07] bg-[#17191f]"><td colspan="8" class="px-6 py-8 text-center text-[#9a9690]">${emptyMsg}</td></tr>`;
        if (footer) footer.textContent = '—';
        return;
    }

    body.innerHTML = data
        .map((o) => {
            const s = STATUS_MAP[o.status] || STATUS_MAP.pending;
            const typ = formatType(o);
            const name0 = esc((o.customer_name || '?')[0]);
            return `
    <tr data-id="${o.id}" data-status="${o.status}" class="border-b border-white/[0.07] bg-[#17191f] hover:bg-white/[0.04]">
      <td class="min-w-0 px-3 py-3 align-top"><span class="text-sm font-black text-[#f5a623]">#${o.id}</span></td>
      <td class="min-w-0 px-3 py-3 align-top">
        <div class="flex min-w-0 items-start gap-2">
          <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-[#f5a623]/15 text-xs font-bold text-[#f5a623]">
            ${name0}
          </div>
          <div class="min-w-0 flex-1">
            <div class="truncate text-sm font-bold text-[#f0ece3]" title="${escAttr(o.customer_name || '')}">${esc(o.customer_name || '—')}</div>
            <div class="truncate text-xs text-[#9a9690]" title="${escAttr(o.customer_phone || '')}">${esc(o.customer_phone || '')}</div>
          </div>
        </div>
      </td>
      <td class="min-w-0 px-3 py-3 align-top">${formatItemsTableCell(o.items)}</td>
      <td class="min-w-0 px-3 py-3 align-top"><span class="text-xs font-bold text-[#f0ece3]">${typ.icon} ${typ.text}</span></td>
      <td class="min-w-0 px-3 py-3 align-top"><span class="font-bold text-[#f0ece3]">${Number(o.total_price).toFixed(2)}</span></td>
      <td class="min-w-0 px-3 py-3 align-top"><span class="text-xs text-[#9a9690]">${formatTime(o.created_at)}</span></td>
      <td class="min-w-0 px-3 py-3 align-top">
        <span class="status-badge ${s.cls}">
          <span class="status-dot" style="background:${s.dot}"></span>
          ${s.label}
        </span>
      </td>
      <td class="min-w-0 px-2 py-3 align-top">
        <div class="order-action-stack">
          <button type="button" data-open-order="${o.id}" class="order-action-btn border border-[#f5a623]/50 bg-[#f5a623]/15 text-[#fbbf24] hover:bg-[#f5a623]/25 hover:text-[#fde68a]" title="عرض تفاصيل الطلب كاملة" aria-label="تفاصيل الطلب">
            <span aria-hidden="true">👁</span><span>تفاصيل</span>
          </button>
          ${
              o.status === 'pending'
                  ? `<button type="button" data-patch-order="${o.id}" data-next="accepted" class="order-action-btn border border-[#25d366]/55 bg-[#25d366]/15 text-[#4ade80] hover:bg-[#25d366]/25 hover:text-[#86efac]" title="قبول الطلب والبدء بالتحضير" aria-label="قبول الطلب"><span aria-hidden="true">✓</span><span>قبول</span></button>`
                  : ''
          }
          ${
              o.status === 'pending' || o.status === 'accepted'
                  ? `<button type="button" data-patch-order="${o.id}" data-next="cancelled" class="order-action-btn border border-[#f87171]/60 bg-[#ef4444]/20 text-[#f87171] hover:bg-[#ef4444]/30 hover:text-[#fca5a5]" title="إلغاء الطلب" aria-label="إلغاء الطلب"><span aria-hidden="true">✕</span><span>إلغاء</span></button>`
                  : ''
          }
        </div>
      </td>
    </tr>`;
        })
        .join('');

    if (footer) {
        const { total, limit } = ordersMeta;
        const n = data.length;
        if (total > n) {
            footer.textContent = `يُعرض ${n} من أصل ${total} طلب (حد أقصى ${limit} لكل تحديث)`;
        } else {
            footer.textContent = `عرض ${n} طلب${n === 1 ? '' : 'ات'}`;
        }
    }
}

async function fetchOrders(options = {}) {
    const silent = options.silent === true;
    if (!document.getElementById('ordersBody')) {
        return;
    }
    const url = cfg.ordersIndex;
    if (!url) return;
    const limit = Number(cfg.ordersListLimit) > 0 ? Number(cfg.ordersListLimit) : 50;
    const params = { limit };
    if (currentFilter !== 'all') {
        params.status = currentFilter;
    }
    try {
        const { data } = await window.axios.get(url, { params });
        orders = data.orders || [];
        if (data.meta && typeof data.meta.total === 'number') {
            ordersMeta = {
                total: data.meta.total,
                limit: data.meta.limit ?? limit,
                returned: data.meta.returned ?? orders.length,
            };
        }
        renderOrders();
    } catch {
        if (!silent) {
            showToast('تعذر تحميل الطلبات');
            const body = document.getElementById('ordersBody');
            if (body) {
                body.innerHTML =
                    '<tr class="border-b border-white/[0.07] bg-[#17191f]"><td colspan="8" class="px-6 py-8 text-center text-red-400">خطأ في التحميل</td></tr>';
            }
        }
    }
}

function startLiveOrdersPolling() {
    if (ordersPollTimer != null || !cfg.ordersIndex) {
        return;
    }
    ordersPollTimer = window.setInterval(() => {
        if (document.visibilityState === 'visible') {
            fetchOrders({ silent: true });
        }
    }, ORDERS_POLL_MS);

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            fetchOrders({ silent: true });
        }
    });
}

/** آخر عدد طلبات معلّقة (مسار orders.checkNew) — لتحديث الإشعارات على كل صفحات اللوحة */
let lastPendingCheckCount = null;

function playNewOrderAlertSound() {
    try {
        const Ctx = window.AudioContext || window.webkitAudioContext;
        if (!Ctx) return;
        const ctx = new Ctx();
        const master = ctx.createGain();
        master.gain.value = 0.12;
        master.connect(ctx.destination);

        const beep = (freq, t0, dur) => {
            const osc = ctx.createOscillator();
            const g = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.value = freq;
            osc.connect(g);
            g.connect(master);
            g.gain.setValueAtTime(0.001, t0);
            g.gain.exponentialRampToValueAtTime(1, t0 + 0.025);
            g.gain.exponentialRampToValueAtTime(0.001, t0 + dur);
            osc.start(t0);
            osc.stop(t0 + dur + 0.03);
        };

        const t0 = ctx.currentTime;
        beep(784, t0, 0.11);
        beep(1047, t0 + 0.13, 0.14);
        beep(1319, t0 + 0.28, 0.16);
        setTimeout(() => ctx.close?.(), 600);
    } catch {
        /* ignore */
    }
}

function updatePendingNotificationsUi(pendingCount) {
    const badge = document.getElementById('dashboard-notif-badge');
    const panel = document.getElementById('dashboard-notif-panel');
    const ordersUrl = cfg.ordersPageUrl || '/orders';
    if (!badge || !panel) return;

    if (pendingCount > 0) {
        badge.classList.remove('hidden');
        badge.textContent = pendingCount > 9 ? '9+' : String(pendingCount);
        const href = String(ordersUrl).replace(/&/g, '&amp;').replace(/"/g, '&quot;');
        panel.innerHTML = `
            <p class="px-4 py-2 text-sm text-[#f0ece3]">لديك ${pendingCount} طلبات معلقة</p>
            <a href="${href}" class="block px-4 py-2 text-sm font-medium text-[#f5a623] hover:bg-white/5">عرض الطلبات →</a>
        `;
    } else {
        badge.classList.add('hidden');
        badge.textContent = '0';
        panel.innerHTML = '<p class="px-4 py-3 text-sm text-[#9a9690]">لا إشعارات جديدة</p>';
    }
}

/** استطلاع الطلبات المعلّقة: تحديث الجرس بدون إعادة تحميل + نغمة ورسالة عند طلب جديد */
function startGlobalPendingNotifications() {
    const url = cfg.checkNewOrdersUrl;
    if (!url) return;

    if (cfg.initialPendingCount !== undefined && cfg.initialPendingCount !== null) {
        lastPendingCheckCount = Number(cfg.initialPendingCount);
    }

    const pollMs = 7000;

    const tick = async () => {
        if (document.visibilityState !== 'visible') return;
        try {
            const { data } = await window.axios.get(url);
            const n = Number(data.new_orders_count ?? 0);
            updatePendingNotificationsUi(n);

            if (lastPendingCheckCount !== null && n > lastPendingCheckCount) {
                playNewOrderAlertSound();
                showToast('طلب جديد! راجع الطلبات.');
                if (document.getElementById('ordersBody')) {
                    await fetchOrders({ silent: true });
                }
            }
            lastPendingCheckCount = n;
        } catch {
            /* ignore */
        }
    };

    tick();
    window.setInterval(tick, pollMs);
}

async function patchOrderStatus(id, status) {
    try {
        await window.axios.patch(orderStatusUrl(id), { status });
        const s = STATUS_MAP[status];
        showToast(`${s?.icon || '✓'} طلب #${id} — ${s?.label || status}`);
        await fetchOrders();
    } catch {
        showToast('تعذر تحديث الحالة');
    }
}

function openOrderModal(id) {
    const o = orders.find((x) => x.id === id);
    if (!o) return;
    currentOrderId = id;
    const s = STATUS_MAP[o.status] || STATUS_MAP.pending;
    const typ = formatType(o);
    const title = document.getElementById('modalOrderTitle');
    const content = document.getElementById('modalContent');
    const acceptBtn = document.getElementById('modalAcceptBtn');

    if (title) title.textContent = `تفاصيل الطلب #${o.id}`;
    if (acceptBtn) {
        acceptBtn.classList.toggle('hidden', o.status !== 'pending');
    }
    if (content) {
        content.innerHTML = `
    <div class="flex flex-col gap-3">
      <div class="flex items-center justify-between rounded-lg border border-white/5 bg-[#131519] p-3">
        <span class="text-sm text-[#9a9690]">الزبون</span>
        <div class="text-end">
          <div class="text-sm font-bold text-[#f0ece3]">${esc(o.customer_name || '—')}</div>
          <div class="text-xs text-[#9a9690]">${esc(o.customer_phone || '')}</div>
        </div>
      </div>
      <div class="flex items-center justify-between rounded-lg border border-white/5 bg-[#131519] p-3">
        <span class="text-sm text-[#9a9690]">نوع الطلب</span>
        <span class="text-sm font-bold text-[#f0ece3]">${typ.icon} ${esc(typ.text)}</span>
      </div>
      <div class="rounded-lg border border-white/5 bg-[#131519] p-3">
        <div class="mb-2 text-sm text-[#9a9690]">الأصناف</div>
        <div class="text-sm font-bold text-[#f0ece3]">${formatItems(o.items)}</div>
      </div>
      <div class="flex items-center justify-between rounded-lg border border-white/5 bg-[#131519] p-3">
        <span class="text-sm text-[#9a9690]">الإجمالي</span>
        <span class="text-lg font-black text-[#f5a623]">${Number(o.total_price).toFixed(2)}</span>
      </div>
      <div class="flex items-center justify-between rounded-lg border border-white/5 bg-[#131519] p-3">
        <span class="text-sm text-[#9a9690]">الحالة</span>
        <span class="status-badge ${s.cls}"><span class="status-dot" style="background:${s.dot}"></span>${s.label}</span>
      </div>
    </div>`;
    }
    getOrderDetailModal()?.show();
}

function closeOrderModal() {
    getOrderDetailModal()?.hide();
}

function setFilterTabActive(filter) {
    document.querySelectorAll('[data-order-filters] [data-filter]').forEach((btn) => {
        const on = btn.getAttribute('data-filter') === filter;
        if (on) {
            btn.classList.add('bg-[#f5a623]', 'text-[#1a1000]', 'shadow-sm');
            btn.classList.remove('text-[#9a9690]', 'hover:bg-white/5');
        } else {
            btn.classList.remove('bg-[#f5a623]', 'text-[#1a1000]', 'shadow-sm');
            btn.classList.add('text-[#9a9690]', 'hover:bg-white/5');
        }
    });
}

function initChart() {
    const canvas = document.getElementById('ordersChart');
    if (!canvas || !cfg.chart) return;
    const ctx = canvas.getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: cfg.chart.labels || [],
            datasets: [
                {
                    label: 'هذا الأسبوع',
                    data: cfg.chart.thisWeek || [],
                    backgroundColor: 'rgba(245,166,35,0.75)',
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'الأسبوع الماضي',
                    data: cfg.chart.lastWeek || [],
                    backgroundColor: 'rgba(255,255,255,0.06)',
                    borderRadius: 6,
                    borderSkipped: false,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e2028',
                    titleColor: '#f0ece3',
                    bodyColor: '#9a9690',
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 10,
                    titleFont: { family: 'Cairo', weight: 'bold' },
                    bodyFont: { family: 'Cairo' },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#55524f', font: { family: 'Cairo', size: 11 } },
                    border: { display: false },
                },
                y: {
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: { color: '#55524f', font: { family: 'Cairo', size: 11 } },
                    border: { display: false },
                },
            },
        },
    });
}

function bindEvents() {
    document.querySelector('[data-order-filters]')?.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-filter]');
        if (!btn) return;
        currentFilter = btn.getAttribute('data-filter');
        setFilterTabActive(currentFilter);
        await fetchOrders({ silent: true });
    });

    document.body.addEventListener('click', (e) => {
        const openId = e.target.closest('[data-open-order]')?.getAttribute('data-open-order');
        if (openId) {
            openOrderModal(Number(openId));
            return;
        }
        const patchBtn = e.target.closest('[data-patch-order]');
        if (patchBtn) {
            const id = Number(patchBtn.getAttribute('data-patch-order'));
            const next = patchBtn.getAttribute('data-next');
            if (id && next) patchOrderStatus(id, next);
        }
    });

    document.querySelectorAll('[data-close-order-modal]').forEach((el) => {
        el.addEventListener('click', closeOrderModal);
    });

    document.getElementById('modalAcceptBtn')?.addEventListener('click', async () => {
        if (currentOrderId) {
            await patchOrderStatus(currentOrderId, 'accepted');
        }
        closeOrderModal();
    });

    document.querySelectorAll('[data-copy-menu-url]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const url = btn.getAttribute('data-copy-menu-url');
            if (!url) return;
            try {
                await navigator.clipboard.writeText(url);
                showToast('تم نسخ الرابط');
            } catch {
                showToast('تعذر النسخ');
            }
        });
    });

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
    }
}

initChart();
bindEvents();
if (document.getElementById('ordersBody')) {
    fetchOrders();
    startLiveOrdersPolling();
}
startGlobalPendingNotifications();
