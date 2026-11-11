<aside id="offCanvasCart" class="cart-sidebar" role="dialog" aria-modal="true" aria-labelledby="cartTitle" data-csrf="<?= csrf_token() ?>" inert>
    <div class="cart-header">
        <h3 id="cartTitle">Seu Carrinho</h3>
        <button type="button" class="close-btn" id="closeCart" aria-label="Fechar carrinho">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
    </div>
    <p id="cartStatus" class="cart-status" role="status"></p>
    <div class="cart-items" id="cartItemsList"></div>
    <div class="cart-summary">
        <div class="subtotal">
            <span>Subtotal:</span>
            <span id="cartSubtotal">R$ 0,00</span>
        </div>
        <form action="checkout.php" method="get">
            <button type="submit" id="cartCheckout" class="checkout-button" disabled>Finalizar Compra</button>
        </form>
    </div>
</aside>

<div id="cartOverlay" class="cart-overlay" aria-hidden="true"></div>
<script src="cart.js" defer></script>
