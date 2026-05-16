
<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dar Al Hiraf — Shop</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    /* Styling to make sure category buttons style consistently like the original links */
    .cat-strip-inner button.cat-item {
      background: none;
      border: none;
      font: inherit;
      cursor: pointer;
    }
    
    /* Active styling to let users know which category is selected */
    .cat-strip-inner button.cat-item.active-cat {
      color: var(--gold) !important;
      font-weight: 700;
    }
  </style>
</head>
<body>
<a href="#main-content" class="skip-link">Skip to main content</a>

<nav class="navbar" aria-label="Main navigation">
  <div class="navbar-inner">
    <a href="index.php" class="navbar-logo">
      <span class="logo-ar" lang="ar">دار الحرف</span>
      <span class="logo-en">Dar Al Hiraf</span>
    </a>
    <div class="navbar-nav">
      <a href="index.php">Home</a>
      <a href="shop.php" class="active">Shop</a>
      <a href="workshops.php">Workshops</a>
      <a href="about.html">About</a>
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

<div class="back-bar">
  <div class="container"><a href="index.php" class="back-link">&larr; Back to Home</a></div>
</div>

<div class="cat-strip">
  <div class="cat-strip-inner">
    <button class="cat-item active-cat" onclick="filterCategory('all', this)">All Crafts</button>
    <button class="cat-item" onclick="filterCategory('pottery', this)">Pottery</button>
    <button class="cat-item" onclick="filterCategory('textiles', this)">Textiles</button>
    <button class="cat-item" onclick="filterCategory('metalwork', this)">Metalwork</button>
    <button class="cat-item" onclick="filterCategory('leather', this)">Leather</button>
    <button class="cat-item" onclick="filterCategory('fragrance', this)">Fragrance</button>
  </div>
</div>

<div class="shop-header">
  <h1 lang="ar">المجموعة الكاملة</h1>
  <?php $count = $pdo->query("SELECT COUNT(*) FROM products WHERE category != 'Workshop'")->fetchColumn(); ?>
  <p><span id="product-count"><?php echo $count; ?></span> handcrafted products by Saudi artisans across the Kingdom</p>
</div>

<div class="shop-content">
  <div class="container">
    <div class="product-grid">
      <?php
      $products = $pdo->query("SELECT * FROM products WHERE category != 'Workshop' ORDER BY pid ASC")->fetchAll();
      foreach ($products as $p):
        // Standardize category strings for matching
        $cardCategory = strtolower(trim($p['category']));
      ?>
      <a href="product.php?id=<?php echo $p['pid']; ?>" class="product-card" data-cat="<?php echo $cardCategory; ?>">
        <div class="image-container">
          <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
        </div>
        <div class="product-info">
          <p class="category"><?php echo strtoupper(htmlspecialchars($p['category'])); ?></p>
          <h3 class="product-title"><?php echo htmlspecialchars($p['name']); ?></h3>
          <p class="author">By: <?php echo htmlspecialchars($p['artisan']); ?></p>
          <div class="price-row">
            <span class="currency">SAR</span>
            <span class="price"><?php echo number_format($p['price'], 0); ?></span>
          </div>
          <button class="view-btn" onclick="event.stopPropagation();Cart.add({id:<?php echo $p['pid']; ?>,name:<?php echo json_encode($p['name']); ?>,price:<?php echo (float)$p['price']; ?>,image:<?php echo json_encode($p['image']); ?>,artisan:<?php echo json_encode($p['artisan']); ?>,type:'product',qty:1})">+ Add to Cart</button>
        </div>
      </a>
      <?php endforeach; ?>
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
          <a href="javascript:void(0);" onclick="filterCategory('all')">All Products</a>
          <a href="javascript:void(0);" onclick="filterCategory('pottery')">Pottery</a>
          <a href="javascript:void(0);" onclick="filterCategory('textiles')">Textiles</a>
          <a href="javascript:void(0);" onclick="filterCategory('metalwork')">Metalwork</a>
          <a href="javascript:void(0);" onclick="filterCategory('leather')">Leather</a>
          <a href="javascript:void(0);" onclick="filterCategory('fragrance')">Fragrance</a>
        </div>
      </div>
      <div>
        <p class="footer-col-title">More</p>
        <div class="footer-links" role="list">
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
      <p>© 2026 Dar Al Hiraf — دار الحرف. All rights reserved.</p>
      <p>A Ministry of Culture Initiative</p>
    </div>
  </div>
</footer>

<script src="cart.js"></script>
<script>
// Instant JavaScript Category Filtering
function filterCategory(categoryName, clickedElement) {
    var cards = document.querySelectorAll('.product-card');
    var visibleCount = 0;
    
    cards.forEach(function(card) {
        var cardCategory = card.getAttribute('data-cat');
        
        if (categoryName === 'all' || cardCategory === categoryName) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Update the layout's dynamic product counter text instantly
    var countDisplay = document.getElementById('product-count');
    if (countDisplay) {
        countDisplay.textContent = visibleCount;
    }

    // Handle high-level active class toggles for strip buttons
    if (clickedElement) {
        var buttons = document.querySelectorAll('.cat-strip-inner .cat-item');
        buttons.forEach(btn => btn.classList.remove('active-cat'));
        clickedElement.classList.add('active-cat');
    } else {
        // If clicked from the footer, sync the top navbar buttons visually
        var topButtons = document.querySelectorAll('.cat-strip-inner .cat-item');
        topButtons.forEach(btn => {
            btn.classList.remove('active-cat');
            if (categoryName === 'all' && btn.textContent.trim() === 'All Crafts') btn.classList.add('active-cat');
            if (btn.textContent.trim().toLowerCase() === categoryName) btn.classList.add('active-cat');
        });
    }
}

// Admin login session gate status handler
(function(){
  var link = document.getElementById('adminNavLink');
  if(!link) return;
  if(sessionStorage.getItem('adminLoggedIn')){
    link.textContent = '→ Admin Dashboard';
    link.href = 'admin-dashboard.php';
  }
})();
</script>
</body>
</html>
