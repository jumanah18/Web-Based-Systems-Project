const Cart = (() => {

  // ─── Add item to cart ────────────────────────────────────────────────────────
  async function add(item) {
    const res = await fetch('cart_add.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(item)
    });
    const data = await res.json();
    if (data.success) {
      _showToast('✓ Added to cart: ' + item.name);
      _updateNavBadge();
    }
  }

  // ─── Remove item from cart ───────────────────────────────────────────────────
  async function remove(id) {
    await fetch('cart_remove.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id })
    });
    _updateNavBadge();
  }

  // ─── Update quantity ─────────────────────────────────────────────────────────
  async function updateQty(id, qty) {
    await fetch('cart_update.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id, qty: parseInt(qty) })
    });
  }

  // ─── Clear entire cart ───────────────────────────────────────────────────────
  async function clear() {
    await fetch('cart_clear.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({})
    });
    _updateNavBadge();
  }

  // ─── Get cart from session ───────────────────────────────────────────────────
  async function get() {
    const res  = await fetch('cart_get.php');
    const data = await res.json();
    return data.cart || [];
  }

  // ─── Count total items in cart ───────────────────────────────────────────────
  async function count() {
    const cart = await get();
    let total = 0;
    for (let i = 0; i < cart.length; i++) {
      total += cart[i].qty;
    }
    return total;
  }

  // ─── Calculate subtotal ──────────────────────────────────────────────────────
  async function subtotal() {
    const cart = await get();
    let total = 0;
    for (let i = 0; i < cart.length; i++) {
      total += cart[i].price * cart[i].qty;
    }
    return total;
  }

  // ─── Render checkout page ────────────────────────────────────────────────────
  async function renderCheckout() {
    const cart     = await get();
    const contentEl = document.getElementById('cart-content');
    const emptyEl   = document.getElementById('empty-msg');
    const tbody     = document.getElementById('cart-tbody');

    if (!contentEl || !emptyEl || !tbody) return;

    if (cart.length === 0) {
      contentEl.style.display = 'none';
      emptyEl.style.display   = 'block';
      _renderTotals(0, []);
      return;
    }

    emptyEl.style.display   = 'none';
    contentEl.style.display = 'block';

    tbody.innerHTML = '';

    for (let i = 0; i < cart.length; i++) {
      var item = cart[i];
      var num  = i + 1;
      var row  = document.createElement('tr');
      row.className = 'cart-row';
      row.id        = 'row-' + item.id;
      row.innerHTML =
        '<td class="cart-num">' + num + '</td>' +
        '<td>' +
          '<div class="item-flex">' +
            '<img src="' + item.image + '" class="item-img" alt="' + item.name + '">' +
            '<div>' +
              '<p class="pc-name" style="margin:0;">' + item.name + '</p>' +
              '<p style="font-size:11px;color:#888;margin:2px 0 0;">By ' + item.artisan + '</p>' +
            '</div>' +
          '</div>' +
        '</td>' +
        '<td><span class="cart-type-badge ' + (item.type === 'workshop' ? 'workshop-badge' : 'product-badge') + '">' + (item.type === 'workshop' ? 'Workshop' : 'Product') + '</span></td>' +
        '<td class="row-price">SAR ' + item.price.toLocaleString() + '</td>' +
        '<td>' +
          '<div class="qty-range-wrap">' +
            '<input type="range" min="1" max="20" value="' + item.qty + '" oninput="Cart.updateQtyAndRefresh(' + item.id + ', this.value); this.nextElementSibling.textContent = this.value;">' +
            '<span class="qty-val">' + item.qty + '</span>' +
          '</div>' +
        '</td>' +
        '<td><button class="btn-delete" onclick="Cart.removeAndRefresh(' + item.id + ')" title="Remove">🗑</button></td>';
      tbody.appendChild(row);
    }

    _renderTotals(0, cart);
  }

  // ─── Remove and re-render ────────────────────────────────────────────────────
  async function removeAndRefresh(id) {
    await remove(id);
    await renderCheckout();
  }

  // ─── Update qty and re-render totals ─────────────────────────────────────────
  async function updateQtyAndRefresh(id, qty) {
    await updateQty(id, qty);
    const cart = await get();
    _renderTotals(0, cart);
  }

  // ─── Render totals ───────────────────────────────────────────────────────────
  function _renderTotals(sub, cart) {
    var products  = 0;
    var workshops = 0;
    for (var i = 0; i < cart.length; i++) {
      if (cart[i].type === 'workshop') {
        workshops += cart[i].price * cart[i].qty;
      } else {
        products += cart[i].price * cart[i].qty;
      }
    }
    var serviceFee = 15;
    var grand      = products + workshops + serviceFee;
    var fmt        = function(n) { return 'SAR ' + n.toLocaleString(); };

    _setEl('sum-products',  fmt(products));
    _setEl('sum-workshops', fmt(workshops));
    _setEl('sum-shipping',  fmt(serviceFee));
    _setEl('sum-grand',     fmt(grand));
  }

  // ─── Update nav badge ────────────────────────────────────────────────────────
  async function _updateNavBadge() {
    var badge = document.getElementById('cart-count-badge');
    if (!badge) return;
    var cart  = await get();
    var total = 0;
    for (var i = 0; i < cart.length; i++) {
      total += cart[i].qty;
    }
    badge.textContent   = total;
    badge.style.display = total > 0 ? 'inline-flex' : 'none';
  }

  // ─── Toast notification ──────────────────────────────────────────────────────
  function _showToast(msg) {
    var toast = document.getElementById('dh-cart-toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'dh-cart-toast';
      toast.style.cssText =
        'position:fixed;bottom:28px;right:28px;z-index:9999;' +
        'background:#243C21;color:#fff;padding:13px 22px;' +
        'border-radius:8px;font-size:14px;font-family:Lato,sans-serif;' +
        'border-left:4px solid #D2AC2B;box-shadow:0 4px 20px rgba(0,0,0,0.25);' +
        'transition:opacity 0.4s;opacity:0;pointer-events:none;';
      document.body.appendChild(toast);
    }
    toast.textContent   = msg;
    toast.style.opacity = '1';
    clearTimeout(toast._t);
    toast._t = setTimeout(function() { toast.style.opacity = '0'; }, 2800);
  }

  // ─── Helper to set element text ──────────────────────────────────────────────
  function _setEl(id, val) {
    var el = document.getElementById(id);
    if (el) el.textContent = val;
  }

  // Update badge on every page load
  document.addEventListener('DOMContentLoaded', function() {
    _updateNavBadge();
  });

  return {
    add, remove, updateQty, clear, get,
    count, subtotal,
    renderCheckout, removeAndRefresh, updateQtyAndRefresh
  };
})();

/* ── Compatibility bridge for existing product pages ─────────────────────────
   My product HTML pages call addToCart(pid, name, price, qty).
   This maps those calls to the new PHP session-based Cart.add().
   Also supports both badge IDs: cartBadge (old) and cart-count-badge (new).
   Author: Combined integration                                              */

// Map old badge ID so _updateNavBadge() works on pages that use "cartBadge"
(function patchBadgeId() {
  document.addEventListener('DOMContentLoaded', function () {
    var oldBadge = document.getElementById('cartBadge');
    if (oldBadge && !document.getElementById('cart-count-badge')) {
      oldBadge.id = 'cart-count-badge';
    }
  });
})();

// Map old addToCart(pid, name, price, qty) API to Cart.add()
function addToCart(pid, name, price, qty) {
  var PRODUCTS = {
    p1:  { img: 'images/p1_baskets.jpg',  artisan: 'Fatima Al-Ghamdi',  type: 'product' },
    p2:  { img: 'images/p2_mortar.jpg',   artisan: 'Hassan Al-Otaibi',  type: 'product' },
    p3:  { img: 'images/p3_hat.jpg',      artisan: 'Aisha Al-Mutairi',  type: 'product' },
    p4:  { img: 'images/p4_khanjar.jpg',  artisan: 'Ibrahim Al-Dosari', type: 'product' },
    p5:  { img: 'images/p5_weaving.jpg',  artisan: 'Fatima Al-Ghamdi',  type: 'product' },
    p6:  { img: 'images/p6_jewelry.jpg',  artisan: 'Maha Al-Shehri',    type: 'product' },
    p7:  { img: 'images/p7_palmweave.jpg',artisan: 'Aisha Al-Mutairi',  type: 'product' },
    p8:  { img: 'images/p8_roses.jpg',    artisan: 'Khalid Al-Zahrani', type: 'product' },
    p9:  { img: 'images/p9_textile.jpg',  artisan: 'Fatima Al-Ghamdi',  type: 'product' },
    p10: { img: 'images/p10_clay.jpg',    artisan: 'Noura Al-Rashidi',  type: 'product' },
    p11: { img: 'images/p11_leather.jpg', artisan: 'Ibrahim Al-Dosari', type: 'product' },
    p12: { img: 'images/p12_wooddoor.jpg',artisan: 'Khalid Al-Zahrani', type: 'product' }
  };
  var meta = PRODUCTS[pid] || { img: '', artisan: '', type: 'product' };
  Cart.add({
    id:      pid,
    name:    name,
    price:   price,
    image:   meta.img,
    artisan: meta.artisan,
    type:    meta.type,
    qty:     qty || 1
  });
}
