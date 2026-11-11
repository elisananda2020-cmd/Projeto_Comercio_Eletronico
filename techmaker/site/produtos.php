<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../models/Product.php';

start_store_session();

$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$search = is_string($_GET['q'] ?? null) ? trim($_GET['q']) : '';
if (!preg_match('/\A.{0,200}\z/us', $search)) {
    $search = '';
}
$readPrice = static fn (mixed $value): ?string =>
    is_string($value) && preg_match('/\A\d{1,9}(?:\.\d{1,2})?\z/', $value) ? $value : null;
$min = $readPrice($_GET['min'] ?? null);
$max = $readPrice($_GET['max'] ?? null);
$inStock = ($_GET['estoque'] ?? null) === '1';
$order = $_GET['ordem'] ?? '';
$order = is_string($order) && isset(PRODUCT_ORDERS[$order]) ? $order : 'catalogo';
$isProductDetail = array_key_exists('id', $_GET);
$product = null;
$products = $categories = $selectedCategories = [];
$priceLimit = 50000;
$catalogError = false;

try {
    if ($isProductDetail) {
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 4294967295]]);
        $product = $id === false ? null : find_product($id);
        if ($product === null) {
            http_response_code(404);
        }
    } else {
        foreach (product_filter_options() as $option) {
            $categories[] = $option['category'];
            $priceLimit = max($priceLimit, (float) $option['max_price']);
        }
        if ($max !== null && (float) $max > $priceLimit) {
            $max = (string) $priceLimit;
        }
        $requested = $_GET['categoria'] ?? [];
        $requested = is_array($requested) ? $requested : [$requested];
        $selectedCategories = array_values(array_filter($categories, static fn ($category) => in_array($category, $requested, true)));
        $products = find_products($search, $selectedCategories, $min, $max, $inStock, $order);
    }
} catch (RuntimeException $exception) {
    error_log('Falha ao consultar catálogo. Código: ' . $exception->getCode());
    http_response_code(503);
    $catalogError = true;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product ? $escape($product['name']) : 'Nossos Produtos' ?> | TechMaker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css"> 
    <link rel="stylesheet" href="vitrine.css"> 
    <link rel="stylesheet" href="header.css"> 
    <link rel="stylesheet" href="footer.css"> 
    <?php $analyticsPage = $isProductDetail ? 'product' : 'catalog'; require __DIR__ . '/../includes/analytics.php'; ?>
</head>
<body class="product-page">
    <?php require __DIR__ . '/../includes/demo-notice.php'; ?>

    <?php require __DIR__ . '/../includes/cart-panel.php'; ?>

    <header class="main-header">
        <div class="container header-content">
            <a href="index.php" class="logo">TechMaker</a>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Página Principal</a></li>
                    <li class="dropdown-menu">
                        <a href="produtos.php" class="active">Produtos <i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-content">
                            <li><a href="produtos.php?categoria=Impressora%203D">Impressão 3D</a></li>
                            <li><a href="produtos.php?categoria%5B%5D=Arduino&amp;categoria%5B%5D=Arduino%20%26%20Eletr%C3%B4nica">Kits Arduino</a></li>
                            <li><a href="produtos.php?categoria=Cortadora%20Laser">Corte a Laser</a></li>
                            <li><a href="index.php#mais-vendidos">Mais Vendidos</a></li>
                        </ul>
                    </li>
                    <li><a href="sobre.php">Sobre Nós</a></li> 
                    <li><a href="index.php#diferenciais">Diferenciais</a></li>
                    <li><a href="index.php#maker">Blog</a></li>
                </ul>
            </nav>

            <div class="header-actions">
                <?php require __DIR__ . '/../includes/catalog-search.php'; ?>
                <a href="registro.php" class="action-icon" title="Cadastre-se ou Faça Login"><i class="fas fa-user"></i></a>
                
                <?php require __DIR__ . '/../includes/cart-trigger.php'; ?>
            </div>
        </div>
    </header>

    <main>
        <?php if ($isProductDetail): ?>
            <section class="product-listing-section">
                <div class="container">
                    <p><a href="produtos.php">Voltar ao catálogo</a></p>
                    <?php if ($catalogError): ?>
                        <p role="alert">Catálogo temporariamente indisponível.</p>
                    <?php elseif ($product === null): ?>
                        <h2>Produto não encontrado</h2>
                    <?php else: ?>
                        <div class="product-detail-card">
                            <?php require __DIR__ . '/../includes/product-card.php'; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        <?php else: ?>
            <form action="produtos.php" method="get">
                <section class="products-hero reveal-item">
                    <div class="container">
                        <div class="hero-content-light">
                            <h2>Nossos Produtos</h2>
                            <p class="subtitle">Encontre o equipamento perfeito para seus projetos</p>
                            <div class="search-bar-product-page">
                                <input type="search" name="q" placeholder="Buscar produtos..." aria-label="Buscar produtos por nome" maxlength="200" value="<?= $escape($search) ?>">
                                <button type="submit" class="search-button" aria-label="Buscar"><i class="fas fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="product-listing-section">
                    <div class="container product-grid-layout">
                        <aside class="filters-sidebar reveal-item">
                            <h3>Filtros</h3>
                            <div class="filter-group">
                                <h4>Categorias</h4>
                                <ul>
                                    <?php foreach ($categories as $category): ?>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="categoria[]" value="<?= $escape($category) ?>" <?= in_array($category, $selectedCategories, true) ? 'checked' : '' ?>>
                                                <?= $escape($category) ?>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="filter-group">
                                <h4>Faixa de Preço</h4>
                                <div class="price-range">
                                    <label for="minPrice">Preço mínimo (R$)</label>
                                    <input type="number" name="min" id="minPrice" min="0" step="0.01" value="<?= $escape($min ?? '') ?>" placeholder="0,00">
                                    <label for="priceRange">Preço máximo</label>
                                    <input type="range" name="max" id="priceRange" min="0" max="<?= $priceLimit ?>" step="0.01" value="<?= $escape($max ?? (string) $priceLimit) ?>">
                                    <div class="price-values">
                                        <span class="min-price">R$ 0</span>
                                        <output class="max-price" for="priceRange" id="priceRangeValue">R$ <?= number_format((float) ($max ?? $priceLimit), 2, ',', '.') ?></output>
                                    </div>
                                </div>
                            </div>

                            <div class="filter-group">
                                <label class="checkbox-group"><input type="checkbox" name="estoque" value="1" <?= $inStock ? 'checked' : '' ?>> Apenas em estoque</label>
                            </div>

                            <button type="submit" class="clear-filters-btn">Aplicar Filtros</button>
                            <a href="produtos.php" class="clear-filters-btn clear-filters-link">Limpar Filtros</a>
                        </aside>

                        <div class="product-list-container">
                            <div class="product-count">
                                <?php if (!$catalogError): ?>
                                    <?= count($products) ?> <?= count($products) === 1 ? 'produto encontrado' : 'produtos encontrados' ?>
                                <?php endif; ?>
                                <label for="productOrder">Ordenar por</label>
                                <select name="ordem" id="productOrder">
                                    <option value="catalogo" <?= $order === 'catalogo' ? 'selected' : '' ?>>Ordem do catálogo</option>
                                    <option value="menor-preco" <?= $order === 'menor-preco' ? 'selected' : '' ?>>Menor preço</option>
                                    <option value="maior-preco" <?= $order === 'maior-preco' ? 'selected' : '' ?>>Maior preço</option>
                                    <option value="nome" <?= $order === 'nome' ? 'selected' : '' ?>>Nome (A–Z)</option>
                                </select>
                                <button type="submit" class="catalog-sort-button">Ordenar</button>
                            </div>
                            <?php if ($catalogError): ?>
                                <p role="alert">Catálogo temporariamente indisponível.</p>
                            <?php elseif ($products === []): ?>
                                <p>Nenhum produto corresponde à busca e aos filtros selecionados.</p>
                            <?php else: ?>
                                <div class="product-grid-view">
                                    <?php foreach ($products as $product): ?>
                                        <?php require __DIR__ . '/../includes/product-card.php'; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </form>
        <?php endif; ?>
    </main>

    <footer class="main-footer">
        <div class="container footer-content">
            <div class="footer-column">
                <h3>Sobre a TechMaker</h3>
                <p>Líder em tecnologia de fabricação digital, oferecendo o melhor em Impressão 3D, Laser e Eletrônica para sua criatividade.</p>
                <div class="social-icons">
                    <a href="institucional.php#redes" aria-label="Facebook da demonstração"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                    <a href="institucional.php#redes" aria-label="Instagram da demonstração"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                    <a href="institucional.php#redes" aria-label="LinkedIn da demonstração"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="footer-column">
                <h3>Atendimento</h3>
                <ul>
                    <li><a href="institucional.php#contato">Fale Conosco</a></li>
                    <li><a href="institucional.php#ajuda">Central de Ajuda</a></li>
                    <li><a href="institucional.php#trocas">Política de Troca</a></li>
                    <li><a href="registro.php">Minha Conta</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Produtos</h3>
                <ul>
                    <li><a href="produtos.php?categoria=Impressora%203D">Impressoras</a></li>
                    <li><a href="produtos.php?categoria=Filamentos">Filamentos</a></li>
                    <li><a href="produtos.php?categoria%5B%5D=Arduino&amp;categoria%5B%5D=Arduino%20%26%20Eletr%C3%B4nica">Kits Eletrônicos</a></li>
                    <li><a href="produtos.php?categoria=Pe%C3%A7as%20e%20Componentes">Peças e Componentes</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <div class="container">
                &copy; 2025 TechMaker. Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <script>
        // --- SCROLL REVEAL ---
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.reveal-item').forEach(item => {
                observer.observe(item);
            });
        });
        

        const priceRange = document.getElementById('priceRange');
        if (priceRange) {
            const priceRangeValue = document.getElementById('priceRangeValue');
            const currency = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });
            priceRange.addEventListener('input', () => {
                priceRangeValue.value = currency.format(Number(priceRange.value));
            });
        }
        
    </script>
    <?php require __DIR__ . '/../includes/cookie-consent.php'; ?>
</body>
</html>
