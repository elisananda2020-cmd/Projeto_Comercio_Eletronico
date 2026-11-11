<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

const CART_MAX_QUANTITY = 9999;

function cart_quantities(): array
{
    return $_SESSION['cart'] ?? [];
}

function cart_snapshot(array $quantities, bool $lockProducts = false): array
{
    $items = [];
    $subtotal = 0;
    $products = [];

    if ($quantities !== []) {
        $placeholders = implode(', ', array_fill(0, count($quantities), '?'));
        $statement = database()->prepare(
            "SELECT id, name, category, price, promotional_price, stock_quantity, is_available
             FROM products WHERE id IN ($placeholders) ORDER BY id"
            . ($lockProducts ? ' FOR UPDATE' : '')
        );
        $statement->execute(array_keys($quantities));
        foreach ($statement->fetchAll() as $product) {
            $products[$product['id']] = $product;
        }
    }

    foreach ($quantities as $id => $quantity) {
        $product = $products[$id] ?? null;
        $stock = !empty($product['is_available']) ? (int) $product['stock_quantity'] : 0;
        $unitPrice = $product === null ? null : (int) str_replace('.', '', $product['promotional_price'] ?? $product['price']);
        $lineTotal = $unitPrice === null ? null : $unitPrice * $quantity;
        $subtotal += $lineTotal ?? 0;
        $items[] = [
            'id' => $id,
            'name' => $product['name'] ?? 'Produto removido do catálogo',
            'category' => $product['category'] ?? null,
            'quantity' => $quantity,
            'stock' => $stock,
            'unit_price_cents' => $unitPrice,
            'line_total_cents' => $lineTotal,
        ];
    }

    return ['items' => $items, 'subtotal_cents' => $subtotal, 'count' => array_sum($quantities)];
}

function change_cart(string $action, int $id, int $quantity): array
{
    $quantities = cart_quantities();
    $previous = $quantities[$id] ?? 0;
    $changedItem = null;
    if ($action === 'remove') {
        if ($previous > 0) {
            $changedItem = cart_snapshot([$id => $previous])['items'][0];
        }
        unset($quantities[$id]);
    } else {
        if ($action === 'update' && !isset($quantities[$id])) {
            throw new DomainException('Este produto não está no carrinho.', 404);
        }
        $quantities[$id] = $action === 'add' ? ($quantities[$id] ?? 0) + $quantity : $quantity;
        if (array_sum($quantities) > CART_MAX_QUANTITY) {
            throw new DomainException('O carrinho pode conter até 9.999 unidades.', 422);
        }
    }

    $cart = cart_snapshot($quantities);
    if ($action !== 'remove') {
        foreach ($cart['items'] as $item) {
            if ($item['id'] !== $id) {
                continue;
            }
            if ($item['stock'] === 0) {
                throw new DomainException('Este produto está indisponível.', 409);
            }
            if ($item['quantity'] > $item['stock']) {
                throw new DomainException('Quantidade solicitada acima do estoque disponível.', 409);
            }
            $changedItem = $item;
            break;
        }
    }

    $_SESSION['cart'] = $quantities;
    $difference = ($quantities[$id] ?? 0) - $previous;
    if ($changedItem !== null && $difference !== 0) {
        $changedItem['quantity'] = abs($difference);
        $changedItem['line_total_cents'] = $changedItem['unit_price_cents'] === null
            ? null
            : $changedItem['unit_price_cents'] * $changedItem['quantity'];
        $cart['change'] = [
            'action' => $difference > 0 ? 'add_to_cart' : 'remove_from_cart',
            'item' => $changedItem,
        ];
    }
    return $cart;
}

function cart_is_orderable(array $cart): bool
{
    if ($cart['items'] === []) {
        return false;
    }
    foreach ($cart['items'] as $item) {
        if ($item['quantity'] > $item['stock'] || $item['unit_price_cents'] === null) {
            return false;
        }
    }
    return true;
}
