<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin-login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dar Al Hiraf — Admin Dashboard</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<nav class="navbar" aria-label="Main navigation">
  <div class="navbar-inner">
    <a href="index.php" class="navbar-logo">
      <span class="logo-ar" lang="ar">دار الحرف</span>
      <span class="logo-en">Dar Al Hiraf</span>
    </a>
    <div class="navbar-nav">
      <a href="index.php">Home</a>
      <a href="shop.php">Shop</a>
      <a href="workshops.html">Workshops</a>
      <a href="about.html">About</a>
    </div>
        <div class="navbar-right">
      <a id="adminNavLink" href="admin-login.php" class="navbar-admin-link">Admin</a>
      <a href="checkout.html" class="navbar-cart-btn" id="cartBtn" aria-label="View cart">
        <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" stroke="#1a1a1a" fill="none" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        <span class="cart-badge" id="cartBadge"></span>
      </a>
    </div>
  </div>
</nav>

<div class="admin-page">
  <div class="admin-container">
    <div class="admin-welcome-banner">
      <div class="admin-welcome-text">
        <h1>Hello, <span>Admin!</span> &#x1F44B;</h1>
        <p>Welcome to the Dar Al Hiraf management portal — Year of Handicrafts 2026</p>
      </div>
      <div class="admin-welcome-icon">&#x1F6E0;</div>
    </div>

    <!-- Stats -->
    <p class="admin-section-title">Store Overview</p>
    <div class="admin-stats-row">
      <div class="admin-stat"><div class="admin-stat-num">12</div><div class="admin-stat-label">Products</div></div>
      <div class="admin-stat"><div class="admin-stat-num">4</div><div class="admin-stat-label">Workshops</div></div>
      <div class="admin-stat"><div class="admin-stat-num">120+</div><div class="admin-stat-label">Artisans</div></div>
      <div class="admin-stat"><div class="admin-stat-num">5</div><div class="admin-stat-label">Categories</div></div>
      <div class="admin-stat"><div class="admin-stat-num">8K+</div><div class="admin-stat-label">Customers</div></div>
    </div>

    <!-- Tabbed management -->
    <p class="admin-section-title">Manage Store</p>
    <div class="admin-tab-bar" role="tablist">
      <button class="admin-tab active" id="btn-products" onclick="switchTab('products')" role="tab" aria-selected="true" aria-controls="tab-products">
        🛍 Products <span class="admin-tab-badge">12</span>
      </button>
      <button class="admin-tab" id="btn-workshops" onclick="switchTab('workshops')" role="tab" aria-selected="false" aria-controls="tab-workshops">
        🏛 Workshops <span class="admin-tab-badge">4</span>
      </button>
    </div>
    <div class="admin-tab-panel active" id="tab-products" role="tabpanel">
      <div class="admin-actions-grid">
        <a href="admin-add-product.php" class="admin-action-card">
          <div class="aac-icon green">&#x2795;</div>
          <p class="aac-label">Products</p>
          <p class="aac-title">Add New Craft Product</p>
          <p class="aac-desc">Upload a new handcrafted item with image, price, artisan info, and category.</p>
          <span class="aac-btn">Add Product</span>
        </a>
        <a href="admin-modify-product.php" class="admin-action-card">
          <div class="aac-icon gold">&#x270F;</div>
          <p class="aac-label">Products</p>
          <p class="aac-title">Edit / Delete Product</p>
          <p class="aac-desc">Edit details, update image, change price or artisan — or permanently delete a product.</p>
          <span class="aac-btn gold-btn">Edit Products</span>
        </a>
      </div>
    </div>
    <div class="admin-tab-panel" id="tab-workshops" role="tabpanel">
      <div class="admin-actions-grid">
        <a href="admin-add-workshop.php" class="admin-action-card">
          <div class="aac-icon green">&#x1F3DB;</div>
          <p class="aac-label">Workshops</p>
          <p class="aac-title">Add New Workshop</p>
          <p class="aac-desc">Create a new workshop session with image, location, date, seats, and booking details.</p>
          <span class="aac-btn">Add Workshop</span>
        </a>
        <a href="admin-modify-workshop.php" class="admin-action-card">
          <div class="aac-icon red">&#x1F527;</div>
          <p class="aac-label">Workshops</p>
          <p class="aac-title">Edit / Delete Workshop</p>
          <p class="aac-desc">Update dates, seats, price, or remove a workshop entirely.</p>
          <span class="aac-btn" style="background:var(--red);">Edit Workshops</span>
        </a>
      </div>
    </div>

    <!-- Quick links -->
    <p class="admin-section-title">Quick Links</p>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <a href="shop.php" class="btn btn-green" style="font-size:12px;">View Shop</a>
      <a href="workshops.html" class="btn btn-green" style="font-size:12px;">View Workshops</a>
      <a href="index.php" class="btn btn-brown" style="font-size:12px;">Main Site</a>
    </div>

  </div>
</div>

<footer class="footer">
  <div class="container">
    <div class="footer-bottom" style="text-align:center;padding:20px 0;">
      <p>&copy; 2026 Dar Al Hiraf &mdash; دار الحرف. Admin Portal.</p>
    </div>
  </div>
</footer>


<script>
  function switchTab(tab){
    document.querySelectorAll('.admin-tab').forEach(function(b){b.classList.remove('active');b.setAttribute('aria-selected','false')});
    document.querySelectorAll('.admin-tab-panel').forEach(function(p){p.classList.remove('active')});
    document.getElementById('btn-'+tab).classList.add('active');
    document.getElementById('btn-'+tab).setAttribute('aria-selected','true');
    document.getElementById('tab-'+tab).classList.add('active');
  }
  document.addEventListener('DOMContentLoaded',function(){
  });
</script>
<script>
  /* Auth-aware navbar link — logout via PHP session */
  (function(){
    var link = document.getElementById('adminNavLink');
    if(!link) return;
    link.textContent = '→ Logout';
    link.className = 'admin-logout-nav-btn';
    link.href = 'logout.php';
  })();
</script>
<script src="cart.js"></script>
</body>
</html>