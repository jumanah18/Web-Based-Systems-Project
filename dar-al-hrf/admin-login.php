<?php
session_start();
require 'db.php';

// If already logged in redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: admin-dashboard.php');
    exit;
}

$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Email regex validation
    if (!preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        $error = 'Please enter a valid email address.';
    }
    // Password regex validation
    else if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{6,}$/', $password)) {
        $error = 'Password must be at least 6 characters and contain a letter and number.';
    }
    else {
        // Check email in database
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if (!$admin) {
            $error = 'Email not found.';
        } else if ($admin['password'] !== $password) {
            $error = 'Wrong password.';
        } else {
            // Login successful
            $_SESSION['admin_id']    = $admin['admin_id'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_name']  = $admin['name'];
            header('Location: admin-dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dar Al Hiraf — Admin Login</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .admin-login-page {
      min-height: calc(100vh - 68px);
      background: var(--cream);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 24px;
    }
    .admin-login-card {
      background: var(--white);
      border-radius: 12px;
      box-shadow: 0 8px 40px rgba(36,60,33,0.13);
      padding: 52px 48px 44px;
      width: 100%;
      max-width: 420px;
      border-top: 4px solid var(--gold);
    }
    .admin-login-logo {
      text-align: center;
      margin-bottom: 30px;
    }
    .admin-login-logo .logo-ar {
      font-family: 'Noto Naskh Arabic', serif;
      font-size: 28px;
      color: var(--green);
      display: block;
      line-height: 1;
    }
    .admin-login-logo .logo-en {
      font-family: 'Lato', sans-serif;
      font-size: 11px;
      color: var(--gold);
      letter-spacing: 0.18em;
      text-transform: uppercase;
      font-weight: 700;
      display: block;
      margin-top: 4px;
    }
    .admin-login-logo .admin-badge {
      display: inline-block;
      margin-top: 12px;
      background: var(--green);
      color: var(--gold);
      font-size: 10px;
      font-weight: 700;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      padding: 5px 14px;
      border-radius: 20px;
    }
    .admin-login-title {
      font-size: 20px;
      font-weight: 700;
      color: var(--green);
      text-align: center;
      margin-bottom: 28px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.14em;
      text-transform: uppercase;
      color: var(--green);
      margin-bottom: 8px;
    }
    .form-group input {
      width: 100%;
      padding: 12px 16px;
      border: 1.5px solid #ddd;
      border-radius: 6px;
      font-size: 14px;
      font-family: 'Lato', sans-serif;
      color: var(--text);
      background: var(--white);
      transition: border-color 0.2s;
      outline: none;
    }
    .form-group input:focus {
      border-color: var(--gold);
    }
    .login-error {
      background: #fdecea;
      color: #901C24;
      border: 1px solid #f5c6cb;
      border-radius: 6px;
      padding: 10px 14px;
      font-size: 13px;
      margin-bottom: 18px;
      display: none;
    }
    .login-error.visible { display: block; }
    .login-btn {
      width: 100%;
      padding: 14px;
      background: var(--green);
      color: var(--white);
      border: none;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      cursor: pointer;
      transition: background 0.2s;
      margin-top: 8px;
    }
    .login-btn:hover { background: #1c3019; }
    .login-divider {
      text-align: center;
      margin-top: 24px;
      font-size: 12px;
      color: #999;
    }
    .login-divider a {
      color: var(--gold);
      font-weight: 700;
      text-decoration: none;
    }
    .login-divider a:hover { text-decoration: underline; }

    .form-group input.invalid {
  border-color: #dc3545;
  background-color: #fff5f5;
}
.form-group input.invalid:focus {
  border-color: #bd2130;
  box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15);
}
  </style>
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
      <a href="workshops.php">Workshops</a>
      <a href="about.html">About</a>
    </div>
        <div class="navbar-right">
      <a id="adminNavLink" href="admin-login.php" class="navbar-admin-link">Admin</a>
      <a href="checkout.php" class="navbar-cart-btn" id="cartBtn" aria-label="View cart">
        <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" stroke="#1a1a1a" fill="none" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        <span class="cart-badge" id="cartBadge"></span>
      </a>
    </div>
  </div>
</nav>

<div class="admin-login-page">
  <div class="admin-login-card">
    <div class="admin-login-logo">
      <span class="logo-ar" lang="ar">دار الحرف</span>
      <span class="logo-en">Dar Al Hiraf</span>
      </div>
    <p class="admin-login-title">Sign In to Admin</p>

    <div class="login-error <?php if($error) echo 'visible'; ?>" id="loginError">
      <?php echo $error ? htmlspecialchars($error) : 'Invalid email or password. Please try again.'; ?>
    </div>

    <form id="loginForm" action="admin-login.php" method="POST">
      <div class="form-group">
        <label for="adminEmail">Email Address</label>
        <input type="email" id="adminEmail" name="email" placeholder="admin@daralhiraf.sa" autocomplete="email" />
      </div>
      <div class="form-group">
        <label for="adminPassword">Password</label>
        <input type="password" id="adminPassword" name="password" placeholder="Enter your password" autocomplete="current-password" />
      </div>
      <button type="button" class="login-btn" onclick="handleLogin()">Sign In</button>
    </form>

    <p class="login-divider">
      &larr; <a href="index.php">Back to main site</a>
    </p>
  </div>
</div>

<script>
  // Get DOM elements
  var emailInput = document.getElementById('adminEmail');
  var passwordInput = document.getElementById('adminPassword');
  var err        = document.getElementById('loginError');

  // Strict regex matching your PHP backend rules
  var emailRegex    = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{6,}$/;

  // Function to evaluate fields in real-time as the user types
  function checkRealtimeValidation() {
    var email = emailInput.value.trim();
    var pw    = passwordInput.value;

    // Only flag as wrong if the user has actually typed something 
    var isEmailWrong    = email !== '' && !emailRegex.test(email);
    var isPasswordWrong = pw !== '' && !passwordRegex.test(pw);

    // Toggle red covering on Email textbox
    if (isEmailWrong) {
      emailInput.classList.add('invalid');
    } else {
      emailInput.classList.remove('invalid');
    }

    // Toggle red covering on Password textbox
    if (isPasswordWrong) {
      passwordInput.classList.add('invalid');
    } else {
      passwordInput.classList.remove('invalid');
    }

    // Live update the error message text box
    if (isEmailWrong) {
      err.textContent = 'Please enter a valid email address.';
      err.classList.add('visible');
    } else if (isPasswordWrong) {
      err.textContent = 'Password must be at least 6 characters and contain a letter and number.';
      err.classList.add('visible');
    } else {
      // Clear error text if both formats are currently valid (or empty)
      err.classList.remove('visible');
    }
  }

  // Handle final submission when clicking "Sign In"
  function handleLogin() {
    var email = emailInput.value.trim();
    var pw    = passwordInput.value;

    // Final hard-check for empty or broken email
    if (!email || !emailRegex.test(email)) {
      emailInput.classList.add('invalid');
      err.textContent = !email ? 'Email is required.' : 'Please enter a valid email address.';
      err.classList.add('visible');
      emailInput.focus();
      return;
    }

    // Final hard-check for empty or broken password
    if (!pw || !passwordRegex.test(pw)) {
      passwordInput.classList.add('invalid');
      err.textContent = !pw ? 'Password is required.' : 'Password must be at least 6 characters and contain a letter and number.';
      err.classList.add('visible');
      passwordInput.focus();
      return;
    }

    // Clear classes and submit safely if everything passes
    err.classList.remove('visible');
    document.getElementById('loginForm').submit();
  }

  // Attach real-time listeners for typing ('input' triggers on every keystroke)
  emailInput.addEventListener('input', checkRealtimeValidation);
  passwordInput.addEventListener('input', checkRealtimeValidation);

  // Allow pressing "Enter" inside input fields to trigger handleLogin safely
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      handleLogin();
    }
  });
</script>
<script src="cart.js"></script>
</body>
</html>