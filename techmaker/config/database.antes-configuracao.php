<?php

declare(strict_types=1);

function database(): PDO
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    $host = 'sql208.infinityfree.com';
    $port = '3306';
    $name = 'if0_43406335_techmaker';
    $user = 'if0_43406335';
    $password = 'AAAsocorro123 ';

    if ($user === false || $user === '' || $password === false) {
        error_log('Configure DB_USER e DB_PASSWORD para acessar o banco.');
        throw new RuntimeException('Banco de dados temporariamente indisponível.');
    }

    try {
        $connection = new PDO(
            "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        );
       } catch (PDOException $exception) {
        error_log('Falha na conexão PDO. Código: ' . $exception->getCode());

        $codigo = (int) ($exception->errorInfo[1] ?? 0);

       throw new RuntimeException(
            $exception->getMessage(),
            (int) ($exception->errorInfo[1] ?? 0)
        );
    }

    return $connection;
}
