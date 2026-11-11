(() => {
    const config = JSON.parse(document.getElementById('storeAnalyticsConfig').textContent);
    const banner = document.getElementById('analyticsConsent');
    const preferences = document.getElementById('analyticsPreferences');
    const accept = document.getElementById('acceptAnalytics');
    const reject = document.getElementById('rejectAnalytics');
    const containerId = config.containerId || '';
    const id = config.measurementId;
    const enabled = Boolean(containerId || id);

    const routes = {
        home: ['index.php', 'Página inicial'],
        catalog: ['produtos.php', 'Catálogo'],
        product: ['produtos.php', 'Detalhe do produto'],
        account: ['registro.php', 'Conta'],
        about: ['sobre.php', 'Sobre'],
        checkout: ['checkout.php', 'Finalização'],
        confirmation: ['checkout.php', 'Confirmação de pedido'],
        privacy: ['privacidade.php', 'Privacidade'],
        institutional: ['institucional.php', 'Atendimento e termos'],
    };
    const [path, title] = routes[config.page];
    const base = document.currentScript.src;
    const pageLocation = new URL(path, base).href;
    const storageKey = 'techmaker.analytics.consent.v1';
    const referrerKey = 'techmaker.analytics.previousPage';
    const disableKey = 'ga-disable-' + id;
    const denied = {
        analytics_storage: 'denied',
        ad_storage: 'denied',
        ad_user_data: 'denied',
        ad_personalization: 'denied',
    };
    let choice;
    let loaded = false;
    try {
        choice = localStorage.getItem(storageKey);
    } catch {
        choice = null;
    }

    const dataLayer = enabled ? (window.dataLayer ||= []) : [];
    function gtag() { dataLayer.push(arguments); }
    if (id) window[disableKey] = true;
    gtag('consent', 'default', denied);

    function event(name, parameters) {
        if (choice === 'granted' && loaded && (containerId || !window[disableKey])) {
            if (containerId) {
                dataLayer.push({ ecommerce: null });
                const { items, currency, value, ...details } = parameters;
                const payload = { event: name, ...details };
                if (items) {
                    payload.ecommerce = { items };
                    if (currency !== undefined) payload.ecommerce.currency = currency;
                    if (value !== undefined) payload.ecommerce.value = value;
                }
                dataLayer.push(payload);
            } else {
                gtag('event', name, parameters);
            }
        }
    }

    function ecommerceItem(line) {
        const item = {
            item_id: String(line.id),
            item_name: line.name,
            quantity: line.quantity,
        };
        if (line.category !== null) item.item_category = line.category;
        if (line.unit_price_cents !== null) item.price = line.unit_price_cents / 100;
        return item;
    }

    function cartEvent(name, cart) {
        event(name, {
            currency: 'BRL',
            value: cart.subtotal_cents / 100,
            items: cart.items.slice(0, 200).map(ecommerceItem),
        });
    }

    function startAnalytics() {
        if (!enabled) return;
        if (id) window[disableKey] = false;
        gtag('consent', 'update', { ...denied, analytics_storage: 'granted' });
        if (loaded) return;
        loaded = true;
        if (!containerId) gtag('js', new Date());

        let referrer = '';
        try {
            const previous = sessionStorage.getItem(referrerKey);
            const knownPages = Object.values(routes).map(([file]) => new URL(file, base).href);
            if (knownPages.includes(previous)) referrer = previous;
            sessionStorage.setItem(referrerKey, pageLocation);
        } catch {
            // A navegação continua com localização canônica mesmo sem armazenamento.
        }
        gtag('set', { page_location: pageLocation, page_title: title, page_referrer: referrer });
        if (containerId) {
            dataLayer.push({
                'gtm.start': Date.now(),
                event: 'gtm.js',
                page_location: pageLocation,
                page_title: title,
                page_referrer: referrer,
            });
        } else {
            gtag('config', id, {
                send_page_view: false,
                allow_google_signals: false,
                allow_ad_personalization_signals: false,
                cookie_domain: 'none',
                cookie_path: '/',
            });
        }
        event('page_view', { page_location: pageLocation, page_title: title, page_referrer: referrer });
        if (config.searched) {
            // O texto digitado pode conter dados pessoais e fica somente na loja.
            event('search', { search_term: 'busca_catalogo', results_count: config.list.items.length });
        }
        if (config.list?.items.length) {
            event('view_item_list', {
                item_list_id: config.list.id,
                item_list_name: config.list.name,
                items: config.list.items.slice(0, 200).map((item, index) => ({ ...item, index })),
            });
        }
        if (config.list?.id === 'categorias') {
            event('view_category', { category_name: config.list.name });
        }
        if (config.item) {
            event('view_item', { currency: 'BRL', value: config.item.price, items: [config.item] });
        }
        if (config.checkout) cartEvent('begin_checkout', config.checkout);

        const tag = document.createElement('script');
        tag.async = true;
        tag.referrerPolicy = 'no-referrer';
        tag.src = containerId
            ? 'https://www.googletagmanager.com/gtm.js?id=' + encodeURIComponent(containerId)
            : 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(id);
        document.head.append(tag);
    }

    function hideBanner() {
        banner.hidden = true;
        preferences.setAttribute('aria-expanded', 'false');
    }

    function showBanner() {
        banner.hidden = false;
        preferences.setAttribute('aria-expanded', 'true');
    }

    function applyChoice(value) {
        choice = value;
        hideBanner();
        if (value === 'granted') {
            startAnalytics();
            return;
        }

        if (id) window[disableKey] = true;
        gtag('consent', 'update', denied);
        try { sessionStorage.removeItem(referrerKey); } catch {}
        for (const cookie of document.cookie.split(';')) {
            const name = cookie.split('=')[0].trim();
            if (/^_ga(?:_[A-Za-z0-9_]+)?$/.test(name)) {
                document.cookie = name + '=; Max-Age=0; Path=/; SameSite=Lax';
            }
        }
    }

    function saveChoice(value) {
        try { localStorage.setItem(storageKey, value); } catch {}
        applyChoice(value);
        preferences.focus();
    }

    preferences.hidden = false;
    preferences.addEventListener('click', () => {
        if (banner.hidden) {
            showBanner();
            accept.focus();
        } else {
            hideBanner();
        }
    });
    accept.addEventListener('click', () => saveChoice('granted'));
    reject.addEventListener('click', () => saveChoice('denied'));
    window.addEventListener('storage', event => {
        if (event.key === storageKey) {
            applyChoice(event.newValue);
            if (event.newValue === null) showBanner();
        }
    });
    window.addEventListener('pageshow', event => {
        if (!event.persisted) return;
        try {
            const saved = localStorage.getItem(storageKey);
            if (saved !== choice) {
                applyChoice(saved);
                if (saved === null) showBanner();
            }
        } catch {
            // A escolha em memória continua disponível quando o navegador bloqueia o armazenamento.
        }
    });

    window.storeAnalytics = {
        viewCart: cart => cartEvent('view_cart', cart),
        cartChange(change) {
            const { action, item } = change;
            if (action !== 'add_to_cart' && action !== 'remove_from_cart') return;
            const parameters = { currency: 'BRL', items: [ecommerceItem(item)] };
            if (item.unit_price_cents !== null) parameters.value = item.unit_price_cents * item.quantity / 100;
            event(action, parameters);
        },
    };

    if (config.list) {
        document.addEventListener('click', click => {
            const link = click.target.closest('.product-detail-link');
            if (!link) return;
            const id = link.closest('[data-product-id]').dataset.productId;
            const index = config.list.items.findIndex(item => item.item_id === id);
            if (index < 0) return;
            event('select_item', {
                item_list_id: config.list.id,
                item_list_name: config.list.name,
                items: [{ ...config.list.items[index], index }],
            });
        });
    }

    if (choice === 'granted' || choice === 'denied') {
        applyChoice(choice);
    } else {
        showBanner();
    }
})();
