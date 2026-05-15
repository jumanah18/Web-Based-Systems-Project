<?php
// Start the session
session_start();

// Empty the cart completely
$_SESSION['cart'] = [];

// Send back success message
echo json_encode(array('success' => true));
?>