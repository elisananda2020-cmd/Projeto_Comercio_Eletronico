<?php
$productId = (int) $product['id'];
$productName = htmlspecialchars($product['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$promotion = $product['promotional_price'];
$available = (bool) $product['is_available'] && (int) $product['stock_quantity'] > 0;
$badge = $product['is_featured'] ? 'Destaque' : ($product['badge'] === 'Destaque' ? null : $product['badge']);
?>
<article class="product-card-v2 reveal-item" data-product-id="<?= $productId ?>">
    <?php if ($badge !== null): ?>
        <div class="badge-tag destaque"><?= htmlspecialchars($badge, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if ($promotion !== null): ?>
        <div class="badge-tag discount">-<?= (int) round(100 * (1 - (float) $promotion / (float) $product['price'])) ?>%</div>
    <?php endif; ?>
    <img src="<?= htmlspecialchars($product['image_url'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['image_alt'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
    <div class="card-content-v2">
        <span class="category-chip"><?= htmlspecialchars($product['category'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
        <h3>
            <?php if (empty($isProductDetail)): ?>
                <a class="product-detail-link" href="produtos.php?id=<?= $productId ?>"><?= $productName ?></a>
            <?php else: ?>
                <?= $productName ?>
            <?php endif; ?>
        </h3>
        <p class="short-description"><?= htmlspecialchars($product['description'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
        <div class="price-actions">
            <div class="price-info">
                <?php if ($promotion !== null): ?>
                    <span class="old-price-v2">R$ <?= number_format((float) $product['price'], 2, ',', '.') ?></span>
                <?php endif; ?>
                <span class="current-price-v2">R$ <?= number_format((float) ($promotion ?? $product['price']), 2, ',', '.') ?></span>
                <span class="installment"><?= htmlspecialchars($product['installment_label'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                <?php if (!$available): ?>
                    <span class="installment">Indisponível</span>
                <?php endif; ?>
            </div>
            <button type="button" class="add-to-cart-btn-v2" aria-label="Adicionar ao carrinho" data-add-product="<?= $productId ?>" <?= $available ? '' : 'disabled' ?>>
                <i class="fas fa-cart-plus" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</article>
