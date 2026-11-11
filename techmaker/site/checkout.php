<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../services/OrderService.php';

start_store_session();
header('Cache-Control: no-store');

if (current_user() === null && $_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['pedido'])) {
    $_SESSION['resume_checkout'] = true;
}
$user = require_user();
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$money = static fn (int $cents): string => 'R$ ' . number_format($cents / 100, 2, ',', '.');
$isConfirmation = array_key_exists('pedido', $_GET);
$order = null;
$cart = null;
$token = null;
$error = '';

function show_order_confirmation(string $id): never
{
    header('Location: checkout.php?pedido=' . $id, true, 303);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $submitted = $_POST['checkout_token'] ?? null;
        $quote = $_SESSION['checkout_quote'] ?? null;
        if ($isConfirmation || !valid_csrf_token($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            $error = 'O formulário expirou. Abra o resumo e tente novamente.';
        } elseif (!is_string($submitted) || !preg_match('/\A[a-f0-9]{64}\z/', $submitted)) {
            http_response_code(409);
            $error = 'Revise o resumo antes de confirmar.';
        } elseif (($existing = find_order_by_token($user['id'], $submitted)) !== null) {
            if ($quote !== null && hash_equals($quote['token'], $submitted) && $quote['quantities'] === cart_quantities()) {
                unset($_SESSION['cart'], $_SESSION['checkout_quote']);
            }
            show_order_confirmation($existing);
        } elseif ($quote === null || !hash_equals($quote['token'], $submitted)) {
            http_response_code(409);
            $error = 'O resumo mudou. Confira os valores atuais antes de confirmar.';
        } else {
            try {
                $id = place_pending_order($user['id'], cart_quantities(), $submitted, $quote['signature']);
                unset($_SESSION['cart'], $_SESSION['checkout_quote']);
                show_order_confirmation($id);
            } catch (DomainException $exception) {
                http_response_code(409);
                $error = $exception->getMessage();
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header('Allow: GET, POST');
        http_response_code(405);
        $error = 'Método não permitido.';
    }

    if ($isConfirmation && $error === '') {
        $id = $_GET['pedido'];
        if (is_string($id) && preg_match('/\A[1-9][0-9]{0,19}\z/', $id)
            && (strlen($id) < 20 || strcmp($id, '18446744073709551615') <= 0)) {
            $order = find_user_order($user['id'], $id);
        }
        if ($order === null) {
            http_response_code(404);
            $error = 'Pedido não encontrado.';
        }
    } elseif (!$isConfirmation) {
        $quantities = cart_quantities();
        $quote = $_SESSION['checkout_quote'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && $quote !== null && $quote['quantities'] === $quantities) {
            $existing = find_order_by_token($user['id'], $quote['token']);
            if ($existing !== null) {
                unset($_SESSION['cart'], $_SESSION['checkout_quote']);
                show_order_confirmation($existing);
            }
        }
        $cart = cart_snapshot($quantities);
        if (cart_is_orderable($cart)) {
            $signature = checkout_signature($cart);
            if ($quote === null || !hash_equals($quote['signature'], $signature)) {
                $quote = ['token' => bin2hex(random_bytes(32)), 'signature' => $signature, 'quantities' => $quantities];
                $_SESSION['checkout_quote'] = $quote;
            }
            $token = $quote['token'];
        }
    }
} catch (RuntimeException $exception) {
    error_log('Falha na operação de pedido. Código: ' . $exception->getCode());
    http_response_code(503);
    $error = 'Serviço de pedidos temporariamente indisponível. Tente novamente.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isConfirmation ? 'Confirmação do pedido' : 'Finalizar compra' ?> | TechMaker</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <?php $analyticsPage = $isConfirmation ? 'confirmation' : 'checkout'; require __DIR__ . '/../includes/analytics.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/../includes/demo-notice.php'; ?>
    <?php require __DIR__ . '/../includes/cart-panel.php'; ?>
    <header class="main-header">
        <div class="container header-content">
            <a href="index.php" class="logo">TechMaker</a>
            <div class="header-actions">
                <a href="produtos.php">Catálogo</a>
                <a href="registro.php?modo=conta" class="action-icon" aria-label="Minha conta"><i class="fas fa-user" aria-hidden="true"></i></a>
                <?php require __DIR__ . '/../includes/cart-trigger.php'; ?>
            </div>
        </div>
    </header>
    <main class="register-section">
        <div class="container">
            <section class="register-box">
                <h1><?= $isConfirmation ? 'Confirmação do pedido' : 'Resumo do pedido' ?></h1>
                <?php if ($error !== ''): ?>
                    <p class="subtitle" role="alert"><?= $escape($error) ?></p>
                <?php endif; ?>

                <?php if ($order !== null): ?>
                    <p>Pedido nº <?= $escape((string) $order['id']) ?> registrado.</p>
                    <?php if ($order['status'] === 'pending'): ?>
                        <p><strong>Status: pendente.</strong> Nenhum pagamento foi realizado.</p>
                    <?php endif; ?>
                    <ul>
                        <?php foreach ($order['items'] as $item): ?>
                            <li class="cart-line">
                                <?= (int) $item['quantity'] ?> × <?= $escape($item['name']) ?>
                                · <?= $money((int) str_replace('.', '', $item['unit_price'])) ?> por unidade
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <p class="subtotal">Total <strong><?= $money((int) str_replace('.', '', $order['total'])) ?></strong></p>
                <?php elseif ($cart !== null && $cart['items'] === []): ?>
                    <p>Seu carrinho está vazio.</p>
                <?php elseif ($cart !== null): ?>
                    <ul>
                        <?php foreach ($cart['items'] as $item): ?>
                            <li class="cart-line">
                                <strong><?= (int) $item['quantity'] ?> × <?= $escape($item['name']) ?></strong>
                                <?php if ($item['unit_price_cents'] !== null): ?>
                                    <p><?= $money($item['unit_price_cents']) ?> por unidade · Total <?= $money($item['line_total_cents']) ?></p>
                                <?php endif; ?>
                                <?php if ($item['quantity'] > $item['stock']): ?>
                                    <p class="cart-stock-warning">Estoque disponível: <?= (int) $item['stock'] ?>.</p>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if ($token !== null): ?>
                        <p class="subtotal">Total <strong><?= $money($cart['subtotal_cents']) ?></strong></p>
                        <p>O pedido será registrado como pendente.</p>
                        <form action="checkout.php" method="post" class="register-form">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <input type="hidden" name="checkout_token" value="<?= $token ?>">
                            <button type="submit" class="cta-button register-submit-btn">Confirmar pedido</button>
                        </form>
                    <?php else: ?>
                        <p>Ajuste os itens indisponíveis no carrinho para continuar.</p>
                    <?php endif; ?>
                <?php endif; ?>

                <p class="login-link-box"><a href="produtos.php" class="form-link">Voltar ao catálogo</a></p>
            </section>
        </div>
    </main>
    <?php require __DIR__ . '/../includes/cookie-consent.php'; ?>
</body>
</html>
