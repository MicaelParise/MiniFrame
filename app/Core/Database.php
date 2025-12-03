<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    /**
     * Retorna a conexão PDO configurada para SQLite (por padrão)
     */
    public static function getConnection(): PDO
    {
        $dbPath = __DIR__ . '/../../database';
        $dbFile = $dbPath . '/database.sqlite';

        // Garante que o diretório exista
        if (!is_dir($dbPath)) {
            mkdir($dbPath, 0777, true);
        }

        try {
            $pdo = new PDO('sqlite:' . $dbFile, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);

            return $pdo;
        } catch (PDOException $e) {
            die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
        }
    }
}