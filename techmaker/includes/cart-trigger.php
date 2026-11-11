<button type="button" class="action-icon cart-icon" id="openCart" aria-label="Abrir carrinho" aria-expanded="false" aria-controls="offCanvasCart">
    <i class="fas fa-shopping-cart" aria-hidden="true"></i>
    <span class="cart-count" aria-live="polite"><?= array_sum($_SESSION['cart'] ?? []) ?></span>
</button>
