<?php
require_once __DIR__ . '/includes/functions.php';

if (isset($_GET['remove'])) {
    $removeId = (int) $_GET['remove'];
    unset($_SESSION['cart'][$removeId]);
    header('Location: cart.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['quantity_pair'] as $fishId => $qtyPair) {
        $qtyPair = max(0, (int) $qtyPair);
        $qtySingle = max(0, (int) ($_POST['quantity_single'][$fishId] ?? 0));
        if (isset($_SESSION['cart'][$fishId])) {
            $_SESSION['cart'][$fishId]['quantity_pair'] = $qtyPair;
            $_SESSION['cart'][$fishId]['quantity_single'] = $qtySingle;
        }
    }
    header('Location: cart.php');
    exit;
}

$items = cart_items();
$page_title = 'Your Cart';
require_once __DIR__ . '/includes/header.php';
?>
<section class="container cart-page">
    <h1>Your Cart</h1>
    <?php if (empty($items)): ?>
        <div class="empty-state">Your cart is empty. Browse live stock and add fishes to book your order.</div>
    <?php else: ?>
        <form method="post" class="cart-table">
            <table>
                <thead>
                    <tr>
                        <th>Fish</th>
                        <th>Pair Qty</th>
                        <th>Single Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item):
                        $subtotal = ($item['quantity_pair'] * $item['price_pair']) + ($item['quantity_single'] * $item['price_single']); ?>
                        <tr>
                            <td>
                                <strong><?= sanitize($item['name']) ?></strong>
                                <div class="small">Pair: <?= format_price($item['price_pair']) ?>, Single: <?= format_price($item['price_single']) ?></div>
                            </td>
                            <td><input type="number" name="quantity_pair[<?= $item['fish_id'] ?>]" min="0" max="<?= $item['stock'] ?>" value="<?= $item['quantity_pair'] ?>"></td>
                            <td><input type="number" name="quantity_single[<?= $item['fish_id'] ?>]" min="0" max="<?= $item['stock'] ?>" value="<?= $item['quantity_single'] ?>"></td>
                            <td><?= format_price($item['price_pair']) ?> / <?= format_price($item['price_single']) ?></td>
                            <td><?= format_price($subtotal) ?></td>
                            <td><a class="trash-link" href="cart.php?remove=<?= $item['fish_id'] ?>">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="cart-actions">
                <button type="submit" class="btn btn-secondary">Update Cart</button>
                <a class="btn btn-primary" href="checkout.php">Proceed to Checkout</a>
            </div>
        </form>
        <div class="cart-summary">
            <h3>Order Total</h3>
            <p><?= format_price(cart_total()) ?></p>
        </div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/includes/footer.php';
