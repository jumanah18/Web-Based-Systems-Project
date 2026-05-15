<?php
$purchases = [];
if (isset($_COOKIE['dh_purchases'])) {
    $purchases = json_decode($_COOKIE['dh_purchases'], true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dar Al Hiraf — دار الحرف | Home</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
<a href="#main-content" class="skip-link">Skip to main content</a>

<nav class="navbar" aria-label="Main navigation">
  <div class="navbar-inner">
    <a href="index.html" class="navbar-logo">
      <span class="logo-ar" lang="ar">دار الحرف</span>
      <span class="logo-en">Dar Al Hiraf</span>
    </a>
    <div class="navbar-nav">
      <a href="index.html" class="active">Home</a>
      <a href="shop.html">Shop</a>
      <a href="workshops.html">Workshops</a>
      <a href="about.html">About</a>
    </div>
    <form class="navbar-search" onsubmit="event.preventDefault();var v=this.querySelector('input').value.trim();if(v)window.location.href='search.html?q='+encodeURIComponent(v);" role="search">
      <input class="navbar-search-input" type="search" placeholder="Search..." aria-label="Search">
      <button class="navbar-search-btn" type="submit" aria-label="Search">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </button>
    </form>
    
    
        <div class="navbar-right">
      <a id="adminNavLink" href="admin-login.html" class="navbar-admin-link">Admin</a>
      <a href="checkout.html" class="navbar-cart-btn" id="cartBtn" aria-label="View cart">
        <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" stroke="#1a1a1a" fill="none" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        <span class="cart-badge" id="cartBadge"></span>
      </a>
    </div>
  </div>
</nav>

</div>
<main id="main-content">

<section class="hero">
  <img class="hero-img" src="images/header.jpg" alt="Saudi artisan at work" />
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">The Year of Handicrafts 2026</p>
    <h1 class="hero-title-ar" lang="ar">دار الحرف</h1>
    <p class="hero-title-en">Dar Al Hiraf</p>
    <p class="hero-desc">Discover authentic handmade Saudi products by skilled local artisans. Each piece tells the story of our culture, heritage, and tradition.</p>
    <div class="hero-btns">
      <a href="shop.html" class="btn btn-gold">Shop Now</a>
      <a href="workshops.html" class="btn btn-white-outline">Our Workshops</a>
    </div>
  </div>
</section>

<div class="cat-strip">
  <div class="cat-strip-inner">
    <a href="shop.html" class="cat-item">All Crafts</a>
    <a href="shop-pottery.html" class="cat-item">Pottery</a>
    <a href="shop-textiles.html" class="cat-item">Textiles</a>
    <a href="shop-metalwork.html" class="cat-item">Metalwork</a>
    <a href="shop-leather.html" class="cat-item">Leather</a>
    <a href="shop-fragrance.html" class="cat-item">Fragrance</a>
  </div>
</div>

<section class="section section-white">
  <div class="container">
    <div class="section-top">
      <div class="section-top-left">
        <p class="eyebrow">Handpicked</p>
        <h2>منتجات مختارة</h2>
        <div class="gold-line"></div>
      </div>
      <a href="shop.html" class="btn btn-green">View All Products &rarr;</a>
    </div>
    <div class="product-grid">
      <a href="product.html?id=p1" class="product-card">
        <div class="image-container">
          <span class="badge featured">FEATURED</span>
          <img src="images/p1_baskets.jpg" alt="Woven Baskets & Embroidered Textiles">
        </div>
        <div class="product-info">
          <p class="category">TEXTILES</p>
          <h3 class="product-title">Woven Baskets &amp; Embroidered Textiles</h3>
          <p class="author">By: Fatima Al-Ghamdi</p>
          <div class="price-row">
            <span class="currency">SAR</span>
            <span class="price">320</span>
          </div>
          <button class="view-btn" onclick="event.stopPropagation();addToCart('p1','Woven Baskets & Embroidered Textiles',320,1)">+ Add to Cart</button>
        </div>
      </a>
      <a href="product.html?id=p4" class="product-card">
        <div class="image-container">
          <span class="badge featured">FEATURED</span>
          <img src="images/p4_khanjar.jpg" alt="Traditional Saudi Khanjar">
        </div>
        <div class="product-info">
          <p class="category">METALWORK</p>
          <h3 class="product-title">Traditional Saudi Khanjar</h3>
          <p class="author">By: Ibrahim Al-Dosari</p>
          <div class="price-row">
            <span class="currency">SAR</span>
            <span class="price">980</span>
          </div>
          <button class="view-btn" onclick="event.stopPropagation();addToCart('p4','Traditional Saudi Khanjar',980,1)">+ Add to Cart</button>
        </div>
      </a>
      <a href="product.html?id=p9" class="product-card">
        <div class="image-container">
          <span class="badge featured">FEATURED</span>
          <img src="images/p9_textile.jpg" alt="Sadu Woven Textile Strip">
        </div>
        <div class="product-info">
          <p class="category">TEXTILES</p>
          <h3 class="product-title">Sadu Woven Textile Strip</h3>
          <p class="author">By: Fatima Al-Ghamdi</p>
          <div class="price-row">
            <span class="currency">SAR</span>
            <span class="price">360</span>
          </div>
          <button class="view-btn" onclick="event.stopPropagation();addToCart('p9','Sadu Woven Textile Strip',360,1)">+ Add to Cart</button>
        </div>
      </a>
      <a href="product.html?id=p12" class="product-card">
        <div class="image-container">
          <span class="badge featured">FEATURED</span>
          <img src="images/p12_wooddoor.jpg" alt="Carved Wooden Heritage Panel">
        </div>
        <div class="product-info">
          <p class="category">WOOD</p>
          <h3 class="product-title">Carved Wooden Heritage Panel</h3>
          <p class="author">By: Khalid Al-Zahrani</p>
          <div class="price-row">
            <span class="currency">SAR</span>
            <span class="price">1,400</span>
          </div>
          <button class="view-btn" onclick="event.stopPropagation();addToCart('p12','Carved Wooden Heritage Panel',1400,1)">+ Add to Cart</button>
        </div>
      </a>
      <a href="product.html?id=p2" class="product-card">
        <div class="image-container">
          
          <img src="images/p2_mortar.jpg" alt="Brass Mortar & Pestle">
        </div>
        <div class="product-info">
          <p class="category">METALWORK</p>
          <h3 class="product-title">Brass Mortar &amp; Pestle</h3>
          <p class="author">By: Hassan Al-Otaibi</p>
          <div class="price-row">
            <span class="currency">SAR</span>
            <span class="price">495</span>
          </div>
          <button class="view-btn" onclick="event.stopPropagation();addToCart('p2','Brass Mortar & Pestle',495,1)">+ Add to Cart</button>
        </div>
      </a>
      <a href="product.html?id=p3" class="product-card">
        <div class="image-container">
          <span class="badge new-item">NEW</span>
          <img src="images/p3_hat.jpg" alt="Palm Leaf Sun Hat">
        </div>
        <div class="product-info">
          <p class="category">TEXTILES</p>
          <h3 class="product-title">Palm Leaf Sun Hat</h3>
          <p class="author">By: Aisha Al-Mutairi</p>
          <div class="price-row">
            <span class="currency">SAR</span>
            <span class="price">145</span>
          </div>
          <button class="view-btn" onclick="event.stopPropagation();addToCart('p3','Palm Leaf Sun Hat',145,1)">+ Add to Cart</button>
        </div>
      </a>
      <a href="product.html?id=p6" class="product-card">
        <div class="image-container">
          <span class="badge new-item">NEW</span>
          <img src="images/p6_jewelry.jpg" alt="Traditional Silver Jewellery">
        </div>
        <div class="product-info">
          <p class="category">METALWORK</p>
          <h3 class="product-title">Traditional Silver Jewellery</h3>
          <p class="author">By: Maha Al-Shehri</p>
          <div class="price-row">
            <span class="currency">SAR</span>
            <span class="price">640</span>
          </div>
          <button class="view-btn" onclick="event.stopPropagation();addToCart('p6','Traditional Silver Jewellery',640,1)">+ Add to Cart</button>
        </div>
      </a>
      <a href="product.html?id=p10" class="product-card">
        <div class="image-container">
          
          <img src="images/p10_clay.jpg" alt="Handmade Clay Pottery Jug">
        </div>
        <div class="product-info">
          <p class="category">POTTERY</p>
          <h3 class="product-title">Handmade Clay Pottery Jug</h3>
          <p class="author">By: Noura Al-Rashidi</p>
          <div class="price-row">
            <span class="currency">SAR</span>
            <span class="price">280</span>
          </div>
          <button class="view-btn" onclick="event.stopPropagation();addToCart('p10','Handmade Clay Pottery Jug',280,1)">+ Add to Cart</button>
        </div>
      </a>
    </div>
  </div>
</section>

<div class="heritage-strip">
  <div class="container">
    <h2>نحافظ على التراث السعودي</h2>
    <p class="sub">Preserving Saudi heritage through handmade crafts — connecting artisans with those who value authenticity.</p>
    <div class="stats-row">
      <div class="stat-item"><span class="stat-num">120+</span><span class="stat-label">Local Artisans</span></div>
      <div class="stat-item"><span class="stat-num">12</span><span class="stat-label">Craft Types</span></div>
      <div class="stat-item"><span class="stat-num">18</span><span class="stat-label">Saudi Regions</span></div>
      <div class="stat-item"><span class="stat-num">8K+</span><span class="stat-label">Happy Customers</span></div>
    </div>
  </div>
</div>

<section class="section section-green">
  <div class="container">
    <div class="section-top">
      <div class="section-top-left">
        <p class="eyebrow" style="color:var(--gold)">Hands-on Learning</p>
        <h2 class="light">ورش العمل</h2>
        <div class="gold-line"></div>
      </div>
    </div>
    <div class="workshops-grid">
      <a href="workshops.html#workshop-1" class="workshop-card">
        <div class="wc-img"><img src="images/p5_weaving.jpg" alt="Basket Weaving" /></div>
        <div class="wc-body">
          <p class="wc-tag">Basket Weaving</p>
          <p class="wc-title" lang="ar">ورشة الخوص التقليدية</p>
          <p class="wc-title-en">Traditional Palm Weaving</p>
          <p class="wc-desc">Learn the traditional Khousse palm-leaf weaving technique with a master artisan from Al-Ahsa.</p>
          <div class="wc-meta">
            <div class="wc-info"><span>May 14, 2026</span><span>3 hours &middot; 12 seats</span><span>Al-Ahsa</span></div>
            <span class="wc-price">280 SAR</span>
          </div>
        </div>
      </a>
      <a href="workshops.html#workshop-2" class="workshop-card">
        <div class="wc-img"><img src="images/p9_textile.jpg" alt="Sadu Weaving" /></div>
        <div class="wc-body">
          <p class="wc-tag">Textiles</p>
          <p class="wc-title" lang="ar">ورشة نسيج السدو</p>
          <p class="wc-title-en">Sadu Weaving Workshop</p>
          <p class="wc-desc">Master the iconic Sadu weaving — a UNESCO-recognized Bedouin craft — with Fatima Al-Ghamdi in Abha.</p>
          <div class="wc-meta">
            <div class="wc-info"><span>May 21, 2026</span><span>4 hours &middot; 8 seats</span><span>Abha, Asir</span></div>
            <span class="wc-price">340 SAR</span>
          </div>
        </div>
      </a>
      <a href="workshops.html#workshop-3" class="workshop-card">
        <div class="wc-img"><img src="images/p2_mortar.jpg" alt="Metalwork" /></div>
        <div class="wc-body">
          <p class="wc-tag">Metalwork</p>
          <p class="wc-title" lang="ar">ورشة النحاس والبرونز</p>
          <p class="wc-title-en">Brass &amp; Copper Crafting</p>
          <p class="wc-desc">Shape, engrave and polish traditional brass pieces with Hassan Al-Otaibi in Riyadh.</p>
          <div class="wc-meta">
            <div class="wc-info"><span>June 5, 2026</span><span>5 hours &middot; 6 seats</span><span>Riyadh</span></div>
            <span class="wc-price">420 SAR</span>
          </div>
        </div>
      </a>
    </div>
  </div>
</section>

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
          <a href="shop.html">All Products</a>
          <a href="shop-pottery.html">Pottery</a>
          <a href="shop-textiles.html">Textiles</a>
          <a href="shop-metalwork.html">Metalwork</a>
          <a href="shop-leather.html">Leather</a>
        </div>
      </div>
      <div>
        <p class="footer-col-title">More</p>
        <div class="footer-links" role="list">
          <a href="about.html">About Us</a>
          <a href="workshops.html">Workshops</a>
          <a href="contact.html">Contact Us</a>
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
<script>
  (function(){
    var link = document.getElementById('adminNavLink');
    if(!link) return;
    if(sessionStorage.getItem('adminLoggedIn')){
      link.textContent = '→ Admin Dashboard';
      link.href = 'admin-dashboard.html';
    }
  })();
</script>
<script src="cart.js"></script>
</body>
</html>
