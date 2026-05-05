<?php
require_once __DIR__ . '/includes/functions.php';
$fishes = get_available_fishes();
$page_title = 'Home - Live Fish Inventory';
require_once __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="container hero-inner">
        <div>
            <span class="eyebrow">Kerala Decorative Fish</span>
            <h1>Fresh live inventory from our home farm</h1>
            <p>Browse only the fishes we currently have in stock. Order today and we will confirm your delivery or pickup requests.</p>
            <a class="btn btn-primary" href="#gallery">View Available Fish</a>
        </div>
    </div>
</section>

<section id="gallery" class="fish-gallery container">
    <div class="section-heading">
        <h2>Current Stock</h2>
        <p>Only available fishes are shown here. Remove or mark out of stock from the admin panel.</p>
    </div>
    <?php if (empty($fishes)): ?>
        <div class="empty-state">No fishes are currently available. Please check back soon.</div>
    <?php else: ?>
        <div class="grid grid-3">
            <?php foreach ($fishes as $fish):
                $images = parse_image_list($fish['images']);
                $thumbnail = $images[0] ?? ''; ?>
                <article class="fish-card">
                    <a href="<?= BASE_URL ?>/fish.php?id=<?= $fish['id'] ?>">
                        <?php if ($thumbnail): ?>
                            <img src="<?= BASE_URL ?>/uploads/images/<?= sanitize($thumbnail) ?>" alt="<?= sanitize($fish['name']) ?>">
                        <?php else: ?>
                            <div class="image-placeholder">No image</div>
                        <?php endif; ?>
                    </a>
                    <div class="fish-details">
                        <h3><?= sanitize($fish['name']) ?></h3>
                        <p class="price">Pair: <?= format_price($fish['price_pair']) ?><?php if ($fish['price_single'] > 0): ?> / Single: <?= format_price($fish['price_single']) ?><?php endif; ?></p>
                        <p class="stock <?= $fish['stock'] < 5 ? 'limited' : '' ?>">Stock: <?= sanitize($fish['stock']) ?> <?= $fish['stock'] < 5 ? '(Limited)' : '' ?></p>
                        <a class="btn btn-secondary" href="<?= BASE_URL ?>/fish.php?id=<?= $fish['id'] ?>">View Details</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="info-section container">
    <div class="info-grid">
        <div>
            <h3>Live inventory, updated daily</h3>
            <p>Our catalogue reflects only the fishes currently available at our Kerala farm. If a fish sells out, it disappears from the customer view.</p>
        </div>
        <div>
            <h3>Flexible order and delivery</h3>
            <p>Choose pickup or delivery. Delivery is reviewed by the seller before approval, so you get accurate coordination for your order.</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php';
