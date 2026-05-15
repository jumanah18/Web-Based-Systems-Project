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
  <title>Dar Al Hiraf — Add Product</title>
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
        <a href="admin-dashboard.html">&#x2190; Admin Dashboard</a> &nbsp;/&nbsp; Add Product
      </p>
      <h1>&#x2795; Add New Craft Product</h1>
      <p>Fill in the details below to add a new handcrafted item to the Dar Al Hiraf shop.</p>
    </div>

    <div class="success-banner" id="successBanner">
      <span style="font-size:20px;">&#x2705;</span>
      <span>Product added successfully! It is now visible in the shop.</span>
    </div>

    <div class="admin-form-card">

      <!-- Image Upload -->
      <p class="form-section-title">Product Image</p>
      <div class="form-group">
        <label>Upload Product Photo <span class="req">*</span></label>
        <div class="image-upload-area" id="uploadArea">
          <input type="file" id="productImage" accept="image/*" onchange="previewImage(this)" />
          <div id="uploadDefault">
            <div class="upload-icon">&#x1F4F7;</div>
            <p class="upload-text"><strong>Click to upload</strong> or drag &amp; drop an image</p>
            <p class="upload-hint">Supports: JPG, PNG, WEBP &mdash; Max 5MB &mdash; Recommended 800&times;600px</p>
          </div>
          <div id="uploadPreviewInner" style="display:none;">
            <img id="previewImg" src="" alt="Preview"  />
            <p class="image-preview-label" style="margin-top:10px;">&#x2713; Image ready to upload</p>
          </div>
        </div>
      </div>

      <!-- Basic Info -->
      <p class="form-section-title">Basic Information</p>
      <div class="form-row">
        <div class="form-group">
          <label for="productName">Product Name (English) <span class="req">*</span></label>
          <input type="text" id="productName" placeholder="e.g. Woven Baskets & Embroidered Textiles" />
        </div>
        <div class="form-group">
          <label for="productNameAr">Product Name (Arabic)</label>
          <input type="text" id="productNameAr" placeholder="e.g. سلال وملابس مطرزة" dir="rtl" />
        </div>
      </div>
      <div class="form-group">
        <label for="productDesc">Product Description <span class="req">*</span></label>
        <textarea id="productDesc" placeholder="Describe this handcrafted item — its origins, materials, technique, and what makes it special..."></textarea>
      </div>

      <!-- Pricing & Category -->
      <p class="form-section-title">Pricing &amp; Category</p>
      <div class="form-row">
        <div class="form-group">
          <label for="productPrice">Price (SAR) <span class="req">*</span></label>
          <input type="number" id="productPrice" placeholder="e.g. 320" min="1" />
        </div>
        <div class="form-group">
          <label for="productCategory">Category <span class="req">*</span></label>
          <select id="productCategory">
            <option value="">Select a category...</option>
            <option value="Textiles">Textiles</option>
            <option value="Metalwork">Metalwork</option>
            <option value="Pottery">Pottery</option>
            <option value="Leather">Leather</option>
            <option value="Fragrance">Fragrance</option>
            <option value="Wood">Wood</option>
          </select>
        </div>
      </div>

      <!-- Artisan Info -->
      <p class="form-section-title">Artisan Information</p>
      <div class="form-row">
        <div class="form-group">
          <label for="artisanName">Artisan Name <span class="req">*</span></label>
          <input type="text" id="artisanName" placeholder="e.g. Fatima Al-Ghamdi" />
        </div>
        <div class="form-group">
          <label for="artisanRegion">Region / City</label>
          <input type="text" id="artisanRegion" placeholder="e.g. Al-Ahsa, Eastern Province" />
        </div>
      </div>

       -->
      

      <!-- Actions -->
      <div class="form-actions">
        <button class="btn-submit" onclick="submitProduct()">&#x2795; Add Product</button>
        <a href="admin-dashboard.html" class="btn-cancel">Cancel</a>
      </div>

    </div>
  </div>
</div>

<footer class="footer">
  <div class="container">
    <div class="footer-bottom" style="text-align:center;padding:20px 0;">
      <p>&copy; 2026 Dar Al Hiraf &mdash; Admin Portal.</p>
    </div>
  </div>
</footer>

<script>
  function previewImage(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('uploadDefault').style.display = 'none';
        document.getElementById('uploadPreviewInner').style.display = 'block';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  // Drag-over highlight
  var area = document.getElementById('uploadArea');
  area.addEventListener('dragover', function() { area.classList.add('dragover'); });
  area.addEventListener('dragleave', function() { area.classList.remove('dragover'); });
  area.addEventListener('drop', function() { area.classList.remove('dragover'); });

  function submitProduct() {
    var name = document.getElementById('productName').value.trim();
    var desc = document.getElementById('productDesc').value.trim();
    var price = document.getElementById('productPrice').value;
    var cat = document.getElementById('productCategory').value;
    var artisan = document.getElementById('artisanName').value.trim();
    var img = document.getElementById('productImage').files.length;

    if (!name || !desc || !price || !cat || !artisan || !img) {
      alert('Please fill in all required fields and upload a product image.');
      return;
    }

    // Show success
    var banner = document.getElementById('successBanner');
    banner.classList.add('visible');
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Reset form
    document.getElementById('productName').value = '';
    document.getElementById('productNameAr').value = '';
    document.getElementById('productDesc').value = '';
    document.getElementById('productPrice').value = '';
    document.getElementById('productCategory').value = '';
    document.getElementById('artisanName').value = '';
    document.getElementById('artisanRegion').value = '';
    document.getElementById('imagePreview').style.display = 'none';
  }
</script>
<script src="cart.js"></script>
</body>
</html>
