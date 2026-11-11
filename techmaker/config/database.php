<?php
declare(strict_types=1);

function database(): PDO
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    try {
        $connection = new PDO(
            'mysql:host=sql208.infinityfree.com;port=3306;dbname=if0_43406335_techmaker;charset=utf8mb4',
            'if0_43406335',
            'AAAsocorro123',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    } catch (PDOException $exception) {
        error_log('Falha na conexao PDO. Codigo: ' . $exception->getCode());
        throw new RuntimeException('Banco de dados temporariamente indisponivel.', 0, $exception);
    }

    return $connection;
}