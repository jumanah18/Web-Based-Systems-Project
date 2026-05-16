<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dar Al Hiraf — Woven Baskets & Embroidered Textiles</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<nav class="navbar">
  <div class="navbar-inner">
    <a href="index.php" class="navbar-logo">
      <span class="logo-ar">دار الحرف</span>
      <span class="logo-en">Dar Al Hiraf</span>
    </a>
    <div class="navbar-nav">
      <a href="index.php" class="active">Home</a>
      <a href="shop.php">Shop</a>
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
      <a id="adminNavLink" href="admin-login.php" class="navbar-admin-link">Admin</a>
      <a href="checkout.html" class="navbar-cart-btn" id="cartBtn" aria-label="View cart">
        <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" stroke="#1a1a1a" fill="none" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        <span class="cart-badge" id="cartBadge"></span>
      </a>
    </div>
  </div>
</nav>

<div class="breadcrumb">
  <div class="breadcrumb-row">
    <a href="index.php">Home</a>
    <span class="bc-sep">/</span>
    <a href="contact.php">Contact Us</a>
  </div>
</div>

<section class="contact-wrap">
  <div class="container">
    <div class="contact-grid">
      
      <div class="contact-info-panel">
        <h1 class="contact-title-ar">تواصل معنا</h1>
        <h2 class="contact-title-en">Contact Us</h2>
        <div class="gold-line" style="margin-bottom: 30px;"></div>

        <div class="contact-item">
          <span class="contact-label">Mailing Address</span>
          <p class="contact-value">
            Dar Al Hiraf Gallery<br>
            Building 72, Al-Turaif District<br>
            Diriyah, Riyadh 13711<br>
            Kingdom of Saudi Arabia
          </p>
        </div>

        <div class="contact-item">
          <span class="contact-label">Phone</span>
          <p class="contact-value">
            <a href="tel:+966110000000" class="contact-link">+966 11 000 0000</a>
          </p>
        </div>

        <div class="contact-item">
          <span class="contact-label">Email</span>
          <p class="contact-value">
            <a href="mailto:hello@daralhiraf.sa" class="contact-link">hello@daralhiraf.sa</a>
          </p>
        </div>
      </div>

      <div class="contact-map-panel">
        <div class="map-frame">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3623.165440476442!2d46.5734!3d24.6865!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjTCsDQxJzExLjQiTiA0NiwzNCcyNC4yIkU!5e0!3m2!1sen!2ssa!4v1700000000000" 
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
          </iframe>
        </div>
      </div>

      <!-- Form Section -->
      <div class="contact-form-panel">
        <h3 class="form-title">Send Us a Message</h3>
        <div class="gold-line" style="margin-bottom: 24px;"></div>

        <?php if(isset($_GET['success'])): ?>
          <div style="background:#e8f5e9; color:#2e7d32; padding:14px 18px; border-radius:8px; margin-bottom:20px; font-weight:700;">
            ✅ Message sent!
          </div>
        <?php endif; ?>

        <form action="send.php" method="POST" class="contact-form">
          
          <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Your name" required>
          </div>

          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="your@email.com" required>
          </div>

          <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="How can we help?">
          </div>

          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" placeholder="Write your message here..." required></textarea>
          </div>

          <button type="submit" class="form-submit-btn">Send Message →</button>

        </form>
      </div>

    </div>
  </div>
</section>

<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <p class="footer-brand-name">دار الحرف</p>
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
          <a href="contact.php">Contact Us</a>
          <a href="workshops.html">Workshops</a>
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

<script>
  (function(){
    var link = document.getElementById('adminNavLink');
    if(!link) return;
    if(sessionStorage.getItem('adminLoggedIn')){
      link.textContent = '→ Admin Dashboard';
      link.href = 'admin-dashboard.php';
    }
  })();
</script>
<script src="cart.js"></script>
</body>
</html>