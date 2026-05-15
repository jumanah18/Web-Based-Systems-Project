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
  <title>Dar Al Hiraf — Modify Workshop</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<nav class="navbar" aria-label="Main navigation">
  <div class="navbar-inner">
    <a href="index.html" class="navbar-logo">
      <span class="logo-ar" lang="ar">دار الحرف</span>
      <span class="logo-en">Dar Al Hiraf</span>
    </a>
    <div class="navbar-nav">
      <a href="index.html">Home</a>
      <a href="shop.html">Shop</a>
      <a href="workshops.html">Workshops</a>
      <a href="about.html">About</a>
    </div>
        <div class="navbar-right">
      <a id="adminNavLink" href="admin-login.html" class="navbar-admin-link">Admin</a>
      <a href="checkout.html" class="navbar-cart-btn" id="cartBtn" aria-label="View cart">
        <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" stroke="#1a1a1a" fill="none" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        <span class="cart-badge" id="cartBadge"></span>
      </a>
    </div>
  </div>
</nav>
<div class="back-bar">
  <div class="container">
    <a href="admin-dashboard.html" class="back-link">&#8592; Back to Admin Dashboard</a>
  </div>
</div>

<div class="admin-page">
  <div class="admin-container">

    <div class="admin-page-header">
      <p class="admin-breadcrumb">
        <a href="admin-dashboard.html">&#x2190; Admin Dashboard</a> &nbsp;/&nbsp; Modify Workshop
      </p>
      <h1>&#x1F527; Modify Existing Workshop</h1>
      <p>Select a workshop below to edit its details, image, dates, seats, or pricing.</p>
    </div>

    <div class="workshops-admin-grid">

      <!-- Workshop 1 -->
      <div class="workshop-admin-card">
        <div class="wac-img"><img src="images/p5_weaving.jpg" alt="Traditional Palm Weaving" /></div>
        <div class="wac-body">
          <p class="wac-tag">Basket Weaving</p>
          <p class="wac-title">Traditional Palm Weaving</p>
          <p class="wac-title-ar" lang="ar">ورشة الخوص التقليدية</p>
          <p class="wac-meta">&#128205; Al-Ahsa Heritage Center</p>
          <p class="wac-meta">&#8987; 3 hours &nbsp;|&nbsp; 12 seats</p>
          <p class="wac-meta">&#128197; May 14, 2026</p>
          <p class="wac-price"><small>SAR</small> 280</p>
          <button class="wac-btn" onclick="openEdit('w1','Traditional Palm Weaving','ورشة الخوص التقليدية','Basket Weaving','Fatima Al-Ghamdi','Al-Ahsa Heritage Center, Al-Ahsa, Eastern Province','3','12','280','Learn the traditional Khousse palm-leaf weaving technique with a master artisan from Al-Ahsa. All materials are provided. Suitable for beginners.','p5_weaving.jpg')">&#x270F; Edit Workshop</button>
        </div>
      </div>

      <!-- Workshop 2 -->
      <div class="workshop-admin-card">
        <div class="wac-img"><img src="images/p9_textile.jpg" alt="Sadu Weaving" /></div>
        <div class="wac-body">
          <p class="wac-tag">Textiles</p>
          <p class="wac-title">Sadu Weaving Workshop</p>
          <p class="wac-title-ar" lang="ar">ورشة نسيج السدو</p>
          <p class="wac-meta">&#128205; Asir Cultural Center, Abha</p>
          <p class="wac-meta">&#8987; 4 hours &nbsp;|&nbsp; 8 seats</p>
          <p class="wac-meta">&#128197; May 21, 2026</p>
          <p class="wac-price"><small>SAR</small> 340</p>
          <button class="wac-btn" onclick="openEdit('w2','Sadu Weaving Workshop','ورشة نسيج السدو','Textiles','Fatima Al-Ghamdi','Asir Cultural Center, Abha, Asir Region','4','8','340','Master the iconic Sadu weaving — a UNESCO-recognized Bedouin craft. Learn to use a traditional ground loom and create your own Sadu-patterned piece with Fatima Al-Ghamdi.','p9_textile.jpg')">&#x270F; Edit Workshop</button>
        </div>
      </div>

      <!-- Workshop 3 -->
      <div class="workshop-admin-card">
        <div class="wac-img"><img src="images/p2_mortar.jpg" alt="Brass Copper Crafting" /></div>
        <div class="wac-body">
          <p class="wac-tag">Metalwork</p>
          <p class="wac-title">Brass &amp; Copper Crafting</p>
          <p class="wac-title-ar" lang="ar">ورشة النحاس والبرونز</p>
          <p class="wac-meta">&#128205; Riyadh Craft District</p>
          <p class="wac-meta">&#8987; 5 hours &nbsp;|&nbsp; 6 seats</p>
          <p class="wac-meta">&#128197; June 5, 2026</p>
          <p class="wac-price"><small>SAR</small> 420</p>
          <button class="wac-btn" onclick="openEdit('w3','Brass &amp; Copper Crafting','ورشة النحاس والبرونز','Metalwork','Hassan Al-Otaibi','Riyadh Craft District, Al-Murabba, Riyadh','5','6','420','Shape, engrave and polish traditional brass pieces in this immersive metalwork session with master craftsman Hassan Al-Otaibi. Take home your own handmade piece.','p2_mortar.jpg')">&#x270F; Edit Workshop</button>
        </div>
      </div>

      <!-- Workshop 4 -->
      <div class="workshop-admin-card">
        <div class="wac-img"><img src="images/p10_clay.jpg" alt="Traditional Pottery" /></div>
        <div class="wac-body">
          <p class="wac-tag">Pottery</p>
          <p class="wac-title">Traditional Pottery Workshop</p>
          <p class="wac-title-ar" lang="ar">ورشة الفخار التقليدي</p>
          <p class="wac-meta">&#128205; Al-Qatif Heritage Village</p>
          <p class="wac-meta">&#8987; 3 hours &nbsp;|&nbsp; 10 seats</p>
          <p class="wac-meta">&#128197; June 18, 2026</p>
          <p class="wac-price"><small>SAR</small> 260</p>
          <button class="wac-btn" onclick="openEdit('w4','Traditional Pottery Workshop','ورشة الفخار التقليدي','Pottery','Noura Al-Rashidi','Al-Qatif Heritage Village, Al-Qatif, Eastern Province','3','10','260','Shape and glaze your own clay piece under the guidance of Noura Al-Rashidi. Using locally sourced red clay, you will create a unique handmade piece to keep.','p10_clay.jpg')">&#x270F; Edit Workshop</button>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- EDIT MODAL -->
<div class="edit-overlay" id="editOverlay" onclick="if(event.target===this)closeEdit()">
  <div class="edit-modal">
    <button class="edit-modal-close" onclick="closeEdit()" aria-label="Close">&times;</button>
    <h2 id="editModalTitle">&#x270F; Edit Workshop</h2>

    <p class="form-section-title">Workshop Banner Image</p>
    <div class="current-image-preview">
      <img id="currentImg" src="" alt="Current workshop image" />
      <p class="current-image-label">Current Image</p>
    </div>
    <div class="image-upload-area">
      <input type="file" id="editWorkshopImage" accept="image/*" onchange="previewEditImage(this)" />
      <p class="upload-text"><strong>Click to replace image</strong> or drag &amp; drop</p>
      <p class="upload-hint">JPG, PNG, WEBP — Max 5MB</p>
    </div>
    <div class="new-image-preview" id="editNewImgPreview">
      <img id="editNewImg" src="" alt="New image preview" />
      <p class="current-image-label" style="color:var(--gold);">&#x2713; New image selected</p>
    </div>

    <p class="form-section-title">Workshop Details</p>
    <div class="form-row">
      <div class="form-group">
        <label for="editTitle">Title (English)</label>
        <input type="text" id="editTitle" />
      </div>
      <div class="form-group">
        <label for="editTitleAr">Title (Arabic)</label>
        <input type="text" id="editTitleAr" dir="rtl" />
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="editCategory">Category</label>
        <select id="editCategory">
          <option>Basket Weaving</option>
          <option>Textiles</option>
          <option>Metalwork</option>
          <option>Pottery</option>
          <option>Leather</option>
          <option>Woodwork</option>
          <option>Fragrance</option>
          <option>Calligraphy</option>
        </select>
      </div>
      <div class="form-group">
        <label for="editArtisan">Lead Artisan</label>
        <input type="text" id="editArtisan" />
      </div>
    </div>
    <div class="form-group">
      <label for="editDesc">Description</label>
      <textarea id="editDesc"></textarea>
    </div>

    <p class="form-section-title">Location &amp; Logistics</p>
    <div class="form-group">
      <label for="editLocation">Full Location / Venue</label>
      <input type="text" id="editLocation" />
    </div>
    <div class="form-row-3">
      <div class="form-group">
        <label for="editDuration">Duration (hours)</label>
        <input type="number" id="editDuration" min="1" max="12" />
      </div>
      <div class="form-group">
        <label for="editSeats">Available Seats</label>
        <input type="number" id="editSeats" min="1" />
      </div>
      <div class="form-group">
        <label for="editPrice">Price (SAR)</label>
        <input type="number" id="editPrice" min="1" />
      </div>
    </div>

    <div class="form-actions">
      <button class="btn-save" onclick="saveEdit()">&#x2705; Save Changes</button>
      <button class="btn-cancel-modal" onclick="closeEdit()">Cancel</button>
      <label for="deleteToggle" class="btn-delete-prominent" role="button" tabindex="0">🗑&nbsp; Delete Workshop</label>
    </div>
  </div>
</div>

<input type="checkbox" id="deleteToggle" />
<div class="delete-confirm-overlay">
  <div class="delete-confirm-box">
    <h3>&#x26A0; Delete Workshop?</h3>
    <p>This action cannot be undone. Are you sure you want to permanently delete this workshop?</p>
    <div class="delete-actions">
      <button class="btn-confirm-delete">&#x1F5D1; Yes, Delete</button>
      <label for="deleteToggle" class="btn-cancel-delete">Cancel</label>
    </div>
  </div>
</div>

<div class="success-toast" id="successToast">
  &#x2705; &nbsp; Workshop updated successfully!
</div>

<footer class="footer">
  <div class="container">
    <div class="footer-bottom" style="text-align:center;padding:20px 0;">
      <p>&copy; 2026 Dar Al Hiraf &mdash; Admin Portal.</p>
    </div>
  </div>
</footer>

<script>
  function openEdit(id, title, titleAr, category, artisan, location, duration, seats, price, desc, img) {
    document.getElementById('editModalTitle').textContent = '\u270F Edit: ' + title.replace(/&amp;/g, '&');
    document.getElementById('editTitle').value = title.replace(/&amp;/g, '&');
    document.getElementById('editTitleAr').value = titleAr;
    document.getElementById('editCategory').value = category;
    document.getElementById('editArtisan').value = artisan;
    document.getElementById('editLocation').value = location;
    document.getElementById('editDuration').value = duration;
    document.getElementById('editSeats').value = seats;
    document.getElementById('editPrice').value = price;
    document.getElementById('editDesc').value = desc;
    document.getElementById('currentImg').src = 'images/' + img;
    document.getElementById('editNewImgPreview').style.display = 'none';
    document.getElementById('editWorkshopImage').value = '';
    document.getElementById('editOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeEdit() {
    document.getElementById('editOverlay').classList.remove('open');
    document.body.style.overflow = '';
  }

  function previewEditImage(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('editNewImg').src = e.target.result;
        document.getElementById('editNewImgPreview').style.display = 'block';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  function saveEdit() {
    closeEdit();
    var toast = document.getElementById('successToast');
    toast.classList.add('show');
    setTimeout(function() { toast.classList.remove('show'); }, 3200);
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeEdit();
  });
</script>
<script src="cart.js"></script>
</body>
</html>
