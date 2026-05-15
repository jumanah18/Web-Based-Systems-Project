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
  <title>Dar Al Hiraf — Modify Product</title>
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
        <a href="admin-dashboard.html">&#x2190; Admin Dashboard</a> &nbsp;/&nbsp; Modify Product
      </p>
      <h1>&#x270F; Modify Existing Product</h1>
      <p>Select a product below to edit its details, image, price, or artisan information.</p>
    </div>

    <div class="admin-search-bar">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="productSearch" placeholder="Search by name, artisan, or category..." oninput="filterProducts()" aria-label="Search products" />
    </div>
    <div class="admin-filter-btns">
      <button class="admin-filter-btn active" onclick="filterByCategory('all',this)">All</button>
      <button class="admin-filter-btn" onclick="filterByCategory('Textiles',this)">Textiles</button>
      <button class="admin-filter-btn" onclick="filterByCategory('Metalwork',this)">Metalwork</button>
      <button class="admin-filter-btn" onclick="filterByCategory('Pottery',this)">Pottery</button>
      <button class="admin-filter-btn" onclick="filterByCategory('Leather',this)">Leather</button>
      <button class="admin-filter-btn" onclick="filterByCategory('Fragrance',this)">Fragrance</button>
      <button class="admin-filter-btn" onclick="filterByCategory('Wood',this)">Wood</button>
    </div>
    <div id="noResults" style="display:none;text-align:center;padding:40px;color:#888;font-size:14px;">No products found.</div>
    <div class="product-grid" id="productsGrid">
      <div class="product-card" id="card-p1" data-search="woven baskets  embroidered textiles textiles fatima al-ghamdi" data-category="Textiles">
        <div class="image-container">
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
          <button class="edit-btn" onclick="openEdit('p1','Woven Baskets &amp; Embroidered Textiles','سلال وملابس مطرزة','Fatima Al-Ghamdi','Al-Ahsa, Eastern Province','320','Textiles','p1_baskets.jpg','Featured','Handwoven baskets and embroidered textiles by skilled artisans from Al-Ahsa.')">— EDIT PRODUCT</button>
        </div>
      </div>
      <div class="product-card" id="card-p2" data-search="brass mortar  pestle metalwork hassan al-otaibi" data-category="Metalwork">
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
          <button class="edit-btn" onclick="openEdit('p2','Brass Mortar &amp; Pestle','هاون نحاسي تقليدي','Hassan Al-Otaibi','Riyadh','495','Metalwork','p2_mortar.jpg','','Traditional brass mortar and pestle handcrafted by master metalsmith Hassan Al-Otaibi in Riyadh.')">— EDIT PRODUCT</button>
        </div>
      </div>
      <div class="product-card" id="card-p3" data-search="palm leaf sun hat textiles aisha al-mutairi" data-category="Textiles">
        <div class="image-container">
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
          <button class="edit-btn" onclick="openEdit('p3','Palm Leaf Sun Hat','قبعة سعف النخيل','Aisha Al-Mutairi','Madinah','145','Textiles','p3_hat.jpg','New','A traditional sun hat handwoven from date palm leaves, crafted by Aisha Al-Mutairi.')">— EDIT PRODUCT</button>
        </div>
      </div>
      <div class="product-card" id="card-p4" data-search="traditional saudi khanjar metalwork ibrahim al-dosari" data-category="Metalwork">
        <div class="image-container">
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
          <button class="edit-btn" onclick="openEdit('p4','Traditional Saudi Khanjar','خنجر سعودي تقليدي','Ibrahim Al-Dosari','Najran','980','Metalwork','p4_khanjar.jpg','Featured','A ceremonial Saudi khanjar handcrafted from silver and steel by Ibrahim Al-Dosari of Najran.')">— EDIT PRODUCT</button>
        </div>
      </div>
      <div class="product-card" id="card-p5" data-search="traditional palm weaving textiles fatima al-ghamdi" data-category="Textiles">
        <div class="image-container">
          
          <img src="images/p5_weaving.jpg" alt="Traditional Palm Weaving">
        </div>
        <div class="product-info">
          <p class="category">TEXTILES</p>
          <h3 class="product-title">Traditional Palm Weaving</h3>
          <p class="author">By: Fatima Al-Ghamdi</p>
          <div class="price-row">
            <span class="currency">SAR</span>
            <span class="price">210</span>
          </div>
          <button class="edit-btn" onclick="openEdit('p5','Traditional Palm Weaving','نسيج السعف التقليدي','Fatima Al-Ghamdi','Al-Ahsa, Eastern Province','210','Textiles','p5_weaving.jpg','','A beautifully woven piece using traditional Khousse palm-leaf weaving technique from Al-Ahsa.')">— EDIT PRODUCT</button>
        </div>
      </div>
      <div class="product-card" id="card-p6" data-search="traditional silver jewellery metalwork maha al-shehri" data-category="Metalwork">
        <div class="image-container">
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
          <button class="edit-btn" onclick="openEdit('p6','Traditional Silver Jewellery','حلي فضية تقليدية','Maha Al-Shehri','Jizan','640','Metalwork','p6_jewelry.jpg','New','Handcrafted traditional silver jewellery featuring intricate patterns inspired by Saudi tribal heritage.')">— EDIT PRODUCT</button>
        </div>
      </div>
      <div class="product-card" id="card-p7" data-search="palm weave decorative basket textiles aisha al-mutairi" data-category="Textiles">
        <div class="image-container">
          
          <img src="images/p7_palmweave.jpg" alt="Palm Weave Decorative Basket">
        </div>
        <div class="product-info">
          <p class="category">TEXTILES</p>
          <h3 class="product-title">Palm Weave Decorative Basket</h3>
          <p class="author">By: Aisha Al-Mutairi</p>
          <div class="price-row">
            <span class="currency">SAR</span>
            <span class="price">175</span>
          </div>
          <button class="edit-btn" onclick="openEdit('p7','Palm Weave Decorative Basket','سلة خوص زخرفية','Aisha Al-Mutairi','Madinah','175','Textiles','p7_palmweave.jpg','','A decorative basket handwoven from natural palm leaves using traditional techniques.')">— EDIT PRODUCT</button>
        </div>
      </div>
      <div class="product-card" id="card-p8" data-search="taif rose water  oud set fragrance khalid al-zahrani" data-category="Fragrance">
        <div class="image-container">
          
          <img src="images/p8_roses.jpg" alt="Taif Rose Water & Oud Set">
        </div>
        <div class="product-info">
          <p class="category">FRAGRANCE</p>
          <h3 class="product-title">Taif Rose Water &amp; Oud Set</h3>
          <p class="author">By: Khalid Al-Zahrani</p>
          <div class="price-row">
            <span class="currency">SAR</span>
            <span class="price">385</span>
          </div>
          <button class="edit-btn" onclick="openEdit('p8','Taif Rose Water &amp; Oud Set','ماء ورد الطائف وعود','Khalid Al-Zahrani','Taif, Makkah Region','385','Fragrance','p8_roses.jpg','','Premium Taif rose water and oud fragrance set, a signature gift from the City of Roses.')">— EDIT PRODUCT</button>
        </div>
      </div>
      <div class="product-card" id="card-p9" data-search="sadu woven textile strip textiles fatima al-ghamdi" data-category="Textiles">
        <div class="image-container">
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
          <button class="edit-btn" onclick="openEdit('p9','Sadu Woven Textile Strip','شريط نسيج سدو تقليدي','Fatima Al-Ghamdi','Abha, Asir Region','360','Textiles','p9_textile.jpg','Featured','A UNESCO-recognized Sadu woven textile strip made using a traditional ground loom by Fatima Al-Ghamdi.')">— EDIT PRODUCT</button>
        </div>
      </div>
      <div class="product-card" id="card-p10" data-search="handmade clay pottery jug pottery noura al-rashidi" data-category="Pottery">
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
          <button class="edit-btn" onclick="openEdit('p10','Handmade Clay Pottery Jug','إبريق فخار طيني','Noura Al-Rashidi','Al-Qatif, Eastern Province','280','Pottery','p10_clay.jpg','','A handmade clay pottery jug shaped and glazed by Noura Al-Rashidi using locally sourced red clay.')">— EDIT PRODUCT</button>
        </div>
      </div>
      <div class="product-card" id="card-p11" data-search="handcrafted leather satchel leather ibrahim al-dosari" data-category="Leather">
        <div class="image-container">
          
          <img src="images/p11_leather.jpg" alt="Handcrafted Leather Satchel">
        </div>
        <div class="product-info">
          <p class="category">LEATHER</p>
          <h3 class="product-title">Handcrafted Leather Satchel</h3>
          <p class="author">By: Ibrahim Al-Dosari</p>
          <div class="price-row">
            <span class="currency">SAR</span>
            <span class="price">720</span>
          </div>
          <button class="edit-btn" onclick="openEdit('p11','Handcrafted Leather Satchel','حقيبة جلدية يدوية الصنع','Ibrahim Al-Dosari','Najran','720','Leather','p11_leather.jpg','','A hand-stitched leather satchel crafted from premium tanned leather with traditional Saudi embossed patterns.')">— EDIT PRODUCT</button>
        </div>
      </div>
      <div class="product-card" id="card-p12" data-search="carved wooden heritage panel wood khalid al-zahrani" data-category="Wood">
        <div class="image-container">
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
          <button class="edit-btn" onclick="openEdit('p12','Carved Wooden Heritage Panel','باب خشبي تقليدي منحوت','Khalid Al-Zahrani','Taif, Makkah Region','1400','Wood','p12_wooddoor.jpg','Featured','A large intricately carved wooden heritage panel inspired by traditional Saudi architectural motifs.')">— EDIT PRODUCT</button>
        </div>
      </div>
    </div>
  </div>
</div>
  </div>
</div>

<!-- EDIT MODAL -->
<div class="edit-overlay" id="editOverlay" onclick="if(event.target===this)closeEdit()">
  <div class="edit-modal">
    <button class="edit-modal-close" onclick="closeEdit()" aria-label="Close">&times;</button>
    <h2 id="editModalTitle">&#x270F; Edit Product</h2>

    <p class="form-section-title">Product Image</p>
    <div class="current-image-preview">
      <img id="currentImg" src="" alt="Current product image" />
      <p class="current-image-label">Current Image</p>
    </div>
    <div class="image-upload-area">
      <input type="file" id="editProductImage" accept="image/*" onchange="previewEditImage(this)" />
      <div id="editUploadDefault">
        <p class="upload-text"><strong>Click to replace image</strong> or drag &amp; drop</p>
        <p class="upload-hint">JPG, PNG, WEBP &mdash; Max 5MB</p>
      </div>
      <div class="new-image-preview" id="editNewImgPreview" style="display:none;">
        <img id="editNewImg" src="" alt="New image preview"  />
        <p class="image-preview-label" style="margin-top:8px;color:var(--gold);">&#x2713; New image selected</p>
      </div>
    </div>

    <p class="form-section-title">Basic Information</p>
    <div class="form-row">
      <div class="form-group">
        <label for="editName">Product Name (English)</label>
        <input type="text" id="editName" />
      </div>
      <div class="form-group">
        <label for="editNameAr">Product Name (Arabic)</label>
        <input type="text" id="editNameAr" dir="rtl" />
      </div>
    </div>
    <div class="form-group">
      <label for="editDesc">Description</label>
      <textarea id="editDesc"></textarea>
    </div>

    <p class="form-section-title">Pricing &amp; Category</p>
    <div class="form-row">
      <div class="form-group">
        <label for="editPrice">Price (SAR)</label>
        <input type="number" id="editPrice" min="1" />
      </div>
      <div class="form-group">
        <label for="editCategory">Category</label>
        <select id="editCategory">
          <option value="Textiles">Textiles</option>
          <option value="Metalwork">Metalwork</option>
          <option value="Pottery">Pottery</option>
          <option value="Leather">Leather</option>
          <option value="Fragrance">Fragrance</option>
          <option value="Wood">Wood</option>
        </select>
      </div>
    </div>

    <p class="form-section-title">Artisan Information</p>
    <div class="form-row">
      <div class="form-group">
        <label for="editArtisan">Artisan Name</label>
        <input type="text" id="editArtisan" />
      </div>
      <div class="form-group">
        <label for="editRegion">Region / City</label>
        <input type="text" id="editRegion" />
      </div>
    </div>

    <div class="form-actions">
      <button class="btn-save" onclick="saveEdit()">&#x2705; Save Changes</button>
      <button class="btn-cancel-modal" onclick="closeEdit()">Cancel</button>
      <label for="deleteToggle" class="btn-delete-prominent" role="button" tabindex="0">🗑&nbsp; Delete Product</label>
    </div>
  </div>
</div>

<input type="checkbox" id="deleteToggle" />
<div class="delete-confirm-overlay">
  <div class="delete-confirm-box">
    <h3>&#x26A0; Delete Product?</h3>
    <p>This action cannot be undone. Are you sure you want to permanently delete this product?</p>
    <div class="delete-actions">
      <button class="btn-confirm-delete">&#x1F5D1; Yes, Delete</button>
      <label for="deleteToggle" class="btn-cancel-delete">Cancel</label>
    </div>
  </div>
</div>

<div class="success-toast" id="successToast">
  &#x2705; &nbsp; Product updated successfully!
</div>

<footer class="footer">
  <div class="container">
    <div class="footer-bottom" style="text-align:center;padding:20px 0;">
      <p>&copy; 2026 Dar Al Hiraf &mdash; Admin Portal.</p>
    </div>
  </div>
</footer>

<script>
  var currentProductId = '';

  function openEdit(id, name, nameAr, artisan, region, price, category, img, badge, desc) {
    currentProductId = id;
    document.getElementById('editModalTitle').textContent = '\u270F Edit: ' + name.replace(/&amp;/g, '&');
    document.getElementById('editName').value = name.replace(/&amp;/g, '&');
    document.getElementById('editNameAr').value = nameAr;
    document.getElementById('editArtisan').value = artisan;
    document.getElementById('editRegion').value = region;
    document.getElementById('editPrice').value = price;
    document.getElementById('editDesc').value = desc;
    document.getElementById('editCategory').value = category;
    document.getElementById('currentImg').src = 'images/' + img;
    document.getElementById('editNewImgPreview').style.display = 'none';
    document.getElementById('editProductImage').value = '';
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
        document.getElementById('editUploadDefault').style.display = 'none';
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


<script>
  var _cat='all';
  function filterProducts(){
    var q=document.getElementById('productSearch').value.toLowerCase();
    var cards=document.querySelectorAll('.product-card'),vis=0;
    cards.forEach(function(c){
      var ok=(!q||c.dataset.search.includes(q))&&(_cat==='all'||c.dataset.category===_cat);
      c.style.display=ok?'':'none'; if(ok)vis++;
    });
    document.getElementById('noResults').style.display=vis?'none':'block';
  }
  function filterByCategory(cat,btn){
    _cat=cat;
    document.querySelectorAll('.admin-filter-btn').forEach(function(b){b.classList.remove('active')});
    btn.classList.add('active'); filterProducts();
  }
  document.addEventListener('DOMContentLoaded',function(){
    if(!sessionStorage.getItem('adminLoggedIn'))window.location.href='admin-login.html';
  });
</script>
<script src="cart.js"></script>
</body>
</html>
