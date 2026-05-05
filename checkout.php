<?php
require_once __DIR__ . '/includes/functions.php';
$cart = cart_items();
if (empty($cart)) {
    header('Location: index.php');
    exit;
}
$customer = get_current_customer();
$defaultName = $customer['name'] ?? '';
$defaultPhone = $customer['phone'] ?? '';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $delivery = in_array($_POST['delivery'] ?? '', ['pickup', 'delivery'], true) ? $_POST['delivery'] : 'pickup';

    if (!$name || !$phone || !$address) {
        $message = 'Please fill all required fields.';
    } else {
        global $mysqli;
        $total = cart_total();
        $customerId = is_customer_logged_in() ? $_SESSION['customer_id'] : null;
        $stmt = $mysqli->prepare('INSERT INTO orders (customer_id, customer_name, phone, address, pickup_delivery, status, total_price, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())');
        $status = 'pending';
        $stmt->bind_param('isssssd', $customerId, $name, $phone, $address, $delivery, $status, $total);
        $stmt->execute();
        $orderId = $stmt->insert_id;
        $stmt->close();

        foreach ($cart as $item) {
            $itemTotal = ($item['quantity_pair'] * $item['price_pair']) + ($item['quantity_single'] * $item['price_single']);
            $stmtItem = $mysqli->prepare('INSERT INTO order_items (order_id, fish_id, quantity_pair, quantity_single, price_pair, price_single, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmtItem->bind_param('iiiiddd', $orderId, $item['fish_id'], $item['quantity_pair'], $item['quantity_single'], $item['price_pair'], $item['price_single'], $itemTotal);
            $stmtItem->execute();
            $stmtItem->close();

            $stmtStock = $mysqli->prepare('UPDATE fishes SET stock = GREATEST(stock - ?, 0), available = IF(stock - ? <= 0, 0, available) WHERE id = ?');
            $reduce = $item['quantity_pair'] + $item['quantity_single'];
            $stmtStock->bind_param('iii', $reduce, $reduce, $item['fish_id']);
            $stmtStock->execute();
            $stmtStock->close();
        }

        $_SESSION['cart'] = [];
        header('Location: order_success.php?id=' . $orderId);
        exit;
    }
}
$page_title = 'Checkout';
require_once __DIR__ . '/includes/header.php';
?>
<section class="container checkout-page">
    <h1>Checkout</h1>
    <div class="checkout-grid">
        <div class="checkout-form">
            <?php if ($message): ?><div class="alert alert-warning"><?= sanitize($message) ?></div><?php endif; ?>
            <form method="post">
                <label>Name</label>
                <input type="text" name="name" value="<?= sanitize($_POST['name'] ?? $defaultName) ?>" required>
                <label>Phone</label>
                <input type="tel" name="phone" value="<?= sanitize($_POST['phone'] ?? $defaultPhone) ?>" required>
                <label>Address</label>
                <textarea name="address" rows="4" required><?= sanitize($_POST['address'] ?? '') ?></textarea>
                <label>Pickup or Delivery</label>
                <div class="radio-row">
                    <label><input type="radio" name="delivery" value="pickup" <?= (!isset($_POST['delivery']) || $_POST['delivery'] === 'pickup') ? 'checked' : '' ?>> Pickup</label>
                    <label><input type="radio" name="delivery" value="delivery" <?= (isset($_POST['delivery']) && $_POST['delivery'] === 'delivery') ? 'checked' : '' ?>> Delivery Request</label>
                </div>
                <button type="submit" class="btn btn-primary">Place Order</button>
            </form>
        </div>
        <div class="order-summary">
            <h2>Order Summary</h2>
            <ul>
                <?php foreach ($cart as $item): ?>
                    <li>
                        <strong><?= sanitize($item['name']) ?></strong><br>
                        Pairs: <?= $item['quantity_pair'] ?>, Singles: <?= $item['quantity_single'] ?><br>
                        Price: <?= format_price($item['price_pair']) ?> / <?= format_price($item['price_single']) ?><br>
                        Subtotal: <?= format_price(($item['quantity_pair'] * $item['price_pair']) + ($item['quantity_single'] * $item['price_single'])) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="summary-total">
                <span>Total</span>
                <strong><?= format_price(cart_total()) ?></strong>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php';
