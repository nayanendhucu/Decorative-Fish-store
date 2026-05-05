<?php
require_once __DIR__ . '/includes/functions.php';

$fishId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$fish = get_fish_by_id($fishId);
if (!$fish || !$fish['available'] || $fish['stock'] <= 0) {
    header('Location: index.php');
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity_pair = max(0, (int) ($_POST['quantity_pair'] ?? 0));
    $quantity_single = max(0, (int) ($_POST['quantity_single'] ?? 0));
    if ($quantity_pair === 0 && $quantity_single === 0) {
        $message = 'Please select quantity for pair or single.';
    } elseif ($quantity_pair + $quantity_single > $fish['stock']) {
        $message = 'Please choose quantity within available stock.';
    } else {
        $_SESSION['cart'][$fish['id']] = [
            'fish_id' => $fish['id'],
            'name' => $fish['name'],
            'price_pair' => $fish['price_pair'],
            'price_single' => $fish['price_single'],
            'quantity_pair' => $quantity_pair,
            'quantity_single' => $quantity_single,
            'stock' => $fish['stock'],
            'image' => parse_image_list($fish['images'])[0] ?? '',
        ];
        header('Location: cart.php');
        exit;
    }
}

$page_title = sanitize($fish['name']) . ' - Fish Details';
require_once __DIR__ . '/includes/header.php';
$images = parse_image_list($fish['images']);
$video = $fish['video'] ?: null;
?>
<section class="container fish-detail">
    <div class="detail-grid">
        <div class="media-column">
            <?php if ($video): ?>
                <video controls src="<?= BASE_URL ?>/uploads/videos/<?= sanitize($video) ?>" class="detail-media"></video>
            <?php elseif (!empty($images)): ?>
                <img src="<?= BASE_URL ?>/uploads/images/<?= sanitize($images[0]) ?>" alt="<?= sanitize($fish['name']) ?>" class="detail-media">
            <?php else: ?>
                <div class="detail-media placeholder">No Image Available</div>
            <?php endif; ?>
            <?php if (count($images) > 1): ?>
                <div class="thumbnail-row">
                    <?php foreach ($images as $image): ?>
                        <img src="<?= BASE_URL ?>/uploads/images/<?= sanitize($image) ?>" alt="<?= sanitize($fish['name']) ?> preview">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="detail-meta">
            <span class="tag">Available Now</span>
            <h1><?= sanitize($fish['name']) ?></h1>
            <p class="price">Pair: <?= format_price($fish['price_pair']) ?><?php if ($fish['price_single'] > 0): ?> / Single: <?= format_price($fish['price_single']) ?><?php endif; ?></p>
            <p class="stock-status">Stock: <?= sanitize($fish['stock']) ?> <?= $fish['stock'] < 5 ? '(Limited stock)' : '' ?></p>
            <p class="description"><?= nl2br(sanitize($fish['description'])) ?></p>
            <?php if ($message): ?><div class="alert alert-warning"><?= sanitize($message) ?></div><?php endif; ?>
            <form method="post" class="order-form">
                <label>Pairs</label>
                <input type="number" name="quantity_pair" min="0" max="<?= $fish['stock'] ?>" value="1">
                <?php if ($fish['price_single'] > 0): ?>
                    <label>Singles</label>
                    <input type="number" name="quantity_single" min="0" max="<?= $fish['stock'] ?>" value="0">
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">Add to Cart</button>
            </form>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php';
