<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

const PRODUCT_PRICE = 'COALESCE(promotional_price, price)';
const PRODUCT_ORDERS = [
    'catalogo' => 'id ASC',
    'menor-preco' => 'effective_price ASC, id ASC',
    'maior-preco' => 'effective_price DESC, id ASC',
    'nome' => 'name ASC, id ASC',
];

function product_filter_options(): array
{
    $sql = 'SELECT category, CEIL(MAX(' . PRODUCT_PRICE . ')) AS max_price
            FROM products GROUP BY category ORDER BY category';
    return database()->query($sql)->fetchAll();
}

function find_products(string $search, array $categories, ?string $min, ?string $max, bool $inStock, string $order): array
{
    $conditions = [];
    $values = [];
    if ($search !== '') {
        $conditions[] = "name LIKE ? ESCAPE '!'";
        $values[] = '%' . strtr($search, ['!' => '!!', '%' => '!%', '_' => '!_']) . '%';
    }
    if ($categories !== []) {
        $conditions[] = 'category IN (' . implode(', ', array_fill(0, count($categories), '?')) . ')';
        array_push($values, ...$categories);
    }
    if ($min !== null) {
        $conditions[] = PRODUCT_PRICE . ' >= ?';
        $values[] = $min;
    }
    if ($max !== null) {
        $conditions[] = PRODUCT_PRICE . ' <= ?';
        $values[] = $max;
    }
    if ($inStock) {
        $conditions[] = 'is_available = TRUE AND stock_quantity > 0';
    }

    $sql = 'SELECT products.*, ' . PRODUCT_PRICE . ' AS effective_price FROM products';
    if ($conditions !== []) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }
    $sql .= ' ORDER BY ' . (PRODUCT_ORDERS[$order] ?? PRODUCT_ORDERS['catalogo']);
    $statement = database()->prepare($sql);
    $statement->execute($values);
    return $statement->fetchAll();
}

function find_product(int $id): ?array
{
    $statement = database()->prepare('SELECT * FROM products WHERE id = ?');
    $statement->execute([$id]);
    return $statement->fetch() ?: null;
}

function home_products(): array
{
    // Mantém a seleção e a ordem da vitrine original pelos IDs do catálogo inicial.
    return database()->query('SELECT * FROM products WHERE id IN (1, 4, 3, 6) ORDER BY FIELD(id, 1, 4, 3, 6)')->fetchAll();
}
