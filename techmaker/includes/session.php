<?php

declare(strict_types=1);

require_once __DIR__ . '/http.php';

function start_store_session(): void
{
    store_response_headers();
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $https = isset($_SERVER['HTTPS']) && !in_array(strtolower($_SERVER['HTTPS']), ['', 'off', '0'], true);
    if (!session_start([
        'use_strict_mode' => true,
        'use_only_cookies' => true,
        'use_trans_sid' => false,
        'cookie_lifetime' => 0,
        'cookie_secure' => $https || getenv('SESSION_COOKIE_SECURE') === '1',
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ])) {
        throw new RuntimeException('Não foi possível iniciar a sessão.');
    }
}

function csrf_token(): string
{
    return $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
}

function valid_csrf_token(mixed $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_user(): array
{
    $user = current_user();
    if ($user === null) {
        header('Location: registro.php?modo=login', true, 303);
        exit;
    }

    return $user;
}

function change_authenticated_user(?array $user): void
{
    if (!session_regenerate_id(true)) {
        throw new RuntimeException('Não foi possível renovar a sessão.');
    }

    unset($_SESSION['user'], $_SESSION['csrf_token'], $_SESSION['checkout_quote']);
    if ($user !== null) {
        $_SESSION['user'] = ['id' => (int) $user['id'], 'name' => $user['full_name']];
    }
}
