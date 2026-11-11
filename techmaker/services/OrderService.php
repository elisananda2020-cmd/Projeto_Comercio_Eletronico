<?php

declare(strict_types=1);

require_once __DIR__ . '/CartService.php';

function checkout_signature(array $cart): string
{
    $lines = [];
    foreach ($cart['items'] as $item) {
        $lines[$item['id']] = [$item['quantity'], $item['unit_price_cents']];
    }
    ksort($lines);
    return hash('sha256', json_encode($lines, JSON_THROW_ON_ERROR));
}

function order_decimal(int $cents): string
{
    return sprintf('%d.%02d', intdiv($cents, 100), $cents % 100);
}

function find_order_by_token(int $userId, string $token): ?string
{
    $statement = database()->prepare('SELECT id FROM orders WHERE checkout_token = ? AND user_id = ?');
    $statement->execute([$token, $userId]);
    $id = $statement->fetchColumn();
    return $id === false ? null : (string) $id;
}

function place_pending_order(int $userId, array $quantities, string $token, string $signature): string
{
    $db = database();
    $db->beginTransaction();

    try {
        $cart = cart_snapshot($quantities, true);
        if (!cart_is_orderable($cart)) {
            throw new DomainException('O estoque mudou. Ajuste o carrinho antes de confirmar.');
        }
        if (!hash_equals($signature, checkout_signature($cart))) {
            throw new DomainException('O carrinho ou os preços mudaram. Revise o resumo atualizado.');
        }

        $order = $db->prepare('INSERT INTO orders (user_id, total, checkout_token) VALUES (?, ?, ?)');
        $order->execute([$userId, order_decimal($cart['subtotal_cents']), $token]);
        $orderId = $db->lastInsertId();

        $insert = $db->prepare(
            'INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)'
        );
        $deduct = $db->prepare('UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?');
        foreach ($cart['items'] as $item) {
            $insert->execute([$orderId, $item['id'], $item['quantity'], order_decimal($item['unit_price_cents'])]);
            $deduct->execute([$item['quantity'], $item['id']]);
        }

        $db->commit();
        return $orderId;
    } catch (Throwable $exception) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        throw $exception;
    }
}

function find_user_order(int $userId, string $orderId): ?array
{
    $db = database();
    $statement = $db->prepare('SELECT id, total, status FROM orders WHERE id = CAST(? AS UNSIGNED) AND user_id = ?');
    $statement->execute([$orderId, $userId]);
    $order = $statement->fetch();
    if (!$order) {
        return null;
    }

    $items = $db->prepare(
        'SELECT p.name, i.quantity, i.unit_price FROM order_items i
         JOIN products p ON p.id = i.product_id WHERE i.order_id = CAST(? AS UNSIGNED) ORDER BY i.id'
    );
    $items->execute([$orderId]);
    $order['items'] = $items->fetchAll();
    return $order;
}
