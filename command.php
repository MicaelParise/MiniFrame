<?php

echo "Iniciando comando! \n";

function initPdo(): PDO
{
    $dbPath = __DIR__ . '/database';
    $dbFile = $dbPath . '/database.sqlite';
    
    // Garante que o diretório exista
    if (!is_dir($dbPath)) {
        mkdir($dbPath, 0777, true);
    }
    
    $pdo = new PDO('sqlite:' . $dbFile, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    return $pdo;
}

function createUsersTable(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(155) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL
        );
    ");

    echo "Tabela users criada com sucesso! \n";
    return;
}

$pdo = initPdo();

createUsersTable($pdo);

//echo base64_encode(random_bytes(32)) . PHP_EOL; // Gerar uma Key aleatoria em base64

echo "Fim do comando!";