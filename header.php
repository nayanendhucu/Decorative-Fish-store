<?php
if (!isset($page_title)) {
    $page_title = 'Kerala Decorative Fish Store';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($page_title) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="<?= BASE_URL ?>/index.php">Kerala Fish Farm</a>
        <nav class="main-nav">
            <a href="<?= BASE_URL ?>/index.php">Home</a>
            <a href="<?= BASE_URL ?>/index.php#gallery">Fish Shop</a>
            <a href="<?= BASE_URL ?>/checkout.php">Checkout</a>
            <?php if (is_customer_logged_in()): ?>
                <a href="<?= BASE_URL ?>/customer/orders.php">My Orders</a>
                <a href="<?= BASE_URL ?>/customer/logout.php">Logout</a>
            <?php elseif (is_admin_logged_in()): ?>
                <a href="<?= BASE_URL ?>/admin/dashboard.php">Admin</a>
                <a href="<?= BASE_URL ?>/admin/logout.php">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/customer/login.php">Customer Login</a>
                <a href="<?= BASE_URL ?>/customer/register.php">Register</a>
                <a href="<?= BASE_URL ?>/admin/login.php">Admin Login</a>
            <?php endif; ?>
        </nav>
        <div class="top-actions">
            <a class="whatsapp-btn" href="https://wa.me/917736407835?text=Hello%20Kerala%20Fish%20Farm%2C%20I%20would%20like%20to%20inquire%20about%20live%20fish%20stock." target="_blank">WhatsApp</a>
            <div class="cart-widget">
                <a href="<?= BASE_URL ?>/cart.php">Cart (<?= cart_count() ?>)</a>
            </div>
        </div>
    </div>
</header>
<main class="page-content">
