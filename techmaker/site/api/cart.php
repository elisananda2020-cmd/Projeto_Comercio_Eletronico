<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../services/CartService.php';

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

try {
    start_store_session();
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === 'GET') {
        $cart = cart_snapshot(cart_quantities());
    } elseif ($method === 'POST') {
        if (!valid_csrf_token($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) {
            throw new DomainException('Sua sessão mudou. Recarregue a página e tente novamente.', 403);
        }
        $action = $_POST['action'] ?? '';
        $integerOptions = ['options' => ['min_range' => 1, 'max_range' => 4294967295]];
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, $integerOptions);
        $quantity = $action === 'remove' ? 1 : filter_var($_POST['quantity'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => CART_MAX_QUANTITY],
        ]);
        if (!in_array($action, ['add', 'update', 'remove'], true) || $id === false || $quantity === false) {
            throw new DomainException('Informe um produto e uma quantidade inteira válida.', 422);
        }
        $cart = change_cart($action, $id, $quantity);
    } else {
        header('Allow: GET, POST');
        throw new DomainException('Método não permitido.', 405);
    }

    echo json_encode($cart, JSON_THROW_ON_ERROR);
} catch (DomainException $exception) {
    http_response_code($exception->getCode());
    echo json_encode(['error' => $exception->getMessage()]);
} catch (RuntimeException | JsonException $exception) {
    error_log('Falha na operação do carrinho. Código: ' . $exception->getCode());
    http_response_code(503);
    echo json_encode(['error' => 'Carrinho temporariamente indisponível. Tente novamente.']);
}
