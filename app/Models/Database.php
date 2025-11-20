<?php

namespace App\Models;

use PDO;

class Database
{
    public static function getConnection()
    {
        $dbPath = __DIR__ . '/../../storage';
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
}