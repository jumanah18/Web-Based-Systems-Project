<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Optional Vanilla Guard: If they didn't just come from an active checkout, 
// redirect them to the home page so they can't bookmark/refresh a fake purchase screen.
/*
if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], 'checkout.php') === false) {
    header('Location: index.php');
    exit;
}
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Purchase Confirmed — Dar Al Hiraf</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .success-wrap {
      min-height: 70vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 80px 24px;
      background: var(--light);
    }
    .success-card {
      background: var(--white);
      border: 1px solid #e0dcd4;
      border-radius: 12px;
      padding: 60px 56px;
      max-width: 560px;
      width: 100%;
      text-align: center;
      box-shadow: 0 8px 40px rgba(36,60,33,0.08);
    }
    .success-icon {
      width: 72px;
      height: 72px;
      background: var(--green);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 28px;
      border: 4px solid var(--gold);
    }
    .success-icon svg {
      width: 36px;
      height: 36px;
      stroke: var(--white);
      fill: none;
      stroke-width: 3;
      stroke-linecap: round;
      stroke-linejoin: round;
    }
    .success-eyebrow {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.22em;
      text-transform: uppercase;
      color: var(--gold);
      margin-bottom: 12px;
    }
    .success-title-ar {
      font-family: 'Noto Naskh Arabic', serif;
      font-size: 34px;
      color: var(--green);
      direction: rtl;
      line-height: 1.3;
      margin-bottom: 8px;
    }
    .success-title-en {
      font-size: 22px;
      font-weight: 700;
      color: var(--brown);
      margin-bottom: 20px;
    }
    .success-divider {
      width: 48px;
      height: 3px;
      background: var(--gold);
      margin: 0 auto 24px;
    }
    .success-msg {
      font-size: 14px;
      color: #666;
      line-height: 1.8;
      margin-bottom: 36px;
    }
    .success-actions {
      display: flex;
      gap: 12px;
      justify-content: center;
      flex-wrap: wrap;
    }
    @media (max-width: 480px) {
      .success-card { padding: 40px 24px; }
      .success-actions { flex-direction: column; }
      .success-actions .btn { text-align: center; }
    }
  </style>
</head>
<body>

<nav class="navbar">
  <div class="navbar-inner">
    <a href="index.php" class="navbar-logo">
      <span class="logo-ar">دار الحرف</span>
      <span class="logo-en">Dar Al Hiraf</span>
    </a>
    <div class="navbar-nav">
      <a href="index.php">Home</a>
      <a href="shop.php">Shop</a>
      <a href="workshops.php">Workshops</a>
    </div>
    <form class="navbar-search" onsubmit="event.preventDefault();var v=this.querySelector('input').value.trim();if(v)window.location.href='search.html?q='+encodeURIComponent(v);" role="search">
      <input class="navbar-search-input" type="search" placeholder="Search..." aria-label="Search">
      <button class="navbar-search-btn" type="submit" aria-label="Search">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </button>
    </form>
    
    <div class="navbar-right">
      <a id="adminNavLink" href="admin-login.php" class="navbar-admin-link">Admin</a>
      <a href="checkout.php" class="navbar-cart-btn" id="cartBtn" aria-label="View cart">
        <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" stroke="#1a1a1a" fill="none" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        <span class="cart-badge" id="cartBadge"></span>
      </a>
    </div>
  </div>
</nav>

<main id="main-content">
  <div class="success-wrap">
    <div class="success-card">

      <div class="success-icon">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
      </div>

      <p class="success-eyebrow">Order Confirmed</p>
      <h1 class="success-title-ar" lang="ar">شكراً لك على طلبك</h1>
      <p class="success-title-en">Purchase Successful</p>
      <div class="success-divider"></div>

      <p class="success-msg">
        Thank you for supporting Saudi artisans and our cultural heritage.<br>
        Your order has been received and will be processed shortly.<br>
        A confirmation will be sent to your registered contact.
      </p>

      <div class="success-actions">
        <a href="shop.php" class="btn btn-green">Continue Shopping</a>
        <a href="workshops.php" class="btn btn-gold">Explore Workshops</a>
      </div>

    </div>
  </div>
</main>

<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <p class="footer-brand-name" lang="ar">دار الحرف</p>
        <p class="footer-desc">A platform connecting Saudi artisans with those who cherish authentic handmade crafts rooted in heritage. Proudly part of The Year of Handicrafts 2026 initiative by the Ministry of Culture.</p>
      </div>
      <div>
        <p class="footer-col-title">Shop</p>
        <div class="footer-links" role="list">
          <a href="shop.php">All Products</a>
          <a href="shop.php">Pottery</a>
          <a href="shop.php">Textiles</a>
          <a href="shop.php">Metalwork</a>
          <a href="shop.php">Leather</a>
          <a href="shop.php">Fragrance</a>
        </div>
      </div>
      <div>
        <p class="footer-col-title">More</p>
        <div class="footer-links">
          <a href="about.html">About Us</a>
          <a href="workshops.php">Workshops</a>
          <a href="contact.php">Contact Us</a>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-gold-bar"></div>
  <div class="container">
    <div class="footer-bottom">
      <p>&copy; 2026 Dar Al Hiraf &mdash; دار الحرف. All rights reserved.</p>
      <p>A Ministry of Culture Initiative</p>
    </div>
  </div>
</footer>

<script src="cart.js"></script>
<script>
  // Admin dynamic navbar menu handler
  (function(){
    var link = document.getElementById('adminNavLink');
    if(!link) return;
    if(sessionStorage.getItem('adminLoggedIn')){
      link.textContent = '→ Admin Dashboard';
      link.href = 'admin-dashboard.php';
    }
  })();

  // Clear out front-end Javascript local tracking memory objects completely 
  // since the backend has completed checkout operations
  if (typeof Cart !== 'undefined' && typeof Cart.clear === 'function') {
      Cart.clear();
  } else if (typeof localStorage !== 'undefined') {
      localStorage.removeItem('cart');
      localStorage.removeItem('shopping_cart');
  }
  
  // Instantly clear the visible header badge count indicator if update utility exists
  if (typeof updateCartBadge === 'function') {
      updateCartBadge();
  } else {
      var badge = document.getElementById('cartBadge');
      if (badge) badge.textContent = '';
  }
</script>
</body>
</html>