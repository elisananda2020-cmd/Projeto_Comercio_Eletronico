(() => {
    const panel = document.getElementById('offCanvasCart');
    const openButton = document.getElementById('openCart');
    const closeButton = document.getElementById('closeCart');
    const overlay = document.getElementById('cartOverlay');
    const list = document.getElementById('cartItemsList');
    const subtotal = document.getElementById('cartSubtotal');
    const checkout = document.getElementById('cartCheckout');
    const status = document.getElementById('cartStatus');
    const counter = openButton.querySelector('.cart-count');
    const currency = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });
    let busy = false;
    let opener = openButton;
    let canCheckout = false;

    function element(tag, text, className) {
        const node = document.createElement(tag);
        node.textContent = text;
        if (className) node.className = className;
        return node;
    }

    function showPanel(trigger) {
        if (!panel.inert) return false;
        opener = trigger;
        panel.inert = false;
        panel.classList.add('is-open');
        overlay.classList.add('is-open');
        openButton.setAttribute('aria-expanded', 'true');
        closeButton.focus();
        return true;
    }

    function closePanel() {
        panel.inert = true;
        panel.classList.remove('is-open');
        overlay.classList.remove('is-open');
        openButton.setAttribute('aria-expanded', 'false');
        opener.focus();
    }

    function render(cart) {
        counter.textContent = cart.count;
        subtotal.textContent = currency.format(cart.subtotal_cents / 100);
        canCheckout = cart.items.length > 0 && cart.items.every(item => item.quantity <= item.stock);
        list.replaceChildren();

        if (cart.items.length === 0) {
            list.append(element('p', 'Seu carrinho está vazio.', 'empty-cart-message'));
            return;
        }

        for (const item of cart.items) {
            const row = element('div', '', 'cart-line');
            row.append(element('strong', item.name));
            if (item.unit_price_cents !== null) {
                const unit = currency.format(item.unit_price_cents / 100);
                const total = currency.format(item.line_total_cents / 100);
                row.append(element('p', unit + ' por unidade · Total: ' + total));
            }
            if (item.quantity > item.stock) {
                const warning = item.stock === 0 ? 'Produto indisponível.' : 'Estoque atual: ' + item.stock + '. Ajuste a quantidade.';
                row.append(element('p', warning, 'cart-stock-warning'));
            }

            const form = element('form', '', 'cart-line-controls');
            const label = element('label', 'Qtd. ');
            const quantity = element('input', '');
            quantity.type = 'number';
            quantity.min = '1';
            quantity.max = Math.min(9999, item.stock);
            quantity.step = '1';
            quantity.required = true;
            quantity.value = item.quantity;
            quantity.disabled = item.stock === 0;
            label.append(quantity);
            const update = element('button', 'Atualizar');
            update.type = 'submit';
            update.disabled = item.stock === 0;
            const remove = element('button', 'Remover');
            remove.type = 'button';
            form.append(label, update, remove);
            form.addEventListener('submit', event => {
                event.preventDefault();
                requestCart('update', item.id, quantity.value);
            });
            remove.addEventListener('click', () => requestCart('remove', item.id));
            row.append(form);
            list.append(row);
        }
    }

    async function requestCart(action, id, quantity, viewed = false) {
        if (busy) return;
        busy = true;
        checkout.disabled = true;
        canCheckout = false;
        if (list.contains(document.activeElement)) closeButton.focus();
        list.inert = true;
        status.textContent = action ? 'Atualizando carrinho…' : 'Carregando carrinho…';

        try {
            const options = action ? {
                method: 'POST',
                headers: { 'X-CSRF-Token': panel.dataset.csrf },
                body: new URLSearchParams({ action, id, quantity: quantity ?? '' }),
            } : {};
            const response = await fetch('api/cart.php', options);
            const cart = await response.json();
            if (!response.ok) {
                if (action) {
                    const current = await fetch('api/cart.php');
                    if (current.ok) {
                        const snapshot = await current.json();
                        render(snapshot);
                        if (viewed) window.storeAnalytics?.viewCart(snapshot);
                    }
                }
                status.textContent = cart.error || 'Não foi possível carregar o carrinho.';
                return;
            }
            render(cart);
            if (cart.change) window.storeAnalytics?.cartChange(cart.change);
            if (viewed) window.storeAnalytics?.viewCart(cart);
            status.textContent = action ? 'Carrinho atualizado.' : '';
        } catch {
            status.textContent = 'Não foi possível acessar o carrinho. Tente novamente.';
        } finally {
            busy = false;
            list.inert = false;
            checkout.disabled = !canCheckout;
        }
    }

    openButton.addEventListener('click', () => {
        const viewed = showPanel(openButton);
        requestCart(undefined, undefined, undefined, viewed);
    });
    closeButton.addEventListener('click', closePanel);
    overlay.addEventListener('click', closePanel);
    document.addEventListener('keydown', event => {
        if (panel.inert) return;
        if (event.key === 'Escape') closePanel();
        if (event.key !== 'Tab') return;

        const controls = Array.from(panel.querySelectorAll('button:not(:disabled), input:not(:disabled)'))
            .filter(control => !control.closest('[inert]'));
        const first = controls[0];
        const last = controls[controls.length - 1];
        const active = document.activeElement;
        if (!panel.contains(active) || (event.shiftKey ? active === first : active === last)) {
            event.preventDefault();
            (event.shiftKey ? last : first).focus();
        }
    });
    document.addEventListener('click', event => {
        const add = event.target.closest('[data-add-product]');
        if (!add || add.disabled) return;
        const viewed = showPanel(add);
        requestCart('add', add.dataset.addProduct, 1, viewed);
    });
})();
