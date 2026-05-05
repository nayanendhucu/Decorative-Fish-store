<?php
require_once __DIR__ . '/../includes/functions.php';
require_customer();
$customer = get_current_customer();

global $mysqli;
$stmt = $mysqli->prepare('SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC');
$stmt->bind_param('i', $_SESSION['customer_id']);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$page_title = 'My Orders';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="container auth-page">
    <h1>My Orders</h1>
    <p>Welcome, <?= sanitize($customer['name']) ?>. Here are your recent bookings.</p>
    <div class="order-list">
        <?php if (empty($orders)): ?>
            <div class="empty-state">You have no orders yet. Add fishes to the cart and checkout to book stock.</div>
        <?php else: ?>
            <div class="order-cards">
                <?php foreach ($orders as $order): ?>
                    <article class="order-card">
                        <h2>Order #<?= $order['id'] ?></h2>
                        <p><strong>Status:</strong> <?= order_status_label($order['status']) ?></p>
                        <p><strong>Delivery:</strong> <?= ucfirst(sanitize($order['pickup_delivery'])) ?></p>
                        <p><strong>Total:</strong> <?= format_price($order['total_price']) ?></p>
                        <p><strong>Date:</strong> <?= sanitize($order['created_at']) ?></p>
                        <a class="btn btn-secondary" href="<?= BASE_URL ?>/customer/order_view.php?id=<?= $order['id'] ?>">View Details</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php';
