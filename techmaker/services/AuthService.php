<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

function register_user(string $name, string $email, string $password): void
{
    $statement = database()->prepare(
        'INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)'
    );
    try {
        $statement->execute([$name, $email, password_hash($password, PASSWORD_BCRYPT, ['cost' => 12])]);
    } catch (PDOException $exception) {
        if ((int) ($exception->errorInfo[1] ?? 0) === 1062) {
            throw new DomainException('Este e-mail já está cadastrado.');
        }
        throw $exception;
    }
}

function authenticate_user(string $email, string $password): ?array
{
    $statement = database()->prepare('SELECT id, full_name, password_hash FROM users WHERE email = ?');
    $statement->execute([$email]);
    $user = $statement->fetch();

    // Uma verificação também é feita quando o endereço não existe.
    $hash = $user['password_hash'] ?? '$2y$12$aV97EGuK14GKEX2.13heiOVn85UtBurGkRxpMXloV4fMwDQ/MeXLq';
    return password_verify($password, $hash) && $user ? $user : null;
}
