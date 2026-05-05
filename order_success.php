<?php
require_once __DIR__ . '/includes/functions.php';
$orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$page_title = 'Order Confirmed';
require_once __DIR__ . '/includes/header.php';
?>
<section class="container confirmation-page">
    <div class="confirmation-card">
        <h1>Order Placed</h1>
        <p>Thank you! Your order has been placed successfully. The seller will review your delivery request and update the status shortly.</p>
        <?php if ($orderId): ?>
            <p class="order-number">Order ID: <strong><?= $orderId ?></strong></p>
        <?php endif; ?>
        <a class="btn btn-primary" href="<?= BASE_URL ?>/index.php">Continue Browsing</a>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php';
