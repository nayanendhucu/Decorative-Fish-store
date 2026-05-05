<?php
require_once __DIR__ . '/../includes/functions.php';
require_customer();

$orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$customerId = $_SESSION['customer_id'];

global $mysqli;
$stmt = $mysqli->prepare('SELECT * FROM orders WHERE id = ? AND customer_id = ? LIMIT 1');
$stmt->bind_param('ii', $orderId, $customerId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if (!$order) {
    header('Location: orders.php');
    exit;
}
$stmt = $mysqli->prepare('SELECT oi.*, f.name FROM order_items oi LEFT JOIN fishes f ON oi.fish_id = f.id WHERE oi.order_id = ?');
$stmt->bind_param('i', $orderId);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$page_title = 'Order #' . $orderId;
require_once __DIR__ . '/../includes/header.php';
?>
<section class="container auth-page order-detail-page">
    <h1>Order #<?= $orderId ?></h1>
    <div class="order-detail-grid">
        <div class="order-info">
            <p><strong>Status:</strong> <?= order_status_label($order['status']) ?></p>
            <p><strong>Delivery type:</strong> <?= ucfirst(sanitize($order['pickup_delivery'])) ?></p>
            <p><strong>Total:</strong> <?= format_price($order['total_price']) ?></p>
            <p><strong>Placed:</strong> <?= sanitize($order['created_at']) ?></p>
            <p><strong>Address:</strong><br><?= nl2br(sanitize($order['address'])) ?></p>
        </div>
        <div class="order-actions">
            <a class="btn btn-secondary" href="orders.php">Back to Orders</a>
        </div>
    </div>
    <section class="order-items">
        <h2>Order Items</h2>
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Fish</th>
                        <th>Pairs</th>
                        <th>Singles</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= sanitize($item['name'] ?: 'Fish item') ?></td>
                            <td><?= sanitize($item['quantity_pair']) ?></td>
                            <td><?= sanitize($item['quantity_single']) ?></td>
                            <td><?= format_price($item['subtotal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</section>
<?php require_once __DIR__ . '/../includes/footer.php';
